<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active'
    ];

    public function create($name)
    {
        try {
            $role = DB::table('role')->insertGetId([
                'name' => $name,
            ]);
            $result = $this->getById($role);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function list()
    {
        $roles = DB::table('role')->get();
        return $roles;
    }

    public function getById($id)
    {
        $role = DB::table('role')
        ->where('id', '=', $id)
        ->first();
        return $role;
    }

    public function getByIdCount($id)
    {
        $role = DB::table('role')
        ->where('id', '=', $id)
        ->count();

        return $role;
    }

    public function updateRole($name, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('role')
            ->where('id', '=', $id)
            ->update([
                'name' => $name,
            ]);
            $role = $this->getById($id);
            return $role;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }
}
