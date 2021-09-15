<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:3|max:255|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $content = $request->input('content');

        $data = array(
            'content' => $content
        );

        $answer = new Answer();
        try {
            $result = $answer->create($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "could not create answer"
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
        $answer = new Answer();
        $result = $answer->list();

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

        $answer = new Answer();
        $result = $answer->getById($id);

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

    public function updateAnswer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'content' => 'required|min:3|max:255|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $content = $request->input('content');

        $data = array(
            'content' => $content
        );

        $answer = new Answer();

        $result = $answer->updateAnswer($data, $id);

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

        $answer = new Answer();
        $response = $answer->updateStatus($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "answer status updated successfully",
            "error" => []
        ]);
    }
}
