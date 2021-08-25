<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_id',
        'student_id',
        'student_answer_id',
        'is_correct',
        'done_date'
    ];

    public function create($data)
    {
        try {
            $response = DB::table('student_response')->insertGetId([
                'quiz_id' => $data['quiz_id'],
                'question_id' => $data['question_id'],
                'student_id' => $data['student_id'],
                'student_answer_id' => $data['student_answer_id'],
                'done_date' => $data['done_date']
            ]);
            $result = $this->getById($response);
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    public function getById($id)
    {
        $response = DB::table('student_response')
        ->where('student_response_id', '=', $id)
        ->first();

        return $response;
    }

    public function listByQuizId($id)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $id)
        ->get();

        return $response;
    }

    public function listByStudentId($id)
    {
        $response = DB::table('student_response')
        ->where('student_id', '=', $id)
        ->get();

        return $response;
    }

    public function listByQuizIdAndStudentId($data)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('student_id', '=', $data['student_id'])
        ->get();

        return $response;
    }

    public function checkIsCorrect($data)
    {
        $response = null;

        $correctAnswer = DB::table('question')
        ->where('question_id', '=', $data['question_id'])
        ->first();

        if ($correctAnswer->correct_answer_id == $data['student_answer_id']) {
            $response = DB::table('student_response')
            ->where('student_response_id', '=', $data['student_response_id'])
            ->update([
                'is_correct' => true
            ]);
        } else {
            $response = DB::table('student_response')
            ->where('student_response_id', '=', $data['student_response_id'])
            ->update([
                'is_correct' => false
            ]);
        }

        return $response;
    }

    public function getTotalCorrect($data)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('student_id', '=', $data['student_id'])
        ->where('is_correct', '=', true)
        ->count();

        return $response;
    }

    public function getTotalIncorrect($data)
    {
        $response = DB::table('student_response')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('student_id', '=', $data['student_id'])
        ->where('is_correct', '=', false)
        ->count();

        return $response;
    }
}
