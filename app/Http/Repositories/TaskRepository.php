<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\MyTaskResource;
use App\Http\Resources\NowContinueTaskResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
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
            'category_id' => $request->category_id,
            'start_task' => $formattedTime,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task muvaffaqqiyatli yaratildi!", "data" => $task]);
    }

    public function updateTask(Request $request, $task_id)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        }
        $task->update([
            'category_id' => $request->category_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task updated successfully!"]);
    }


    public function getMyTasks()
    {
        $task = Task::select('*')->where('user_id', Auth::user()->id)
        ->orderBy('high', 'asc')
        ->paginate(15);
        return MyTaskResource::collection($task);
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

    public function getTasks()
    {
        $get = Task::paginate(15);
        return TaskResource::collection($get);
    }

    public function officialTasks()
    {
        $tasks = Task::select('tasks.id as task_id', 'description', 'task_name','username', 'category_id', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('categories', 'categories.id', '=', 'tasks.category_id')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('category_name', 'Official')
            ->orderBy('start_task', 'desc')
            ->paginate(15);
        return TaskResource::collection($tasks);
    }

    public function personalTasks()
    {
        $tasks = Task::select('tasks.id as task_id',  'description', 'task_name', 'username', 'category_id', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('categories', 'categories.id', '=', 'tasks.category_id')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('category_name', 'Personal')
            ->orderBy('start_task', 'desc')
            ->paginate(15);
        return TaskResource::collection($tasks);
    }

    public function finishedTasks()
    {
        $tasks = Task::select('tasks.id as task_id',  'description', 'task_name', 'username', 'category_id', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
            ->join('categories', 'categories.id', '=', 'tasks.category_id')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('tasks.active', false)
            ->orderBy('start_task', 'desc')
            ->paginate(15);

        return TaskResource::collection($tasks);
    }

    public function nowContinueTasks()
    {
        $tasks = Task::select('tasks.id as task_id',  'description', 'task_name', 'username', 'category_id', 'start_task',  'original_task', 'high', 'category_name')
            ->join('categories', 'categories.id', '=', 'tasks.category_id')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('tasks.active', true)
            ->orderBy('start_task', 'desc')
            ->paginate(15);

        return NowContinueTaskResource::collection($tasks);
    }

    public function filterTask(){
        $search = request('search');

        $task = Task::select('tasks.id as task_id', 'description', 'task_name','username', 'category_id', 'start_task', 'end_task', 'original_task', 'high', 'category_name')
        ->join('categories', 'categories.id', '=', 'tasks.category_id')
        ->join('users', 'users.id', '=', 'tasks.user_id')
        ->when($search, function ($query) use ($search) {
            $query->where('task_name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('original_task', 'like', "%$search%")
                ->orWhere('start_task', 'like', "%$search%")
                ->orWhere('end_task', 'like', "%$search%");
        })
        ->orderBy('tasks.id', 'asc')
        ->paginate(15);
        return TaskResource::collection($task);
    }
}
