<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|min:3|max:255|String',
            'address' => 'required|min:3|max:255|String',
            'phone' => 'required|digits:10|Numeric',
            'staff_id' => 'integer|exists:staff,staff_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }

        $school_name = $request->input('school_name');
        $address = $request->input('address');
        $staff_id = $request->input('staff_id');

        $data = array(
            'school_name' => $school_name,
            'address' => $address,
            'staff_id' => $staff_id
        );

        $school = new School();
        // try {
        //     $result = $school->create($data);

        //     if (!$result) return response()->json([
        //         "data" => [],
        //         "error" => "could not create school"
        //     ], 400);

        //     return response()->json([
        //         "data" => $result,
        //         "error" => []
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         "data" => [],
        //         "error" => $e->getMessage()
        //     ]);
        // }

    }

    public function list()
    {
        $school = new School();
        $result = $school->list();

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

        $school = new School();
        $result = $school->getByIdFull($id);

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

    public function updateSchool(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'school_name' => 'required|min:3|max:255|String',
            'address' => 'required|min:3|max:255|String',
            'phone' => 'required|min:10|max:10|String',
            'staff_id' => 'integer|exists:staff,staff_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "data" => [],
                "error" => $validator->errors()->all()
            ]);
        }
        $school_name = $request->input('school_name');
        $address = $request->input('address');
        $staff_id = $request->input('staff_id');

        $data = array(
            'id' => $id,
            'school_name' => $school_name,
            'address' => $address,
            'staff_id' => $staff_id
        );
        $school = new School();
        $result = $school->updateSchool($data, $id);

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

        $school = new School();
        $response = $school->updateStatus($id);

        if (!$response) return response()->json([
            "data" => [],
            "error" => "Id not found"
        ], 400);
        return response()->json([
            "data" => "school status updated successfully",
            "error" => []
        ]);
    }
}
