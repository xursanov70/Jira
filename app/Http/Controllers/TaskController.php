<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\TaskInterface;
use App\Http\Requests\AddEndTaskRequest;
use App\Http\Requests\DeleteEndTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;


class TaskController extends Controller
{
    public function __construct(protected TaskInterface $taskInterface)
    {
    }

    function createTask(TaskRequest $request)
    {
        return $this->taskInterface->createTask($request);
    }

    function updateTask(UpdateTaskRequest $request, int $task_id)
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
        return response()->json(["message" => "Sizning huquqingiz yo'q!"], 403);

        return $this->taskInterface->admin();
    }

    public function addEndTask(AddEndTaskRequest $request){
        return $this->taskInterface->addEndTask($request);
    }

    public function deleteEndTask(DeleteEndTaskRequest $request){
        return $this->taskInterface->deleteEndTask($request);
    }
}