<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|max:255|String',
            'last_name' => 'required|min:3|max:255|String',
            'email' => 'required|email',
            'password' => 'required|min:5|string',
            'school_id' => 'required|integer|exists:school,school_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ], 400);
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $school_id = $request->input('school_id');

        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'school_id' => $school_id
        );

        $student = new Student();
        try {
            $result = $student->create($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "could not create student"
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
        $student = new Student();
        $result = $student->list();

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

        $student = new Student();
        $result = $student->getByIdFull($id);

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

    public function updateStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'first_name' => 'required|min:3|max:255|String',
            'last_name' => 'required|min:3|max:255|String',
            'email' => 'required|email',
            'school_id' => 'required|integer|exists:school,school_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $school_id = $request->input('subject_id');

        $data = array(
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'school_id' => $school_id
        );
        $student = new Student();
        $result = $student->updateRole($data, $id);

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

        $student = new Student();
        $response = $student->updateStatus($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "student status updated successfully",
            "error" => []
        ]);
    }
}
