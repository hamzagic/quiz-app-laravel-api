<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'quiz_id'
    ];

    public function addQuestionToQuiz($quiz_id, $question_id)
    {

    }

    public function removeQuestionFromQuiz($quiz_id, $question_id)
    {

    }
}
