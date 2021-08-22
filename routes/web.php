<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once '../routes/endpoints/role.php';

Route::post('/quiz', [QuizController::class, 'create']);

Route::get('/quiz', [QuizController::class, 'list']);

Route::get('/quiz/{id}', [QuizController::class, 'getById']);

Route::post('/quiz/{id}', [QuizController::class, 'update']);

Route::post('/quiz/status/{id}', [QuizController::class, 'updateActiveStatus']);

Route::get('/', function () {
    return 'Quiz api';
});
