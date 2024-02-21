<?php

namespace App\Http\Interfaces;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

interface TaskInterface
{
    function createTask(TaskRequest $request);
    function updateTask(Request $request, int $task_id);
    function endTask(int $task_id);
    function getMyTasks();
    function getTasks();
    function officialTasks();
    function myOfficialTasks();
    function personalTasks();
    function myPersonalTasks();
    function finishedTasks();
    function myFinishedTasks();
    function nowContinueTasks();
    function lateTasks();
    function filterTask();
}
