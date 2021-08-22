<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/role', [RoleController::class, 'create']);

Route::get('/role', [RoleController::class, 'list']);

Route::get('/role/{id}', [RoleController::class, 'getById']);

Route::post('/role/{id}', [RoleController::class, 'updateRole']);

