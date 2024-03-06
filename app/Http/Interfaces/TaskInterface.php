<?php

namespace App\Http\Interfaces;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

interface TaskInterface
{
    function createTask(TaskRequest $request);
    function updateTask(UpdateTaskRequest $request, int $task_id);
    function endTask(int $task_id);
    function searchTask();
    function user();
    function admin();
}
