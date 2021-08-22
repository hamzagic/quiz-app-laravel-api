<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::post('/subject', [SubjectController::class, 'create']);

Route::get('/subject', [SubjectController::class, 'list']);

Route::get('/subject/{id}', [SubjectController::class, 'getById']);

Route::post('/subject/{id}', [SubjectController::class, 'updateSubject']);
