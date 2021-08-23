<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::post('/quiz', [QuizController::class, 'create']);

Route::get('/quiz', [QuizController::class, 'list']);

Route::get('/quiz/{id}', [QuizController::class, 'getById']);

Route::post('/quiz/{id}', [QuizController::class, 'update']);

Route::post('/quiz/status/{id}', [QuizController::class, 'updateActiveStatus']);

