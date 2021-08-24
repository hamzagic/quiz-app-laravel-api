<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_id'
    ];

    public function addAnswerToQuestion($question_id, $answer_id)
    {

    }

    public function removeAnswerFromQuestion($question_id, $answer_id)
    {

    }

    public function listAnswersFromQuestion($question_id)
    {

    }
}
