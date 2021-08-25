<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuizResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'total_correct',
        'total_incorrect'
    ];

    public function create($data)
    {
        try {
            $result = DB::table('quiz_result')->insertGetId([
                'quiz_id' => $data['quiz_id'],
                'question_id' => $data['question_id']
            ]);
            $quizResult = $this->getById($result);
            return $quizResult;
        } catch(Exception $e) {
            return false;
        }
    }

    public function getById($id)
    {
        $response = DB::table('quiz_result')
        ->where('quiz_result_id', '=', $id)
        ->first();

        return $response;
    }

    public function listByStudentId($id)
    {
        $response = DB::table('quiz_result')
        ->where('student_id', '=', $id)
        ->get();

        return $response;
    }

    public function listByQuizId($id)
    {
        $response = DB::table('quiz_result')
        ->where('quiz_id', '=', $id)
        ->get();

        return $response;
    }

    // decide if this method will stay in this model
    public function getTotalCorrect($data)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('student_id', '=', $data['student_id'])
        ->where('is_correct', '=', true)
        ->count();

        return $response;
    }

    // decide if this method will stay in this model
    public function getTotalIncorrect($data)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('student_id', '=', $data['student_id'])
        ->where('is_correct', '=', false)
        ->count();

        return $response;
    }

    public function setTotalCorrect($data, $id)
    {
        $totalCorrect = $this->getTotalCorrect($data);

        DB::table('quiz_result')
        ->where('quiz_result_id', '=', $id)
        ->update([
            'total_correct' => $totalCorrect
        ]);
    }

    public function setTotalIncorrect($data, $id)
    {
        $totalIncorrect = $this->getTotalInorrect($data);

        DB::table('quiz_result')
        ->where('quiz_result_id', '=', $id)
        ->update([
            'total_correct' => $totalIncorrect
        ]);
    }
}
