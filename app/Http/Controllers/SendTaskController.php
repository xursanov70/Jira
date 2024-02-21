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

    public function getForMyTask()
    {
        return $this->sendTaskInterface->getForMyTask();
    }

    public function acceptForMyTask(int $send_task_id)
    {

        return $this->sendTaskInterface->acceptForMyTask($send_task_id);
    }

    public function noAcceptForMyTask(DeleteForMyTaskRequest $request, int $send_task_id)
    {
        return $this->sendTaskInterface->noAcceptForMyTask($request, $send_task_id);
    }

    public function acceptTasks()
    {
        return $this->sendTaskInterface->acceptTasks();
    }

    public function noAcceptTasks()
    {
        return $this->sendTaskInterface->noAcceptTasks();
    }
}
