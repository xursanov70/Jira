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


    public function searchTask()
    {
        return $this->taskInterface->searchTask();
    }

    public function user(){
        return $this->taskInterface->user();
    }

    public function admin(){

        if ($this->can('task', 'get') == 'denied')
        return response()->json(["message" => "You are not allowed!"], 403);

        return $this->taskInterface->admin();
    }

}