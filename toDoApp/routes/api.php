<?php

use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskListController::class);