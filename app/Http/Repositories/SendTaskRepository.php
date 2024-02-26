<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\DeleteForMyTaskRequest;
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
        return response()->json(["message" => "Taklifingiz partner tomonidan ko'rib chiqiladi!", "data" => $task], 200);
    }

    public function updateSendTask(Request $request, int $send_task_id)
    {
        $task = SendTask::find($send_task_id);
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"], 404);
        }
        $task->update([
            'partner_id' => $request->partner_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        return response()->json(["message" => "Task o'zgartirildi!"], 200);
    }

    public function acceptForMyTask(int $send_task_id)
    {

        $send_task = SendTask::select('*')
            ->where('id', $send_task_id)
            ->where('accept', false)
            ->where('decline', false)
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

    public function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id)
    {
        try {
            $send_task = SendTask::select('*')
                ->where('id', $send_task_id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', Auth::user()->id)->first();

            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"]);
            }
            $send = SendTask::find($send_task_id);
            $send->update([
                'title' => $request->title
            ]);

            $send_task->decline = true;
            $send_task->save();
            return response()->json(["message" => "Taskni qabul qilmaganingiz tasdiqlandi!"]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function forMeTasks()
    {
        $task = SendTask::select('send_tasks.id as send_task_id', 'task_name', 'category_name', 'description', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.user_id')
            ->where('send_tasks.partner_id', Auth::user()->id)
            ->where('accept', false)
            ->where('decline', false)
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(20);
        return $task;
    }

    public function mySendTasks()
    {
        $task_accept = request('task_accept');
        $task_decline = request('task_decline');
        $task = request('task');

        $get = SendTask::select('send_tasks.id as send_task_id', 'task_name', 'category_name', 'description', 'high', 'original_task', 'username')
        ->join('users', 'users.id', '=', 'send_tasks.user_id')
        ->when($task_accept, function ($query) use ($task_accept) {
            $query->where('accept', $task_accept)
                ->where('decline', false);
        })
        ->when($task_decline, function ($query) use ($task_decline) {
            $query->where('decline', $task_decline)
                ->where('accept', false);
        })
        ->when($task, function ($query) use ($task) {
            $query->where('decline', $task)
                ->where('accept', $task);
        })
        ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
        ->orderBy('original_task', 'asc')
        ->paginate(20);
    return $get;
    }

}
