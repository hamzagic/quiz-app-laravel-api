<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cors'])->group(function () {
    Route::post('/student', [StudentController::class, 'create']);

    Route::get('/student', [StudentController::class, 'list']);

    Route::get('/student/{id}', [StudentController::class, 'getById']);

    Route::post('/student/{id}', [StudentController::class, 'updateStudent']);

    Route::post('/student/status/{id}', [StudentController::class, 'updateStatus']);
});

