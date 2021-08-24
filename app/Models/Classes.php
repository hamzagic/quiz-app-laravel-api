<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'class';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_name',
        'school_id',
        'staff_id'
    ];

    public function create($data)
    {
        $classNameExists = $this->checkIfclassNameExists($data['name']);
        if ($classNameExists > 0) throw new Exception('Class name already exists');

        try {
            $class = DB::table('class')->insertGetId([
                'class_name' => $data['name'],
                'school_id' => $data['school_id'],
                'staff_id' => $data['staff_id'],
                'created_at' => Carbon::now()
            ]);

            $result = $this->getById($class);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function checkIfClassNameExists($name)
    {
        $class = DB::table('class')
        ->where('class_name', '=', $name)
        ->count();

        return $class;
    }

    public function list()
    {
        $classes = DB::table('class')
        ->join('school', 'school.school_id', '=', 'class.school_id')
        ->join('staff', 'staff.staff_id', '=', 'class.staff_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($classes as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id)
    {
        $class = DB::table('class')
        ->where('class_id', '=', $id)
        ->first();
        return $class;
    }

    public function getByIdFull($id)
    {
        $class = DB::table('class')
        ->join('school', 'school.school_id', '=', 'class.school_id')
        ->join('staff', 'staff.staff_id', '=', 'class.staff_id')
        ->join('role', 'role.role_id', '=', 'staff.role_id')
        ->select('*')
        ->where('class.class_id', '=', $id)
        ->get();

        $response = $this->formatResponse($class[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $class = DB::table('class')
        ->where('class_id', '=', $id)
        ->count();

        return $class;
    }

    public function updateClass($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('class')
            ->where('class_id', '=', $id)
            ->update([
                'class_name' => $data['name'],
                'school_id' => $data['school_id'],
                'staff_id' => $data['staff_id'],
                'updated_at' => Carbon::now()
            ]);
            $class = $this->getById($id);
            return $class;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->class_id,
            'class_name' => $table->class_name,
            'school' => [
                'school_id' => $table->school_id,
                'school_name' => $table->school_name,
                'school_address' => $table->school_address,
                'school_active' => $table->school_active,
            ],
            'staff' => [
                'staff_id' => $table->staff_id,
                'staff_first_name' => $table->staff_first_name,
                'staff_last_name' => $table->staff_last_name,
                'staff_email' => $table->staff_email,
                'staff_active' => $table->staff_active,
                'role' => [
                    'role_id' => $table->role_id,
                    'role_title' => $table->role_title
                ]
                ],
            // 'subject' => [
            //     'subject_id' => $table->subject_id,
            //     'subject_name' => $table->subject_name,
            // ],
           // 'class_active' => $table->class_active, -> TODO
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
