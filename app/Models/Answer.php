<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    use HasFactory;

    protected $primaryKey = 'answer_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer_content',
        'answer_active'
    ];

    public function create($data)
    {
        try {
            $answer = DB::table('answer')->insertGetId([
                'answer_content' => $data['content'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($answer);
            return $result;
        } catch(\Exception $e) {
           return false;
        }
    }

    public function list()
    {
        $answers = DB::table('answer')->get();
        return $answers;
    }

    public function getById($id)
    {
        $answer = DB::table('answer')
        ->where('answer_id', '=', $id)
        ->first();
        return $answer;
    }

    public function getByIdCount($id)
    {
        $answer = DB::table('answer')
        ->where('answer_id', '=', $id)
        ->count();

        return $answer;
    }

    public function updateAnswer($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('answer')
            ->where('answer_id', '=', $id)
            ->update([
                'answer_content' => $data['content'],
                'updated_at' => Carbon::now()
            ]);
            $answer = $this->getById($id);
            return $answer;
        } catch(\Exception $e) {
           return $e->getMessage();
        }
    }

    public function updateStatus($id)
    {
        $answer = $this->getByIdCount($id);
        if ($answer == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('answer')
            ->where('answer_id', '=', $id)
            ->update([
                'answer_active' => !$active->answer_active
            ]);
            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
