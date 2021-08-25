<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'quiz_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_name',
        'back_button',
        'questions_per_page',
        'start_date',
        'end_date',
        'total_questions',
        'active',
        'created_at',
        'updated_at'
    ];

    public function create($data)
    {
        try {
            $quiz = DB::table('quiz')->insertGetId([
                'quiz_name' => $data['name'],
                'back_button' => $data['back_button'],
                'questions_per_page' => $data['questions_per_page'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_questions' => $data['total_questions'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($quiz);
            return $result;
        } catch(\Exception $e) {
           return $e->getMessage();
           // return false;
        }
    }

    public function getById($id)
    {
        $quiz = DB::table('quiz')->where('quiz_id', $id)->first();
        return $quiz;
    }

    public function getByIdCount($id)
    {
        $quiz = DB::table('quiz')->where('quiz_id', $id)->count();
        return $quiz;
    }

    public function list()
    {
        $quiz = DB::table('quiz')->get();
        return $quiz;
    }

    public function updateQuiz($data)
    {
        $result = $this->getById($data['id']);
        if ($result) {
            try {
                DB::table('quiz')
                ->where('quiz_id', '=', $data['id'])
                ->update([
                    'quiz_name' => $data['name'],
                    'back_button' => $data['back_button'],
                    'questions_per_page' => $data['questions_per_page'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'total_questions' => $data['total_questions'],
                    'updated_at' => Carbon::now(),
                    'created_at' => $result->created_at
                ]);
                $result2 = $this->getById($data['id']);
                return $result2;
            } catch(\Exception $e) {
               return $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function deleteQuiz($id)
    {
        $quiz = $this->getByIdCount($id);
        if ($quiz == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('quiz')
            ->where('quiz_id', '=', $id)
            ->update([
                'active' => !$active->active
            ]);
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public $timestamps = true;
}
