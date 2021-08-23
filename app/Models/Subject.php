<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_name',
        'subject_active',
    ];

    public function create($name)
    {
        try {
            $subject = DB::table('subject')->insertGetId([
                'subject_name' => $name,
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($subject);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function list()
    {
        $subject = DB::table('subject')->get();
        return $subject;
    }

    public function getById($id)
    {
        $subject = DB::table('subject')
        ->where('id', '=', $id)
        ->first();
        return $subject;
    }

    public function getByIdCount($id)
    {
        $subject = DB::table('subject')
        ->where('id', '=', $id)
        ->count();

        return $subject;
    }

    public function updateSubject($name, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('subject')
            ->where('id', '=', $id)
            ->update([
                'subject_name' => $name,
                'updated_at' => Carbon::now()
            ]);
            $subject = $this->getById($id);
            return $subject;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }
}
