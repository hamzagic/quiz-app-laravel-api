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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'active',
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
    public function create($data)
    {
        $emailExists = $this->checkIfEmailExists($data['email']);
        if ($emailExists > 0) throw new Exception('Email already exists');

        try {
            $staff = DB::table('staff')->insertGetId([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
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
        ->where('email', '=', $email)
        ->count();

        return $staff;
    }

    public function list()
    {
        $staff = DB::table('staff')
        ->join('subject', 'subject.id', '=', 'staff.subject_id')
        ->join('role', 'role.id', '=', 'staff.role_id')
        ->select('*')
        ->get();

        $response = $this->formatResponse($staff[0]);

        return $response;
    }

    public function getById($id) {
        $staff = DB::table('staff')
        ->where('staff.id', '=', $id)
        ->first();

        return $staff;
    }

    public function getByIdFull($id)
    {
        $staff = DB::table('staff')
        ->join('subject', 'subject.id', '=', 'staff.subject_id')
        ->join('role', 'role.id', '=', 'staff.role_id')
        ->select('*')
        ->where('staff.id', '=', $id)
        ->get();

        $response = $this->formatResponse($staff[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $staff = DB::table('staff')
        ->where('id', '=', $id)
        ->count();

        return $staff;
    }

    public function updateStaff($name, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('staff')
            ->where('id', '=', $id)
            ->update([
                'name' => $name,
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
            ->where('id', '=', $id)
            ->update([
                'active' => !$active->active
            ]);
            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->id,
            'first_name' => $table->first_name,
            'last_name' => $table->last_name,
            'email' => $table->email,
            'role' => [
                'role_id' => $table->role_id,
                'role_title' => $table->title
            ],
            'subject' => [
                'subject_id' => $table->subject_id,
                'subject_name' => $table->name,
            ],
            'active' => $table->active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
