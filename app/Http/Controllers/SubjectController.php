<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|String'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }

        $name = $request->input('name');

        $subject = new Subject();
        $result = $subject->create($name);

        if (!$result) return response()->json([
            "data" => [],
            "error" => "subject name already exists"
        ], 400);

        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }

    public function list()
    {
        $subject = new Subject();
        $result = $subject->list();

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

        $subject = new Subject();
        $result = $subject->getById($id);

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

    public function updateSubject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'Integer',
            'name' => 'required|min:3|String'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $name = $request->input('name');
        $subject = new Subject();
        $result = $subject->updateRole($name, $id);

        if (!$result) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => $result,
            "error" => []
        ]);
    }
}
