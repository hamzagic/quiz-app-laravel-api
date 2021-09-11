<?php

use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cors'])->group(function () {
    Route::post('/school', [SchoolController::class, 'create']);

    Route::get('/school', [SchoolController::class, 'list']);

    Route::get('/school/{id}', [SchoolController::class, 'getById']);

    Route::post('/school/{id}', [SchoolController::class, 'updateSchool']);

    Route::post('/school/status/{id}', [SchoolController::class, 'updateStatus']);
});

