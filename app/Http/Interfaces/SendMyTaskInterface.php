<?php

namespace App\Http\Interfaces;

use App\Http\Requests\AddMyTaskRequest;
use App\Http\Requests\SendDeclineTaskRequest;
use App\Http\Requests\ShareTaskRequest;

interface SendMyTaskInterface
{
    function addMyTask(AddMyTaskRequest $request);
    function sendDeclineTAsk(SendDeclineTaskRequest $request);
    function shareTask(ShareTaskRequest $request);
}
