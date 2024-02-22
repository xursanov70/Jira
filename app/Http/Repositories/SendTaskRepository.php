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
        $accept = request('accept');
        $decline = request('decline');
        $my_task = request('my_task');

        $task = SendTask::select('send_tasks.id as send_task_id', 'task_name', 'category_name', 'description', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.partner_id')
            ->when($accept, function ($query) use ($accept) {
                $query->where('category_name', $accept)
                    ->where('send_tasks.accept', true);
            })
            ->when($decline, function ($query) use ($decline) {
                $query->where('category_name', $decline)
                    ->where('send_tasks.decline', true);
            })
            ->when($my_task, function ($query) use ($my_task) {
                $query->where('category_name', $my_task)
                    ->where('tasks.partner_id', Auth::user()->id);
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
        return $task;
    }
}
