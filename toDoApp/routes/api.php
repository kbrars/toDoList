<?php

use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

Route::get('/getTasks', [TaskListController::class, 'getTasks']);

Route::post('/updateTask', [TaskListController::class, 'updateTask']);

Route::post('/addTask', [TaskListController::class, 'addTask']);

Route::post('/deleteTask', [TaskListController::class, 'deleteTask']);
