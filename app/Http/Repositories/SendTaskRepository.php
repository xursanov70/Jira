<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\OriginalTaskRequest;
use App\Http\Requests\SendTaskRequest;
use App\Jobs\AcceptTaskJob;
use App\Jobs\DeclineTaskJob;
use App\Jobs\SendTaskJob;
use App\Models\SendTask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\AcceptTaskNotification;
use App\Notifications\DeclineTaskNotification;
use App\Notifications\SendTaskNotification;
use Illuminate\Support\Facades\Auth;

class SendTaskRepository implements SendTaskInterface
{
    public function createSendTask(SendTaskRequest $request)
    {
        try {
        $formattedTime = date('Y-m-d H:i');
        $partner = $request->partner_id;
        $auth = Auth::user()->id;
        $user = User::where('id', $partner)->where('active', true)->first();
        if ($partner == $auth) {
            return response()->json(["message" => "O'zingiz uchun task yarata olmaysiz!"], 403);
        }

        $taskMessage = SendTask::create([
            'user_id' => $auth,
            'partner_id' => $partner,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
            'send_time' => $formattedTime
        ]);
        auth()->user()->notify(new SendTaskNotification($taskMessage));

        if ($user->send_email == true) {
            dispatch(new SendTaskJob($taskMessage, $user));
        }

        return response()->json(["message" => "Task muvaffaqqiyatli yuborildi!", "data" => $taskMessage], 201);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Task yuborishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }

    public function updateSendTask(SendTaskRequest $request, int $send_task_id)
    {
        $taskMessage = SendTask::where('id', $send_task_id)->where('user_id', Auth::user()->id)
            ->where('accept', false)
            ->where('decline', false)
            ->first();
        if (!$taskMessage) {
            return response()->json(["message" => "Taskni o'zgartira olmaysiz!"], 403);
        }
        $original_partner_id = $taskMessage->partner_id;
        $partner_id = $request->partner_id;

        $taskMessage->update([
            'partner_id' => $partner_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'category_name' => $request->category_name,
            'original_task' => $request->original_task,
            'high' => $request->high,
        ]);
        if ($original_partner_id != $partner_id) {
            $user = User::where('id', $partner_id)
                ->where('active', true)
                ->first();
                auth()->user()->notify(new SendTaskNotification($taskMessage));
            if ($user->send_email == true) {
                dispatch(new SendTaskJob($taskMessage, $user));
            }
        }
        return response()->json(["message" => "Task o'zgartirildi!"], 200);
    }

    public function acceptForMyTask(OriginalTaskRequest $request, int $send_task_id)
    {
        try {
            $auth = Auth::user()->id;

            $send_task = SendTask::where('id', $send_task_id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', $auth)->first();

            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }
            $send_task->update([
                'original_task' => $request->original_task
            ]);

            $user = User::where('id', $send_task->user_id)->where('active', true)->first();

            $formattedTime = date('Y-m-d H:i:s');
            $taskMessage = Task::create([
                'user_id' => $auth,
                'task_name' => $send_task->task_name,
                'description' => $send_task->description,
                'category_name' => $send_task->category_name,
                'start_task' => $formattedTime,
                'original_task' => $send_task->original_task,
                'high' => $send_task->high,
                'real_task' => $send_task->id
            ]);

            auth()->user()->notify(new AcceptTaskNotification($taskMessage));

            if ($user->send_email == true) {
                dispatch(new AcceptTaskJob($taskMessage, $user));
            }

            $task = Task::where('id', $send_task->last_task_id)->first();
            if ($task) {
                $task->delete();
            }
            $send_task->accept = true;
            $send_task->save();

            return response()->json(["message" => "Taskni qabul qildingiz!", "data" => $taskMessage], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Taskni qabul qilishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }

    public function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id)
    {
        try {
            $send_task = SendTask::where('id', $send_task_id)
                ->where('accept', false)
                ->where('decline', false)
                ->where('partner_id', Auth::user()->id)->first();

            if (!$send_task) {
                return response()->json(["message" => "Task mavjud emas!"], 403);
            }
            $send_task->update([
                'title' => $request->title
            ]);

            $user = User::where('id', $send_task->user_id)->where('active', true)->first();

            auth()->user()->notify(new DeclineTaskNotification($send_task));

            if ($user->send_email == true) {
                dispatch(new DeclineTaskJob($send_task, $user));
            }

            $task_status = Task::where('id', $send_task->last_task_id)->first();
            if ($task_status) {
                $task_status->status = 'enable';
                $task_status->save();
            }
            $send_task->decline = true;
            $send_task->save();

            return response()->json(["message" => "Taskni bekor qilganingiz tasdiqlandi!"], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Taskni rad qilishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }

    public function forMeTasks()
    {
        return SendTask::select(
            'send_tasks.id as send_task_id',
            'task_name',
            'category_name',
            'description',
            'high',
            'original_task',
            'username',
            'end_task_time',
            'send_time'
        )
            ->join('users', 'users.id', '=', 'send_tasks.user_id')
            ->where('send_tasks.partner_id', Auth::user()->id)
            ->where('accept', false)
            ->where('decline', false)

            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
    }

    public function mySendTasks()
    {
        $accept = request('accept');
        $decline = request('decline');
        $auth = Auth::user()->id;

        return SendTask::select(
            'send_tasks.id as send_task_id',
            'task_name',
            'title',
            'end_task_time',
            'high',
            'original_task',
            'username',
            'category_name',
            'description',
            'accept',
            'decline'
        )
            ->join('users', 'users.id', '=', 'send_tasks.partner_id')
            ->where('send_tasks.user_id', $auth)
            ->when($accept !== null, function ($query) use ($accept) {
                return $query->where('accept', $accept);
            })
            ->when($decline !== null, function ($query) use ($decline) { 
                return $query->where('decline', $decline);
            })
            ->orderByRaw("FIELD(high, 'high', 'medium', 'low')")
            ->orderBy('original_task', 'asc')
            ->paginate(15);
    }
}
