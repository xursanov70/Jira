<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\SendTaskInterface;
use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\OriginalTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\SendTaskRequest;
use App\Http\Requests\ShareTaskRequest;
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

    public function acceptForMyTask(OriginalTaskRequest $request, int $send_task_id)
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

    public function shareTask(ShareTaskRequest $request){
        return $this->sendTaskInterface->shareTask($request);
    }

    public function sendDeclineTAsk(SendDeclineTaskRequest $request){
        return $this->sendTaskInterface->sendDeclineTAsk($request);
    }

    public function addMyTask(AddMyTaskRequest $request){
        return $this->sendTaskInterface->addMyTask($request);
    }
}
