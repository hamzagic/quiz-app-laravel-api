<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_first_name',
        'student_last_name',
        'student_email',
        'password',
        'student_active',
        'school_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    // todo encrypt password
    // todo create method to update password
    public function create($data)
    {
        $emailExists = $this->checkIfEmailExists(strtolower($data['email']));
        if ($emailExists > 0) throw new Exception('Email already exists');

        try {
            $student = DB::table('student')->insertGetId([
                'student_first_name' => $data['first_name'],
                'student_last_name' => $data['last_name'],
                'student_email' => strtolower($data['email']),
                'password' => $data['password'],
                'school_id' => $data['school_id'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($student);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function checkIfEmailExists($email)
    {
        $student = DB::table('student')
        ->where('student_email', '=', $email)
        ->count();

        return $student;
    }

    public function list()
    {
        $student = DB::table('student')
        ->join('school', 'school.school_id', '=', 'student.school_id')
        ->join('staff', 'staff.staff_id', '=', 'school.staff_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($student as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id) {
        $student = DB::table('student')
        ->where('student_id', '=', $id)
        ->first();

        return $student;
    }

    public function getByIdFull($id)
    {
        $student = DB::table('student')
        ->join('school', 'school.school_id', '=', 'student.school_id')
        ->join('staff', 'staff.staff_id', '=', 'school.staff_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->where('student.student_id', '=', $id)
        ->get();

        $response = $this->formatResponse($student[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $student = DB::table('student')
        ->where('student_id', '=', $id)
        ->count();

        return $student;
    }

    public function updateStudent($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('student')
            ->where('student_id', '=', $id)
            ->update([
                'student_first_name' => $data['first_name'],
                'student_last_name' => $data['last_name'],
                'student_email' => strtolower($data['email']),
                'school_id' => $data['school_id'],
                'updated_at' => Carbon::now()
            ]);
            $student = $this->getById($id);
            return $student;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }

    public function updateStatus($id)
    {
        $student = $this->getByIdCount($id);
        if ($student == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('student')
            ->where('student_id', '=', $id)
            ->update([
                'student_active' => !$active->student_active
            ]);
            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->student_id,
            'first_name' => $table->student_first_name,
            'last_name' => $table->student_last_name,
            'email' => $table->student_email,
            'school' => [
                'school_id' => $table->school_id,
                'school_name' => $table->school_name,
                'school_address' => $table->school_address,
                'school_active' => $table->school_active,
                'school_staff' => [
                    'staff_id' => $table->staff_id,
                    'staff_first_name' => $table->staff_first_name,
                    'staff_last_name' => $table->staff_last_name,
                    'staff_email' => $table->staff_email,
                    'staff_active' => $table->staff_active,
                    'staff_role' => [
                        'staff_role_id' => $table->role_id,
                        'staff_role_title' => $table->role_title
                    ]
                ],
            ],
            'active' => $table->student_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
