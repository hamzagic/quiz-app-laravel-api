<?php

use App\Http\Controllers\ClassesController;
use Illuminate\Support\Facades\Route;

Route::post('/classes', [ClassesController::class, 'create']);

Route::get('/classes', [ClassesController::class, 'list']);

Route::get('/classes/{id}', [ClassesController::class, 'getById']);

Route::post('/classes/{id}', [ClassesController::class, 'updateClass']);

// Route::post('/classes/status/{id}', [ClassesController::class, 'updateStatus']); // todo
