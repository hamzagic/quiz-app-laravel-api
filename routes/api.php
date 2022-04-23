<?php

use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require_once base_path('routes/endpoints/role.php');
require_once base_path('routes/endpoints/subject.php');
require_once base_path('routes/endpoints/staff.php');
require_once base_path('routes/endpoints/quiz.php');
require_once base_path('routes/endpoints/school.php');
require_once base_path('routes/endpoints/student.php');
require_once base_path('routes/endpoints/classes.php');
require_once base_path('routes/endpoints/question.php');
require_once base_path('routes/endpoints/answer.php');
require_once base_path('routes/endpoints/quiz_question.php');
require_once base_path('routes/endpoints/question_answer.php');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('cors:api')->get('/', function () {
    return 'Hello Quiz';
});
