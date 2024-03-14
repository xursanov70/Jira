<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SendMyTaskController;
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


Route::group(['prefix' => 'auth'], function () {
    Route::post('user/login', [RegisterController::class, 'userLogin']);
    
    Route::post('send/email', [RegisterController::class, 'sendEmail']);
    Route::post('confirm/code', [RegisterController::class, 'confirmCode']);
    Route::post('user/register', [RegisterController::class, 'userRegister']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('get/users', [RegisterController::class, 'getUsers']);
    Route::get('user/auth', [RegisterController::class, 'authUser']);
    Route::get('user/search', [RegisterController::class, 'searchUser']);
    Route::put('change/send/email', [RegisterController::class, 'changeSendEmail']);
    
    Route::post('create/task', [TaskController::class, 'createTask']);
    Route::post('update/task/{task_id}', [TaskController::class, 'updateTask']);
    Route::put('end/task/{task_id}', [TaskController::class, 'endTask']);  //taskni tugatish
    
    Route::get('for/admin', [TaskController::class, 'forAdmin']);        //admin uchun         
    Route::get('for/user', [TaskController::class, 'forUser']);          //user uchun
    Route::get('search/task', [TaskController::class, 'searchTask']);          
    Route::get('history/task', [TaskController::class, 'historyTask']); 

    Route::get('finish/task', [TaskController::class, 'finishTasks']);          
    Route::get('continue/task', [TaskController::class, 'continueTasks']);          
    Route::get('late/task', [TaskController::class, 'lateTasks']);      
    Route::post('add/end/task', [TaskController::class, 'addEndTask']);  //end taskni oziga qoshish
    Route::delete('delete/end/task', [TaskController::class, 'deleteEndTask']);  //end taskni oziga qoshish
        
    Route::get('user', [TaskController::class, 'user']);          
    Route::get('admin', [TaskController::class, 'admin']);          
    
    Route::post('create/send/task', [SendTaskController::class, 'createSendTask']);
    Route::post('update/send/task/{send_task_id}', [SendTaskController::class, 'updateSendTask']);
    Route::put('accept/for/my/task/{send_task_id}', [SendTaskController::class, 'acceptForMyTask']);   //taskni qabul qilish
    Route::put('decline/for/my/task/{send_task_id}', [SendTaskController::class, 'declineForMyTask']);  //qabul qmaslik
    Route::get('for/me/tasks', [SendTaskController::class, 'forMeTasks']);  //menga kegan tasklar
    Route::get('my/sand/tasks', [SendTaskController::class, 'mySendTasks']);  //men jonatgan tasklar
    
    Route::post('send/decline/task', [SendMyTaskController::class, 'sendDeclineTAsk']);  //decline taskni bowqaga jonatw
    Route::post('share/task', [SendMyTaskController::class, 'shareTask']);  //mavjud taskni jonatiw
    Route::post('add/my/task', [SendMyTaskController::class, 'addMyTask']);  //decline taskni oziga qoshish
});
