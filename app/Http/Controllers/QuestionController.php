<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255|String',
            'alternatives_length' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }

        $title = $request->input('title');
        $alternatives_length = $request->input('alternatives_length');

        $data = array(
            'title' => $title,
            'alternatives_length' => $alternatives_length
        );

        $question = new Question();
        try {
            $result = $question->create($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "could not create question"
            ], 400);

            return response()->json([
                "data" => $result,
                "error" => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }

    }

    public function list()
    {
        $question = new Question();
        $result = $question->list();

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
            ]);
        }

        $question = new Question();
        $result = $question->getByIdFull($id);

        if (!$result) {
            return response()->json([
                "data" => [],
                "error" => []
            ]);
        }

        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function updateQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'title' => 'required|min:3|max:255|String',
            'alternatives_length' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $title = $request->input('title');
        $alternatives_length = $request->input('alternatives_length');

        $data = array(
            'title' => $title,
            'alternatives_length' => $alternatives_length,
        );

        $question = new Question();
        $result = $question->updateQuestion($data, $id);

        if (!$result) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function updateStatus($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $question = new Question();
        $response = $question->updateStatus($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "question status updated successfully",
            "error" => []
        ]);
    }
}
