<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\OriginalTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\SendTaskRequest;
use App\Http\Requests\ShareTaskRequest;
use App\Models\SendTask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\AcceptNotification;
use App\Notifications\DeclineNotification;
use App\Notifications\SendTaskNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\String\b;

class SendTaskRepository implements SendTaskInterface
{

    public function createSendTask(SendTaskRequest $request)
    {
        try {
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
            $user = User::find($request->partner_id);

            $message = [
                "hi" => "Sizga yangi task keldi",
                "task_name" => $request->task_name,
                "description" => $request->description,
                "category_name" => $request->category_name,
                "original_task" => $request->original_task,
                "high" => $request->high
            ];
            $user->notify(new SendTaskNotification($message));

            return response()->json(["message" => "Taklifingiz partner tomonidan ko'rib chiqiladi!", "data" => $task], 201);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updateSendTask(Request $request, int $send_task_id)
    {
        $task = SendTask::select('*')
        ->where('id', $send_task_id)
        ->where('user_id', Auth::user()->id)
        ->where('accept', false)
        ->where('decline', false)
        ->first();
        if (!$task) {
            return response()->json(["message" => "Task mavjud emas!"], 403);
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

    public function acceptForMyTask(OriginalTaskRequest $request, int $send_task_id)
    {
        try {
            $auth = Auth::user()->id;

            $send_task = SendTask::select('*')
                ->where('id', $send_task_id)
                ->where('partner_id', Auth::user()->id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', $auth)
                ->first();

            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }
            $send_task->update([
                'original_task' => $request->original_task
            ]);

            $user = User::find($send_task->user_id);

            $message = [
                "hi" => "Yuborilgan taskingiz partner tomonidan qabul qilindi",
                "task_name" => $send_task->task_name,
                "description" => $send_task->description,
                "category_name" => $send_task->category_name,
                "original_task" => $send_task->original_task,
                "high" => $send_task->high
            ];
            $user->notify(new AcceptNotification($message));

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
            $task = Task::find($send_task->last_task_id);
            if ($task) {
                $task->delete();
            }

            return response()->json(["message" => "Taskni qabul qildingiz!", "data" => $return], 200);
        } catch (Exception $e) {
            return $e;
        }
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
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }
            $send_task->update([
                'title' => $request->title
            ]);

            $user = User::find($send_task->user_id);

            $message = [
                "hi" => "Yuborilgan taskingiz partner tomonidan rad qilindi",
                "task_name" => $send_task->task_name,
                "description" => $send_task->description,
                "category_name" => $send_task->category_name,
                "original_task" => $send_task->original_task,
                "high" => $send_task->high,
                "title" => $send_task->title,
            ];
            $user->notify(new DeclineNotification($message));

            $send_task->decline = true;
            $send_task->save();

            return response()->json(["message" => "Taskni bekor qilganingiz tasdiqlandi!"], 200);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function forMeTasks()
    {

        $task = SendTask::select('send_tasks.id as send_task_id', 'task_name',  'category_name', 'description', 'high', 'original_task', 'username', 'send_time')
            ->join('users', 'users.id', '=', 'send_tasks.user_id')
            ->where('send_tasks.partner_id', Auth::user()->id)
            ->where('accept', false)
            ->where('decline', false)

            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
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
            ->paginate(15);
        return $get;
    }

    public function shareTask(ShareTaskRequest $request)
    {
        try{

        $auth = Auth::user()->id;
        $formattedTime = now('Asia/Tashkent')->format('Y-m-d H:i');

        $task = Task::select('*')->where('user_id', $auth)
            ->where('active', true)
            ->where('status', 'enable')
            ->where('id', $request->task_id)->first();

        if (!$task) {
            return response()->json(["message" => "Yuborilgan taskni yana qayta yubora olmaysiz!"], 403);
        }

        SendTask::create([
            'last_task_id' => $task->id,
            'user_id' => $auth,
            'partner_id' => $request->user_id,
            'task_name' => $task->task_name,
            'description' => $task->description,
            'category_name' => $task->category_name,
            'original_task' => $task->original_task,
            'high' => $task->high,
            'send_time' => $formattedTime
        ]);
        $user = User::find($request->user_id);

            $message = [
                "hi" => "Sizga yangi task keldi",
                "task_name" => $task->task_name,
                "description" => $task->description,
                "category_name" => $task->category_name,
                "original_task" => $task->original_task,
                "high" => $task->high
            ];
            $user->notify(new SendTaskNotification($message));

            $task->status = 'disable';
            $task->save();

        return response()->json(["message" => "Task muvaffaqqiyatli  jo'natildi!"], 200);
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

    public function sendDeclineTAsk(SendDeclineTaskRequest $request)
    {
        try {
            $decline_task =  SendTask::select('*')->where('id', $request->decline_task_id)
            ->where('user_id', Auth::user()->id)
                ->where('decline', true)
                ->where('accept', false)
                ->first();

            if (!$decline_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }

            $decline_task->update([
                'partner_id' => $request->partner_id,
            ]);
            $decline_task->decline = false;
            $decline_task->save();
            $user = User::find($decline_task->partner_id);

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
}
