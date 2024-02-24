<?php

namespace App\Http\Interfaces;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

interface TaskInterface
{
    function createTask(TaskRequest $request);
    function updateTask(Request $request, int $task_id);
    function endTask(int $task_id);
    function forAdmin();
    function forUser();
    function searchTask();
    function historyTask();
    function finishTasks();
    function continueTasks();
    function lateTasks();
    function user();
    function admin();
}
