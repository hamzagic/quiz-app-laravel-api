<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    protected $primaryKey = 'school_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_name',
        'school_address',
        'school_active',
        'staff_id'
    ];

    public function create($data)
    {
        $nameExists = $this->checkIfNameExists($data['school_name']);
        if ($nameExists > 0) throw new Exception('School already exists');

        try {
            $school = DB::table('school')->insertGetId([
                'school_name' => $data['school_name'],
                'school_address' => $data['address'],
                'staff_id' => $data['staff_id'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($school);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function checkIfNameExists($name)
    {
        $school = DB::table('school')
        ->where('school_name', '=', $name)
        ->count();

        return $school;
    }

    public function list()
    {
        $school = DB::table('school')
        // ->join('staff', 'staff.staff_id', '=', 'school.staff_id')
        // ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($school as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id) {
        $school = DB::table('school')
        ->where('school.school_id', '=', $id)
        ->first();

        return $school;
    }

    public function getByIdFull($id)
    {
        $school = DB::table('school')
        ->join('staff', 'staff.staff_id', '=', 'school.staff_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->where('school.school_id', '=', $id)
        ->get();

        $response = $this->formatResponse($school[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $school = DB::table('school')
        ->where('school_id', '=', $id)
        ->count();

        return $school;
    }

    public function updateSchool($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('school')
            ->where('school_id', '=', $id)
            ->update([
                'school_name' => $data['school_name'],
                'school_address' => $data['address'],
                'staff_id' => $data['staff_id'],
                'updated_at' => Carbon::now()
            ]);
            $school = $this->getById($id);
            return $school;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }

    public function updateStatus($id)
    {
        $school = $this->getByIdCount($id);
        if ($school == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('school')
            ->where('school_id', '=', $id)
            ->update([
                'school_active' => !$active->school_active
            ]);
            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->school_id,
            'name' => $table->school_name,
            'address' => $table->school_address,
            // 'staff' => [
            //     'staff_id' => $table->staff_id,
            //     'staff_first_name' => $table->staff_first_name,
            //     'staff_last_name' => $table->staff_last_name,
            //     'staff_email' => $table->staff_email,
            //     'staff_active' => $table->staff_active,
            //     'role' => [
            //         'staff_role_id' => $table->role_id,
            //         'staff_role_title' => $table->role_title
            //     ],
            //     'staff_created_at' => $table->created_at,
            //     'staff_updated_at' => $table->updated_at
            // ],
            'active' => $table->school_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
