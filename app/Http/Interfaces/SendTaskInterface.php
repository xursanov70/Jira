<?php

namespace App\Http\Interfaces;

use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\OriginalTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\SendTaskRequest;
use App\Http\Requests\ShareTaskRequest;
use Illuminate\Http\Request;

interface SendTaskInterface
{
    function createSendTask(SendTaskRequest $request);
    function updateSendTask(SendTaskRequest $request, int $send_task_id);
    function acceptForMyTask(OriginalTaskRequest $request, int $send_task_id);
    function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id);
    function forMeTasks();
    function mySendTasks();
    function addMyTask(AddMyTaskRequest $request);
    function sendDeclineTAsk(SendDeclineTaskRequest $request);
    function shareTask(ShareTaskRequest $request);
}
