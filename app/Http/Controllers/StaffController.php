<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|max:255|String',
            'last_name' => 'required|min:3|max:255|String',
            'email' => 'required|email',
            'password' => 'required|min:5|string',
            'role_id' => 'required|integer|exists:role,role_id',
            'subject_id' => 'required|integer|exists:subject,subject_id'
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
        $role_id = $request->input('role_id');
        $subject_id = $request->input('subject_id');

        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'role_id' => $role_id,
            'subject_id' => $subject_id
        );

        $staff = new Staff();
        try {
            $result = $staff->create($data);

            if (!$result) return response()->json([
                "data" => [],
                "error" => "could not create staff user"
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
        $staff = new Staff();
        $result = $staff->list();

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

        $staff = new Staff();
        $result = $staff->getByIdFull($id);

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

    public function updateStaff(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'first_name' => 'required|min:3|max:255|String',
            'last_name' => 'required|min:3|max:255|String',
            'email' => 'required|email',
            'role_id' => 'required|integer|exists:role,role_id',
            'subject_id' => 'required|integer|exists:subject,subject_id'
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
        $role_id = $request->input('role_id');
        $subject_id = $request->input('subject_id');

        $data = array(
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'role_id' => $role_id,
            'subject_id' => $subject_id
        );
        $staff = new Staff();
        $result = $staff->updateRole($data, $id);

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

        $staff = new Staff();
        $response = $staff->updateStatus($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "staff status updated successfully",
            "error" => []
        ]);
    }
}
