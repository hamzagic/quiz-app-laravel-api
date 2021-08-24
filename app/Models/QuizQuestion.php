<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'quiz_id'
    ];

    public function addQuestionToQuiz($data)
    {
        $quiz_id = $data['quiz_id'];
        $question_id = $data['question_id'];

        $pairExists = $this->checkIfPairExists($quiz_id, $question_id);
        if ($pairExists > 0) throw new Exception('Question is already included on quiz');

        try {
            DB::table('quiz_question')->insert([
                'quiz_id' => $quiz_id,
                'question_id' => $question_id
            ]);
            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function removeQuestionFromQuiz($data)
    {
        $quizExists = $this->checkIfQuizExists($data['quiz_id']);

        if ($quizExists == 0) throw new Exception('Quiz not found');

        try {
            DB::table('quiz_question')
            ->where('quiz_id', '=', $data['quiz_id'])
            ->where('question_id', '=', $data['question_id'])
            ->delete();

            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function listQuestionsFromQuiz($quiz_id)
    {
        $quizExists = $this->checkIfQuizExists($quiz_id);

        if ($quizExists == 0) throw new Exception('Quiz not found');

        $list = DB::table('quiz_question')
        ->join('quiz', 'quiz.quiz_id', '=', 'quiz_question.quiz_id')
        ->join('question', 'question.question_id', '=', 'quiz_question.question_id')
        ->select('*')
        ->where('quiz.quiz_id', '=', $quiz_id)
        ->get();

        $result = array();
        foreach($list as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function checkIfQuizExists($id)
    {
        $quiz = DB::table('quiz')
        ->where('quiz_id', '=', $id)
        ->count();

        return $quiz;
    }

    public function checkIfPairExists($quiz_id, $question_id)
    {
        $result = DB::table('quiz_question')
        ->where('quiz_id', '=', $quiz_id)
        ->where('question_id', '=', $question_id)
        ->count();

        return $result;
    }

    // todo - format response
    public function list()
    {
        $list = DB::table('quiz_question')
        ->join('quiz', 'quiz.quiz_id', '=', 'quiz_question.quiz_id')
        ->join('question', 'question.question_id', '=', 'quiz_question.question_id')
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
            'quiz' => [
                'quiz_id' => $table->quiz_id,
                'quiz_name' => $table->quiz_name,
                'quiz_back_button' => $table->back_button,
                'quiz_questions_per_page' => $table->questions_per_page,
                'quiz_start_date' => $table->start_date,
                'quiz_end_date' => $table->end_date,
            ],
            'question' => [
                'question_id' => $table->question_id,
                'question_title' => $table->question_title,
                'question_alternatives_length' => $table->question_alternatives_length,
                'question_correct_answer_id' => $table->question_correct_answer_id,
                'question_active' => $table->question_active
            ]
        );

        return $response;
    }
}
