<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function __construct(){}

    // todo: create a format response class

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

        $quiz = new Quiz();
        $result = $quiz->create($name, $back_button, $questions_per_page, $start_date, $end_date);
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

        $quiz = new Quiz();

        $data = array(
            'name' => $name,
            'back_button' => $back_button,
            'questions_per_page' => $questions_per_page,
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

    public function updateActiveStatus($id)
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
        $response = $quiz->deleteQuiz($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "quiz status updated successfully",
            "error" => []
        ]);
    }
}
