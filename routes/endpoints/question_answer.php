<?php

use App\Http\Controllers\QuestionAnswerController;
use Illuminate\Support\Facades\Route;

Route::post('/question_answer', [QuestionAnswerController::class, 'addAnswerToQuestion']);

Route::get('/question_answer/{id}', [QuestionAnswerController::class, 'listAnswersFromQuestion']);

Route::delete('/question_answer/{id}', [QuestionAnswerController::class, 'removeAnswerFromQuestion']);

Route::get('/question_answer', [QuestionAnswerController::class, 'listAll']);
