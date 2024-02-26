<?php

namespace App\Http\Interfaces;

use App\Http\Requests\DeleteForMyTaskRequest;
use App\Http\Requests\SendTaskRequest;
use Illuminate\Http\Request;

interface SendTaskInterface
{
    function createSendTask(SendTaskRequest $request);
    function updateSendTask(Request $request, int $send_task_id);
    function acceptForMyTask(int $send_task_id);
    function declineForMyTask(DeleteForMyTaskRequest $request, int $send_task_id);
    function forMeTasks();
    function mySendTasks();
}
