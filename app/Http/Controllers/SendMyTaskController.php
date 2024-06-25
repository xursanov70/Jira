<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\SendMyTaskInterface;
use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\ShareTaskRequest;

class SendMyTaskController extends Controller
{
    public function __construct(protected SendMyTaskInterface $sendMyTaskInterface)
    {
    }

    public function sendDeclineTask(SendDeclineTaskRequest $request){
        return $this->sendMyTaskInterface->sendDeclineTask($request);
    }

    public function addMyTask(AddMyTaskRequest $request){
        return $this->sendMyTaskInterface->addMyTask($request);
    }

    public function shareTask(ShareTaskRequest $request){
        return $this->sendMyTaskInterface->shareTask($request);
    }

}
