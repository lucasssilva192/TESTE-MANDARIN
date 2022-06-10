<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/task', [TaskController::class, 'add_task']);
Route::put('/task/{task}', [TaskController::class, 'edit_task']);
Route::patch('/task/{task}/status', [TaskController::class, 'update_status']);
Route::post('/task/{task}/tag', [TaskController::class, 'add_tag']);
Route::get('/task', [TaskController::class, 'get_tasks']);
Route::get('/task/{task}/file_url', [TaskController::class, 'get_file_url']);