<?php

namespace App\Models;

use Exception;
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

    public function addAnswerToQuestion($data)
    {
        $question_id = $data['question_id'];
        $answer_id = $data['answer_id'];

        $pairExists = $this->checkIfPairExists($question_id, $answer_id);
        if ($pairExists > 0) throw new Exception('Answer is already included on question');

        try {
            DB::table('question_answer')->insert([
                'question_id' => $question_id,
                'answer_id' => $answer_id,
            ]);
            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    // todo - validate if pair exists before attempting to delete
    public function removeAnswerFromQuestion($data)
    {
        $questionExists = $this->checkIfQuestionExists($data['question_id']);

        if ($questionExists == 0) throw new Exception('Question not found');

        $pairExists = $this->checkIfPairExists($data['question_id'], $data['answer_id']);

        if ($pairExists == 0) throw new Exception('Pair not found');

        try {
            DB::table('question_answer')
            ->where('question_id', '=', $data['question_id'])
            ->where('answer_id', '=', $data['answer_id'])
            ->delete();

            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function listAnswersFromQuestion($question_id)
    {
        $questionExists = $this->checkIfQuestionExists($question_id);

        if ($questionExists == 0) throw new Exception('Question not found');

        $list = DB::table('question_answer')
        ->join('question', 'question.question_id', '=', 'question_answer.question_id')
        ->join('answer', 'answer.answer_id', '=', 'question_answer.answer_id')
        ->select('*')
        ->where('question.question_id', '=', $question_id)
        ->get();

        $result = array();
        foreach($list as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function checkIfQuestionExists($id)
    {
        $question = DB::table('question')
        ->where('question_id', '=', $id)
        ->count();

        return $question;
    }

    public function checkIfPairExists($question_id, $answer_id)
    {
        $result = DB::table('question_answer')
        ->where('question_id', '=', $question_id)
        ->where('answer_id', '=', $answer_id)
        ->count();

        return $result;
    }

    public function list()
    {
        $list = DB::table('question_answer')
        ->join('question', 'question.question_id', '=', 'question_answer.question_id')
        ->join('answer', 'answer.answer_id', '=', 'question_answer.answer_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($list as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->id,
            'question' => [
                'question_id' => $table->question_id,
                'question_title' => $table->question_title,
                'question_alternatives_length' => $table->question_alternatives_length,
                'question_correct_answer_id' => $table->question_correct_answer_id,
                'question_active' => $table->question_active
            ],
            'answer' => [
                'answer_id' => $table->answer_id,
                'answer_content' => $table->answer_content,
                'answer_active' => $table->answer_active
            ]
        );

        return $response;
    }
}
