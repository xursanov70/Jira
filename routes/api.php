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
    Route::get('get/my/tasks', [TaskController::class, 'getMyTasks']);     //mening tasklarim
    Route::get('get/tasks', [TaskController::class, 'getTasks']);     //hamma tasklar faqat admin koradi
    Route::get('official/tasks', [TaskController::class, 'officialTasks']);     //official uchun tasklar admin uchun
    Route::get('personal/tasks', [TaskController::class, 'personalTasks']);     //personal  tasklar  admin uchun
    Route::get('finished/tasks', [TaskController::class, 'finishedTasks']);     //tugagan tasklar admin uchun
    Route::get('my/official/tasks', [TaskController::class, 'myOfficialTasks']);     //official tasklarim
    Route::get('my/personal/tasks', [TaskController::class, 'myPersonalTasks']);     //personal tasklarim
    Route::get('my/finished/tasks', [TaskController::class, 'myFinishedTasks']);         //tugagan tasklarim
    Route::get('now/continue/tasks', [TaskController::class, 'nowContinueTasks']);     //hali tugamagan  tasklar admin uchun
    Route::get('filter/task', [TaskController::class, 'filterTask']);

    Route::post('create/send/task', [SendTaskController::class, 'createSendTask']);
    Route::post('update/send/task/{send_task_id}', [SendTaskController::class, 'updateSendTask']);
    Route::get('get/for/my/task', [SendTaskController::class, 'getForMyTask']);
    Route::put('accept/for/my/task/{send_task_id}', [SendTaskController::class, 'acceptForMyTask']);   //taskni qabul qilish
    Route::put('no/accept/for/my/task/{send_task_id}', [SendTaskController::class, 'noAcceptForMyTask']);  //qabul qmaslik
    Route::get('accept/tasks', [SendTaskController::class, 'acceptTasks']);  //qabul qlingan tasklar
    Route::get('no/accept/tasks', [SendTaskController::class, 'noAcceptTasks']);  //qabul qlinmagan tasklar

});
