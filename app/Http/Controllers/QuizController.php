<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function __construct(){}

    public function list()
    {
        $quiz = new Quiz();
        $result = $quiz->list();
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|String',
            'back_button' => 'required|Boolean',
            'questions_per_page' => 'required|Integer',
            'total_questions' => 'required|integer|min:1',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $name = $request->input('name');
        $back_button = $request->input('back_button');
        $questions_per_page = $request->input('questions_per_page');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $total_questions = $request->input('total_questions');

        $data = array(
            'name' => $name,
            'back_button' => $back_button,
            'questions_per_page' => $questions_per_page,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_questions' => $total_questions
        );

        $quiz = new Quiz();
        $result = $quiz->create($data);
        if (!$result) return response()->json([
            "data" => [],
            "error" => "Could not create quiz"
        ], 400);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function getById($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'Integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $quiz = new Quiz();

        $result = $quiz->getById($id);

        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    // todo - create and update in the same method
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|String',
            'back_button' => 'required|Boolean',
            'questions_per_page' => 'required|Integer',
            'total_questions' => 'required|integer|min:1',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $name = $request->input('name');
        $back_button = $request->input('back_button');
        $questions_per_page = $request->input('questions_per_page');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $total_questions = $request->input('total_questions');

        $quiz = new Quiz();

        $data = array(
            'name' => $name,
            'back_button' => $back_button,
            'questions_per_page' => $questions_per_page,
            'total_questions' => $total_questions,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'id' => $id
        );
        $result = $quiz->updateQuiz($data);
        if (!$result) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function publishQuiz($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'Integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $quiz = new Quiz();
        try {
            $response = $quiz->publishQuiz($id);

            if (!$response) return response()->json([
                "data" => [],
                "error" => "Id not found"
            ], 400);
            return response()->json([
                "data" => "quiz published successfully",
                "error" => []
            ]);
        } catch (Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }
    }

    public function unpublishQuiz($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'Integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $quiz = new Quiz();
        $response = $quiz->unpublishQuiz($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "quiz unpublished successfully",
            "error" => []
        ]);
    }
}
