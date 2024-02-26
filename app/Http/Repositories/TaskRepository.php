<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
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
        return response()->json(["message" => "Task muvaffaqqiyatli yaratildi!", "data" => $task]);
    }

    public function updateTask(Request $request, int $task_id)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        }
        $task->update([
            'category_name' => $request->category_name,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task updated successfully!"]);
    }


    public function endTask(int $task_id)
    {
        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i:s');

        $task = Task::find($task_id);
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        } else {
            $task->update([
                'end_task' => $formattedTime,
            ]);
            $task->active = false;
            $task->save();
            return response()->json(["message" => "Task muvaffaqqiyatli tugatildi!", "data" => $task]);
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
            ->orderBy('users.id', 'asc')
            ->paginate(20);
        return TaskResource::collection($task);
    }


    public function user()
    {

        $finish = request('finish');
        $continue = request('continue');

        $task = Task::select('tasks.id as task_id', 'tasks.active',  'description', 'task_name', 'username', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->when($finish, function ($query) use ($finish) {
                $query->where('category_name', "$finish")
                    ->where('tasks.active', false)
                    ->where('tasks.user_id', Auth::user()->id);
            })
            ->when($continue, function ($query) use ($continue) {
                $query->where('category_name', "$continue")
                    ->where('tasks.active', true)
                    ->where('tasks.user_id', Auth::user()->id);
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(20);
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
                    ->where('tasks.active', true);
            })
            ->when($late, function ($query) use ($late) {
                $query->where('category_name', "$late")
                    ->where('original_task', '<', now())
                    ->where('tasks.active', true);
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(20);
        return $task;
    }
}
