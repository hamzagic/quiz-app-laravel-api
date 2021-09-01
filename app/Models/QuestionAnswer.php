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
        'answer_id',
        'quiz_id',
        'is_answer_correct'
    ];

    public function addAnswerToQuestion($data)
    {
        $quiz_id = $data['quiz_id'];
        $question_id = $data['question_id'];
        $answer_id = $data['answer_id'];
        $is_answer_correct = $data['is_answer_correct'];

        $quizQuestionExists = $this->checkIfQuizQuestionExists($data['quiz_id'], $data['question_id']);

        // we will not add answer if the question is not already added to the quiz
        if ($quizQuestionExists == 0) throw new Exception('Question is not included on quiz yet');

        // we will not duplicate the same quiz/question/answer group
        // todo check if there's already an answer set as correct
        $groupExists = $this->checkIfGroupExists($quiz_id, $question_id, $answer_id);
        if ($groupExists > 0) throw new Exception('Answer is already included on question/quiz');

        try {
            DB::table('question_answer')->insert([
                'quiz_id' => $quiz_id,
                'question_id' => $question_id,
                'answer_id' => $answer_id,
                'is_answer_correct' => $is_answer_correct
            ]);
            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function removeAnswerFromQuestion($data)
    {
        $questionExists = $this->checkIfQuestionExists($data['question_id']);

        if ($questionExists == 0) throw new Exception('Question not found');

        $groupExists = $this->checkIfGroupExists($data['quiz_id'], $data['question_id'], $data['answer_id']);

        if ($groupExists == 0) throw new Exception('Group not found');

        try {
            DB::table('question_answer')
            ->where('quiz_id', '=', $data['quiz_id'])
            ->where('question_id', '=', $data['question_id'])
            ->where('answer_id', '=', $data['answer_id'])
            ->delete();

            return true;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function listAnswersFromQuestion($quiz_id, $question_id)
    {
        $questionExists = $this->checkIfQuestionExists($question_id);

        if ($questionExists == 0) throw new Exception('Question not found');

        $list = DB::table('question_answer')
        ->join('quiz', 'quiz.quiz_id', '=', 'question_answer.quiz_id')
        ->join('question', 'question.question_id', '=', 'question_answer.question_id')
        ->join('answer', 'answer.answer_id', '=', 'question_answer.answer_id')
        ->select('*')
        ->where('quiz.quiz_id', '=', $quiz_id)
        ->where('question.question_id', '=', $question_id)
        ->get();

        $result = array();
        foreach($list as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function setCorrectAnswer($data)
    {
        $quizQuestionExists = $this->checkIfQuizQuestionExists($data['quiz_id'], $data['question_id']);
        if ($quizQuestionExists == 0) throw new Exception('Question is not included on quiz yet');

        $groupExists = $this->checkIfGroupExists($data['quiz_id'], $data['question_id'], $data['answer_id']);
        if ($groupExists == 0) throw new Exception('Could not find answer in the quiz/question');

        $correctAnswerCount = $this->checkIfIsCorrectOnQuizQuestionExists($data['quiz_id'], $data['question_id']);

        if ($correctAnswerCount > 0) throw new Exception('There is already a correct answer - please verify');

        DB::table('question_answer')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('question_id', '=', $data['question_id'])
        ->where('answer_id', '=', $data['answer_id'])
        ->update([
            'is_answer_correct' => 1
        ]);
    }

    public function removeCorrectFromAnswer($data)
    {
        $quizQuestionExists = $this->checkIfQuizQuestionExists($data['quiz_id'], $data['question_id']);
        if ($quizQuestionExists == 0) throw new Exception('Question is not included on quiz yet');

        $groupExists = $this->checkIfGroupExists($data['quiz_id'], $data['question_id'], $data['answer_id']);
        if ($groupExists == 0) throw new Exception('Could not find answer in the quiz/question');

        DB::table('question_answer')
        ->where('quiz_id', '=', $data['quiz_id'])
        ->where('question_id', '=', $data['question_id'])
        ->where('answer_id', '=', $data['answer_id'])
        ->update([
            'is_answer_correct' => 0
        ]);
    }

    public function checkIfIsCorrectOnQuizQuestionExists($quiz_id, $question_id)
    {
        $result = DB::table('question_answer')
        ->where('quiz_id', '=', $quiz_id)
        ->where('question_id', '=', $question_id)
        ->where('is_answer_correct', '=', 1)
        ->count();

        return $result;
    }

    public function checkIfQuestionExists($id)
    {
        $question = DB::table('question')
        ->where('question_id', '=', $id)
        ->count();

        return $question;
    }

    public function checkIfQuizQuestionExists($quiz_id, $question_id)
    {
        $result = DB::table('quiz_question')
        ->where('quiz_id', '=', $quiz_id)
        ->where('question_id', '=', $question_id)
        ->count();

        return $result;
    }

    public function checkIfGroupExists($quiz_id, $question_id, $answer_id)
    {
        $result = DB::table('question_answer')
        ->where('quiz_id', '=', $quiz_id)
        ->where('question_id', '=', $question_id)
        ->where('answer_id', '=', $answer_id)
        ->count();

        return $result;
    }

    public function getByTableIds($quiz_id, $question_id, $answer_id)
    {
        $result = DB::table('question_answer')
        ->where('quiz_id', '=', $quiz_id)
        ->where('question_id', '=', $question_id)
        ->where('answer_id', '=', $answer_id)
        ->first();

        return $result;
    }

    public function list()
    {
        $list = DB::table('question_answer')
        ->join('quiz', 'quiz.quiz_id', '=', 'question_answer.quiz_id')
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
            'quiz' => [
               'quiz_id' => $table->quiz_id,
               'quiz_name' => $table->quiz_name,
               'back_button' => $table->back_button,
               'questions_per_page' => $table->questions_per_page,
               'total_questions' => $table->total_questions,
               'start_date'=> $table->start_date,
               'end_date'=> $table->end_date,
               'active' => $table->active
            ],
            'question' => [
                'question_id' => $table->question_id,
                'question_title' => $table->question_title,
                'question_alternatives_length' => $table->alternatives_length,
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
