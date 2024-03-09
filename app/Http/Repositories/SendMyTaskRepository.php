<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendMyTaskInterface;
use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\ShareTaskRequest;
use App\Models\SendTask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SendTaskNotification;
use Exception;
use Illuminate\Support\Facades\Auth;


class SendMyTaskRepository implements SendMyTaskInterface{

    public function sendDeclineTAsk(SendDeclineTaskRequest $request)
    {

        try {
            $request_partner_id = $request->partner_id;

            $decline_task =  SendTask::select('*')->where('id', $request->decline_task_id)
                ->where('user_id', Auth::user()->id)
                ->where('decline', true)
                ->where('accept', false)
                ->first();

            if (!$decline_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }

            $decline_task->update([
                'partner_id' => $request_partner_id,
            ]);
            $decline_task->decline = false;
            $decline_task->save();
            $user = User::find($request_partner_id);

            $message = [
                "hi" => "Sizga yangi task keldi",
                "task_name" => $decline_task->task_name,
                "description" => $decline_task->description,
                "category_name" => $decline_task->category_name,
                "original_task" => $decline_task->original_task,
                "high" => $decline_task->high
            ];
            $user->notify(new SendTaskNotification($message));
            return response()->json(["message" => "Task muvaffaqqiyatli jo'natildi!"], 200);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addMyTask(AddMyTaskRequest $request)
    {
        try {
            $auth = Auth::user()->id;
            $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');

            $decline_task = SendTask::select('*')->where('user_id', $auth)
                ->where('id', $request->send_decline_task_id)
                ->where('decline', true)
                ->where('accept', false)
                ->first();

            if (!$decline_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }

            Task::create([
                'user_id' => $auth,
                'task_name' => $decline_task->task_name,
                'description' => $decline_task->description,
                'category_name' => $decline_task->category_name,
                'start_task' => $formattedTime,
                'original_task' => $decline_task->original_task,
                'high' => $decline_task->high
            ]);
            $find = SendTask::find($decline_task->id);
            $find->delete();
            return response()->json(["message" => "Tasklaringiz ro'yxatiga muvaffaqqiyatli qo'shildi!"], 200);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function shareTask(ShareTaskRequest $request)
    {
        try {

            $auth = Auth::user()->id;
            $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');
            $request_user_id = $request->user_id;

            $task = Task::select('*')->where('user_id', $auth)
                ->where('active', true)
                ->where('status', 'enable')
                ->where('id', $request->task_id)->first();
            if (!$task) {
                return response()->json(["message" => "Yuborilgan taskni yana qayta yubora olmaysiz!"], 403);
            }

          $message =  SendTask::create([
                'last_task_id' => $task->id,
                'user_id' => $auth,
                'partner_id' => $request_user_id,
                'task_name' => $task->task_name,
                'description' => $task->description,
                'category_name' => $task->category_name,
                'original_task' => $task->original_task,
                'high' => $task->high,
                'send_time' => $formattedTime
            ]);
            $user = User::find($request_user_id);
            $user->notify(new SendTaskNotification($message));

            $task->status = 'disable';
            $task->save();

            return response()->json(["message" => "Task muvaffaqqiyatli  jo'natildi!"], 200);
        } catch (Exception $e) {
            return $e;
        }
    }
}