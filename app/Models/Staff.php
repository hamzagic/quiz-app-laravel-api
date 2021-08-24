<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staff_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_first_name',
        'staff_last_name',
        'staff_email',
        'password',
        'staff_active',
        'subject_id'
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
        $emailExists = $this->checkIfEmailExists($data['email']);
        if ($emailExists > 0) throw new Exception('Email already exists');

        try {
            $staff = DB::table('staff')->insertGetId([
                'staff_first_name' => $data['first_name'],
                'staff_last_name' => $data['last_name'],
                'staff_email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $data['role_id'],
                'subject_id' => $data['subject_id'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($staff);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function checkIfEmailExists($email)
    {
        $staff = DB::table('staff')
        ->where('staff_email', '=', $email)
        ->count();

        return $staff;
    }

    public function list()
    {
        $staff = DB::table('staff')
        ->join('subject', 'subject.subject_id', '=', 'staff.subject_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($staff as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id) {
        $staff = DB::table('staff')
        ->where('staff_id', '=', $id)
        ->first();

        return $staff;
    }

    public function getByIdFull($id)
    {
        $staff = DB::table('staff')
        ->join('subject', 'subject.subject_id', '=', 'staff.subject_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->where('staff.staff_id', '=', $id)
        ->get();

        $response = $this->formatResponse($staff[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $staff = DB::table('staff')
        ->where('staff_id', '=', $id)
        ->count();

        return $staff;
    }

    public function updateStaff($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('staff')
            ->where('staff_id', '=', $id)
            ->update([
                'staff_first_name' => $data['first_name'],
                'staff_last_name' => $data['last_name'],
                'staff_email' => $data['email'],
                'staff_role_id' => $data['role_id'],
                'staff_subject_id' => $data['subject_id'],
                'updated_at' => Carbon::now()
            ]);
            $staff = $this->getById($id);
            return $staff;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }

    public function updateStatus($id)
    {
        $staff = $this->getByIdCount($id);
        if ($staff == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('staff')
            ->where('staff_id', '=', $id)
            ->update([
                'staff_active' => !$active->staff_active
            ]);
            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->staff_id,
            'first_name' => $table->staff_first_name,
            'last_name' => $table->staff_last_name,
            'email' => $table->staff_email,
            'role' => [
                'role_id' => $table->role_id,
                'role_title' => $table->role_title
            ],
            'subject' => [
                'subject_id' => $table->subject_id,
                'subject_name' => $table->subject_name,
            ],
            'active' => $table->staff_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
