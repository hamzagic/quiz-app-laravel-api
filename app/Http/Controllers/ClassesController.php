<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|String',
            'school_id' => 'required|integer|exists:school,school_id',
            'staff_id' => 'required|integer|exists:staff,staff_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $name = $request->input('name');
        $school_id = $request->input('school_id');
        $staff_id = $request->input('staff_id');

        $data = array(
            'name' => $name,
            'school_id' => $school_id,
            'staff_id' => $staff_id,
        );

        try {
            $class = new Classes();
            $result = $class->create($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "class name already exists"
            ], 400);

            return response()->json([
                "data" => $result,
                "error" => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "error" => $e->getMessage()
            ], 400);
        }
    }

    public function list()
    {
        $class = new Classes();
        $result = $class->list();

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

        $class = new Classes();
        $result = $class->getByIdFull($id);

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

    public function updateClass(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'Integer',
            'name' => 'required|min:3|String',
            'school_id' => 'required|integer|exists:school,school_id',
            'staff_id' => 'required|integer|exists:staff,staff_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $name = $request->input('name');
        $school_id = $request->input('school_id');
        $staff_id = $request->input('staff_id');

        $data = array(
            'name' => $name,
            'school_id' => $school_id,
            'staff_id' => $staff_id,
        );

        $class = new Classes();
        $result = $class->updateClass($data, $id);

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
