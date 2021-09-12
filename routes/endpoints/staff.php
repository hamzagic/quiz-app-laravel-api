<?php

use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cors'])->group(function () {
    Route::post('/staff', [StaffController::class, 'create']);

    Route::get('/staff', [StaffController::class, 'list']);

    Route::get('/staff/{id}', [StaffController::class, 'getById']);

    Route::post('/staff/{id}', [StaffController::class, 'updateStaff']);

    Route::post('/staff/status/{id}', [StaffController::class, 'updateStatus']);
});
