<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{

    public function createTask(TaskRequest $request)
    {

        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');
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
        $task = Task::select('*')->where('id', $task_id)
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
        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i:s');

        $task = Task::select('*')->where('id', $task_id)
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
            $task->active = false;
            $task->save();
            return response()->json(["message" => "Task muvaffaqqiyatli tugatildi!", "data" => $task], 200);
        }
    }



    public function searchTask()
    {
        $search = request('search');

        $task = Task::select('tasks.id as task_id',  'description', 'task_name', 'username', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%$search%")
                    ->orWhere('task_name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
            })
            ->orderBy('tasks.id', 'asc')
            ->paginate(15);
        return TaskResource::collection($task);
    }


    public function user()
    {

        $finish = request('finish');
        $continue = request('continue');
        $late = request('late');
        $auth = Auth::user()->id;

        $task = Task::select('tasks.id as task_id', 'tasks.active', 'tasks.status', 'description', 'task_name', 'username', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('tasks.user_id', $auth)
            ->when($finish, function ($query) use ($finish) {
                $query->where('category_name', "$finish")
                    ->where('tasks.active', false);
            })
            ->when($continue, function ($query) use ($continue) {
                $query->where('category_name', "$continue")
                    ->where('end_task', null)
                    ->where('tasks.active', true);
            })
            ->when($late, function ($query) use ($late) {
                $query->where('category_name', "$late")
                    ->where('tasks.active', true)
                    ->where('original_task', '<', now());
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
        return $task;
    }

    public function admin()
    {
        $finish = request('finish');
        $continue = request('continue');
        $late = request('late');

        $task = Task::select('tasks.id as task_id',  'description', 'task_name', 'username', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->when($finish, function ($query) use ($finish) {
                $query->where('category_name', "$finish")
                    ->where('tasks.active', false);
            })
            ->when($continue, function ($query) use ($continue) {
                $query->where('category_name', "$continue")
                    ->where('end_task', null)
                    ->where('tasks.active', true);
            })
            ->when($late, function ($query) use ($late) {
                $query->where('category_name', "$late")
                    ->where('tasks.active', true)
                    ->where('original_task', '<', now());
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
        return $task;
    }
}
