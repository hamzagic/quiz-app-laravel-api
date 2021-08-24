<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{
    public function addQuestionToQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quiz,quiz_id',
            'question_id' => 'required|integer|exists:question,question_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $quiz_id = $request->input('quiz_id');
        $question_id = $request->input('question_id');

        $data = array(
            'quiz_id' => $quiz_id,
            'question_id' => $question_id
        );

        $quizQuestion = new QuizQuestion();
        try {
            $result = $quizQuestion->addQuestionToQuiz($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "Could not add question to quiz"
            ], 400);
            return response()->json([
                "data" => 'Question added successfully',
                "error" => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }
    }

    public function listQuestionsFromQuiz($quiz_id)
    {
        $quizQuestion = new QuizQuestion();
        $result = $quizQuestion->listQuestionsFromQuiz($quiz_id);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function removeQuestionFromQuiz(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'question_id' => 'required|integer|exists:question,question_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $question_id = $request->input('question_id');

        $quizQuestion = new QuizQuestion();

        $data = array(
            'quiz_id' => $id,
            'question_id' => $question_id
        );

        $result = $quizQuestion->removeQuestionFromQuiz($data);

        return response()->json([
            "data" => 'question removed successfully',
            "error" => []
        ]);
    }

    public function listAll()
    {
        $quizQuestion = new QuizQuestion();
        $result = $quizQuestion->list();

        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }
}
