<?php

use App\Http\Controllers\QuizQuestionController;
use Illuminate\Support\Facades\Route;

Route::post('/quiz_question', [QuizQuestionController::class, 'addQuestionToQuiz']);

Route::get('/quiz_question/{id}', [QuizQuestionController::class, 'listQuestionsFromQuiz']);

Route::delete('/quiz_question/{id}', [QuizQuestionController::class, 'removeQuestionFromQuiz']);

Route::get('/quiz_question', [QuizQuestionController::class, 'listAll']);
