<?php

namespace App\Http\Interfaces;

use App\Http\Requests\AddEndTaskRequest;
use App\Http\Requests\DeleteEndTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;

interface TaskInterface
{
    function createTask(TaskRequest $request);
    function updateTask(UpdateTaskRequest $request, int $task_id);
    function endTask(int $task_id);
    function searchTask();
    function user();
    function admin();
    function addEndTask(AddEndTaskRequest $request);
    function deleteEndTask(DeleteEndTaskRequest $request);
}
