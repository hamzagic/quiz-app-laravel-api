<?php

namespace App\Http\Controllers;

use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionAnswerController extends Controller
{
    public function addAnswerToQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|integer|exists:question,question_id',
            'answer_id' => 'required|integer|exists:answer,answer_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $question_id = $request->input('question_id');
        $answer_id = $request->input('answer_id');

        $data = array(
            'question_id' => $question_id,
            'answer_id' => $answer_id
        );

        $questionAnswer = new QuestionAnswer();
        try {
            $result = $questionAnswer->addAnswerToQuestion($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "Could not add answer to question"
            ], 400);
            return response()->json([
                "data" => 'Answer added successfully',
                "error" => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }
    }

    public function listAnswersFromQuestion($question_id)
    {
        $questionAnswer = new QuestionAnswer();
        $result = $questionAnswer->listAnswersFromQuestion($question_id);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function removeAnswerFromQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'answer_id' => 'required|integer|exists:answer,answer_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $answer_id = $request->input('answer_id');

        $data = array(
            'question_id' => $id,
            'answer_id' => $answer_id
        );

        $questionAnswer = new QuestionAnswer();


        try {
            $questionAnswer->removeAnswerFromQuestion($data);
            return response()->json([
                "data" => 'answer removed successfully',
                "error" => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }
    }

    public function listAll()
    {
        $questionAnswer = new QuestionAnswer();
        $result = $questionAnswer->list();

        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }
}
