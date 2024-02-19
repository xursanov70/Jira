<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\SendTaskRequest;
use App\Models\SendTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendTaskRepository implements SendTaskInterface
{

    public function createSendTask(SendTaskRequest $request)
    {
        $task = SendTask::create([
            'user_id' => Auth::user()->id,
            'partner_id' => $request->partner_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Taklifingiz partner tomonidan ko'rib chiqiladi!", "data" => $task]);
    }

    public function updateSendTask(Request $request, $send_task_id)
    {
        $task = SendTask::find($send_task_id);
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        }
        $task->update([
            'user_id' => Auth::user()->id,
            'partner_id' => $request->partner_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task o'zgartirildi!"]);
    }

    public function getForMyTask()
    {
        $task = SendTask::select('task_name', 'category_name', 'description', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.user_id')
            ->where('send_tasks.partner_id', Auth::user()->id)
            ->where('accept', false)
            ->where('deleted_is', false)
            ->orderBy('high', 'asc')
            ->paginate(15);
        return $task;
    }

    public function acceptForMyTask($send_task_id)
    {

        $send_task = SendTask::select('*')
            ->where('id', $send_task_id)
            ->where('accept', false)
            ->where('deleted_is', false)
            ->where('partner_id', Auth::user()->id)->first();
        if (!$send_task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        }
        $send_task->accept = true;
        $send_task->save();

        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');
        Task::create([
            'user_id' => Auth::user()->id,
            'task_name' => $send_task->task_name,
            'description' => $send_task->description,
            'category_name' => $send_task->category_name,
            'start_task' => $formattedTime,
            'original_task' => $send_task->original_task,
            'high' => $send_task->high,
        ]);
        return response()->json(["message" => "Taskni qabul qildingiz!"]);
    }

    public function deleteForMyTask($send_task_id)
    {
        $send_task = SendTask::select('*')
            ->where('id', $send_task_id)
            ->where('accept', false)
            ->where('deleted_is', false)
            ->where('partner_id', Auth::user()->id)->first();

        if (!$send_task) {
            return response()->json(["message" => "Task mavjud emas!"]);
        }
        $send_task->deleted_is = true;
        $send_task->save();
        return response()->json(["message" => "Taskni qabul qilmaganingiz tasdiqlandi!"]);
    }

    public function acceptTasks()
    {
        $task = SendTask::select('task_name', 'category_name', 'description', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.partner_id')
            ->where('user_id', Auth::user()->id)
            ->where('accept', true)
            ->orderBy('high', 'asc')
            ->paginate(15);
        return $task;
    }

    public function deletedTasks()
    {
        $task = SendTask::select('task_name', 'category_name', 'description', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.partner_id')
            ->where('user_id', Auth::user()->id)
            ->where('deleted_is', true)
            ->orderBy('high', 'asc')
            ->paginate(15);
        return $task;
    }
}
