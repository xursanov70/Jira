<?php

namespace App\Http\Interfaces;

use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\OriginalTaskRequest;
use App\Http\Requests\SendTaskRequest;

interface SendTaskInterface
{
    function createSendTask(SendTaskRequest $request);
    function updateSendTask(SendTaskRequest $request, int $send_task_id);
    function acceptForMyTask(OriginalTaskRequest $request, int $send_task_id);
    function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id);
    function forMeTasks();
    function mySendTasks();
}
