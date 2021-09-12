<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cors'])->group(function () {
    Route::post('/answer', [AnswerController::class, 'create']);

    Route::get('/answer', [AnswerController::class, 'list']);

    Route::get('/answer/{id}', [AnswerController::class, 'getById']);

    Route::post('/answer/{id}', [AnswerController::class, 'updateAnswer']);

    Route::post('/answer/status/{id}', [AnswerController::class, 'updateStatus']);
});

