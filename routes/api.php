<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    Route::get   ('/tasks',        [TaskController::class,'index']);
    Route::post  ('/tasks',        [TaskController::class,'store']);
    Route::put   ('/tasks/{task}', [TaskController::class,'update']);
    Route::delete('/tasks/{task}', [TaskController::class,'destroy']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');