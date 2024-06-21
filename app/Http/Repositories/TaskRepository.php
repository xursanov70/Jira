<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\AddEndTaskRequest;
use App\Http\Requests\DeleteEndTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\EndTaskJob;
use App\Models\SendTask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\EndTaskNotification;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{
    public $taskId = 'tasks.id as task_id';

    public function createTask(TaskRequest $request)
    {

        $formattedTime = date('Y-m-d H:i');
        $task = Task::create([
            'user_id' => Auth::user()->id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'start_task' => $formattedTime,
            'original_task' => $request->original_task,
            'high' => $request->high
        ]);
        return response()->json(["message" => "Task muvaffaqqiyatli yaratildi!", "data" => $task], 201);
    }

    public function updateTask(UpdateTaskRequest $request, int $task_id)
    {
        $task = Task::where('id', $task_id)
            ->where('user_id', Auth::user()->id)
            ->where('active', true)
            ->where('status', 'enable')
            ->first();

        if (!$task) {
            return response()->json(["message" => "Yuborilgan taskni tahrirlay olmaysiz!"], 403);
        }
        $task->update([
            'category_name' => $request->category_name,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task muvaffaqqiyatli o'zgartirildi!"], 200);
    }


    public function endTask(int $task_id)
    {
        try {
            $formattedTime = date('Y-m-d H:i');

            $task = Task::where('id', $task_id)
                ->where('user_id', Auth::user()->id)
                ->where('active', true)
                ->where('status', 'enable')
                ->first();

            if (!$task) {
                return response()->json(["message" => "Yuborilgan taskni tugata olmaysiz!"], 403);
            } else {
                $task->update([
                    'end_task' => $formattedTime
                ]);
                $real_task = SendTask::where('id', $task->real_task)->first();
                if ($real_task) {
                    $real_task->update(['end_task_time' => $formattedTime]);

                    $user = User::where('id', $real_task->user_id)->where('active', true)->first();
                    $message = [
                        "task_name" => $task->task_name,
                        "description" => $task->description,
                        "category_name" => $task->category_name,
                        "original_task" => $task->original_task,
                        "high" => $task->high,
                    ];
                    if ($user->send_email == true) {
                        dispatch(new EndTaskJob($message, $user));
                        // $user->notify(new EndTaskNotification($message));
                    }
                }
                $task->active = false;
                $task->save();
                return response()->json(["message" => "Task muvaffaqqiyatli tugatildi!", "data" => $task], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Task tugatishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }

    public function searchTask()
    {
        $search = request('search');

        $task = Task::select(
            $this->taskId,
            'description',
            'task_name',
            'username',
            'start_task',
            'end_task',
            'original_task',
            'high',
            'category_name'
        )
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%$search%")
                    ->orWhere('task_name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
            })
            ->orderBy('task_id', 'asc')
            ->paginate(15);
        return TaskResource::collection($task);
    }


    public function user()
    {

        $finish = request('finish');
        $continue = request('continue');
        $late = request('late');
        $auth = Auth::user()->id;

        return Task::select(
            $this->taskId,
            'tasks.active',
            'tasks.status',
            'description',
            'task_name',
            'username',
            'start_task',
            'end_task',
            'original_task',
            'high',
            'category_name'
        )
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('tasks.user_id', $auth)
            ->when($finish, function ($query) use ($finish) {
                $query->where('category_name', $finish)
                    ->where('tasks.active', false)
                    ->orderBy('end_task', 'desc');
            })
            ->when($continue, function ($query) use ($continue) {
                $query->where('category_name', $continue)
                    ->where('end_task', null)
                    ->where('tasks.active', true);
            })
            ->when($late, function ($query) use ($late) {
                $query->where('category_name', $late)
                    ->where('end_task', null)
                    ->where('tasks.active', true)
                    ->where('original_task', '<', date('Y-m-d H:i'));
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
    }

    public function admin()
    {
        if (Auth::user()->status != 'admin'){
            return response()->json(["message" => "Sizning huquqingiz yo'q!"], 403);
        }
        $finish = request('finish');
        $continue = request('continue');
        $late = request('late');

        return Task::select(
            $this->taskId,
            'description',
            'task_name',
            'username',
            'start_task',
            'end_task',
            'original_task',
            'high',
            'category_name'
        )
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->when($finish, function ($query) use ($finish) {
                $query->where('category_name', $finish)
                    ->where('tasks.active', false);
            })
            ->when($continue, function ($query) use ($continue) {
                $query->where('category_name', $continue)
                    ->where('end_task', null)
                    ->where('tasks.active', true);
            })
            ->when($late, function ($query) use ($late) {
                $query->where('category_name', $late)
                    ->where('tasks.active', true)
                    ->where('original_task', '<', date('Y-m-d H:i'));
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
    }

    public function addEndTask(AddEndTaskRequest $request)
    {
        $task = Task::where('id', $request->end_task_id)->where('user_id', Auth::user()->id)->where('active', false)
            ->where('end_task', '!=', null)
            ->first();
        if (!$task)
            return response()->json(['message' => "Task mavjud emas"], 403);
        $task->active = true;
        $task->end_task = null;
        $task->save();
        return response()->json(['message' => "Tasklaringiz ro'yxatiga qo'shildi"], 200);
    }

    public function deleteEndTask(DeleteEndTaskRequest $request)
    {
        $task = Task::where('id', $request->end_task_id)->where('user_id', Auth::user()->id)->where('active', false)
            ->where('end_task', '!=', null)
            ->first();
        if (!$task)
            return response()->json(['message' => "Task mavjud emas"], 403);
        $task->delete();
        return response()->json(['message' => "Task muvaffaqqiyatli o'chirildi!"], 200);
    }
}
