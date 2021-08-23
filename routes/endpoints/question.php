<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::post('/question', [QuestionController::class, 'create']);

Route::get('/question', [QuestionController::class, 'list']);

Route::get('/question/{id}', [QuestionController::class, 'getById']);

Route::post('/question/{id}', [QuestionController::class, 'updateQuestion']);

Route::post('/question/status/{id}', [QuestionController::class, 'updateStatus']);
