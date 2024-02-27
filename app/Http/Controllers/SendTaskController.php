<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\SendTaskRequest;
use Illuminate\Http\Request;

class SendTaskController extends Controller
{
    public function __construct(protected SendTaskInterface $sendTaskInterface)
    {
    }

    public function createSendTask(SendTaskRequest $request)
    {
        return $this->sendTaskInterface->createSendTask($request);
    }

    public function updateSendTask(Request $request, int $send_task_id)
    {
        return $this->sendTaskInterface->updateSendTask($request, $send_task_id);
    }

    public function acceptForMyTask(Request $request, int $send_task_id)
    {

        return $this->sendTaskInterface->acceptForMyTask($request, $send_task_id);
    }

    public function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id)
    {
        return $this->sendTaskInterface->declineForMyTask($request, $send_task_id);
    }

    public function forMeTasks()
    {
        return $this->sendTaskInterface->forMeTasks();
    }

    public function mySendTasks()
    {
        return $this->sendTaskInterface->mySendTasks();
    }
}
