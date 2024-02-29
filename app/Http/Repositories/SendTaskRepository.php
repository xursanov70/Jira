<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\SendTaskRequest;
use App\Models\SendTask;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendTaskRepository implements SendTaskInterface
{

    public function createSendTask(SendTaskRequest $request)
    {
        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');

        $task = SendTask::create([
            'user_id' => Auth::user()->id,
            'partner_id' => $request->partner_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
            'send_time' => $formattedTime
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

    public function acceptForMyTask(Request $request, int $send_task_id)
    {
        try {
            $auth = Auth::user()->id;

            $send_task = SendTask::select('*')
                ->where('id', $send_task_id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', $auth)
                ->first();
            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"], 404);
            }
            $send_task->update([
                'original_task' => $request->original_task
            ]);
            $send_task->accept = true;
            $send_task->save();

            $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');
            $return = Task::create([
                'user_id' => $auth,
                'task_name' => $send_task->task_name,
                'description' => $send_task->description,
                'category_name' => $send_task->category_name,
                'start_task' => $formattedTime,
                'original_task' => $send_task->original_task,
                'high' => $send_task->high,
            ]);
            return response()->json(["message" => "Taskni qabul qildingiz!", "data" => $return], 200);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id)
    {
        try {

            $send_task = SendTask::select('id', 'accept', 'decline', 'partner_id', 'title')
                ->where('id', $send_task_id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', Auth::user()->id)->first();

            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"], 404);
            }
            $send = SendTask::find($send_task_id);
            $send->update([
                'title' => $request->title
            ]);

            $send_task->decline = true;
            $send_task->save();

            return response()->json(["message" => "Taskni bekor qilganingiz tasdiqlandi!"], 200);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function forMeTasks()
    {

        $task = SendTask::select('send_tasks.id as send_task_id', 'task_name', 'category_name', 'description', 'high', 'original_task', 'username', 'send_time')
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

        $accept = request('accept');
        $decline = request('decline');
        $auth = Auth::user()->id;

        $get = SendTask::select('send_tasks.id as send_task_id', 'task_name', 'title', 'category_name', 'description', 'accept', 'decline', 'high', 'original_task', 'username')
            ->join('users', 'users.id', '=', 'send_tasks.partner_id')
            ->where('send_tasks.user_id', $auth)
            ->when($accept !== null, function ($query) use ($accept) {
                return $query->where('accept', $accept);
            })
            ->when($decline !== null, function ($query) use ($decline) { //null, 0 false
                return $query->where('decline', $decline);
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')") 
            ->orderBy('original_task', 'asc')
            ->paginate(20);
        return $get;
    }
}
