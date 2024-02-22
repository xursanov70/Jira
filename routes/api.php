<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SendTaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    
    Route::post('user/login', [RegisterController::class, 'userLogin']);
    Route::post('send/email', [RegisterController::class, 'sendEmail']);
    Route::post('confirm/code', [RegisterController::class, 'confirmCode']);
    Route::post('user/register', [RegisterController::class, 'userRegister']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('get/users', [RegisterController::class, 'getUsers']);
    Route::get('user/auth', [RegisterController::class, 'authUser']);
    Route::get('user/filter', [RegisterController::class, 'filterUser']);



    Route::post('create/task', [TaskController::class, 'createTask']);
    Route::post('update/task/{task_id}', [TaskController::class, 'updateTask']);
    Route::put('end/task/{task_id}', [TaskController::class, 'endTask']);  //taskni tugatish

    Route::get('for/admin', [TaskController::class, 'forAdmin']);        //admin uchun         
    Route::get('for/user', [TaskController::class, 'forUser']);          //user uchun

    Route::post('create/send/task', [SendTaskController::class, 'createSendTask']);
    Route::post('update/send/task/{send_task_id}', [SendTaskController::class, 'updateSendTask']);
    Route::put('accept/for/my/task/{send_task_id}', [SendTaskController::class, 'acceptForMyTask']);   //taskni qabul qilish
    Route::put('decline/for/my/task/{send_task_id}', [SendTaskController::class, 'declineForMyTask']);  //qabul qmaslik
    Route::get('for/me/tasks', [SendTaskController::class, 'forMeTasks']);  //qabul qlingan tasklar

});
