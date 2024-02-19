<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RegisterController;
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

Route::post('send/message', [RegisterController::class, 'sendMessage']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('user/register', [RegisterController::class, 'userRegister']);
    Route::post('test', [RegisterController::class, 'test']);

    Route::post('confirm/code', [RegisterController::class, 'confirmCode']);
    Route::post('user/login', [RegisterController::class, 'userLogin']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('get/users', [RegisterController::class, 'getUsers']);
    Route::get('user/auth', [RegisterController::class, 'authUser']);
    Route::get('user/filter', [RegisterController::class, 'filterUser']);

    Route::post('create/category', [CategoryController::class, 'createCategory']);
    Route::put('update/category/{category_id}', [CategoryController::class, 'updateCategory']);
    Route::get('get/category', [CategoryController::class, 'getCategory']);

    Route::post('create/task', [TaskController::class, 'createTask']);
    Route::put('update/task/{task_id}', [TaskController::class, 'updateTask']);
    Route::put('end/task/{task_id}', [TaskController::class, 'endTask']);  //taskni tugatish
    Route::get('get/my/tasks', [TaskController::class, 'getMyTasks']);     //mening tasklarim
    Route::get('get/tasks', [TaskController::class, 'getTasks']);     //hamma tasklar faqat admin koradi
    Route::get('official/tasks', [TaskController::class, 'officialTasks']);     //ishonch uchun tasklar
    Route::get('personal/tasks', [TaskController::class, 'personalTasks']);     //personal  tasklar
    Route::get('finished/tasks', [TaskController::class, 'finishedTasks']);     //tugagan  tasklar admin koradi
    Route::get('now/continue/tasks', [TaskController::class, 'nowContinueTasks']);     //hali tugamagan  tasklar admin koradi
    Route::get('filter/task', [TaskController::class, 'filterTask']);

    Route::post('create/comment', [CommentController::class, 'createComment']);
    Route::put('update/comment/{comment_id}', [CommentController::class, 'updateComment']);
    Route::get('get/comment', [CommentController::class, 'getComments']);   //menga kegan commentlar
    Route::get('get/my/comment', [CommentController::class, 'getMyComments']);   //men jonatgan  commentlar
    
});