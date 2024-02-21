<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function __construct(protected TaskInterface $taskInterface)
    {
    }

    function createTask(TaskRequest $request)
    {
        return $this->taskInterface->createTask($request);
    }

    function updateTask(Request $request, int $task_id)
    {
        return $this->taskInterface->updateTask($request, $task_id);
    }

    function endTask(int $task_id)
    {
        return $this->taskInterface->endTask($task_id);
    }

    function getMyTasks()
    {
        return $this->taskInterface->getMyTasks();
    }

    function getTasks()
    {
        if ($this->can('task', 'get') == 'denied')
            return response()->json(["message" => "You are not allowed!"]);

        return $this->taskInterface->getTasks();
    }

    function officialTasks()
    {
        if ($this->can('task', 'get') == 'denied')
            return response()->json(["message" => "You are not allowed!"]);

        return $this->taskInterface->officialTasks();
    }

    function myOfficialTasks()
    {
        return $this->taskInterface->myOfficialTasks();
    }


    function personalTasks()
    {
        // if ($this->can('task', 'get') == 'denied')
        // return response()->json(["message" => "You are not allowed!"]);

        return $this->taskInterface->personalTasks();
    }

    function myPersonalTasks()
    {
        return $this->taskInterface->myPersonalTasks();
    }


    function finishedTasks()
    {
        // if ($this->can('task', 'get') == 'denied')
        // return response()->json(["message" => "You are not allowed!"]);

        return $this->taskInterface->finishedTasks();
    }

    function myFinishedTasks()
    {
        return $this->taskInterface->finishedTasks();
    }


    function nowContinueTasks()
    {
        // if ($this->can('task', 'get') == 'denied')
        // return response()->json(["message" => "You are not allowed!"]);

        return $this->taskInterface->nowContinueTasks();
    }

    public function filterTask()
    {
        return $this->taskInterface->filterTask();
    }
}
