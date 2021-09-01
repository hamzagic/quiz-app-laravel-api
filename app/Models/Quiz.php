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

    public function publishQuiz($id)
    {
        $quiz = $this->getByIdCount($id);
        if ($quiz == 0) return false;

        $correctAnswerSet = $this->checkCorrectAnswerSet($id);
        if ($correctAnswerSet != 1) throw new Exception("All questions must have exactly one correct answer");

        try {
            DB::table('quiz')
            ->where('quiz_id', '=', $id)
            ->update([
                'active' => 0
            ]);
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function unpublishQuiz($id)
    {
        $quiz = $this->getByIdCount($id);
        if ($quiz == 0) return false;

        try {
            DB::table('quiz')
            ->where('quiz_id', '=', $id)
            ->update([
                'active' => 0
            ]);
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteQuiz($id)
    {

    }

    public function checkCorrectAnswerSet($id)
    {
        $result = DB::table('question_answer')
        ->where('quiz_id', '=', $id)
        ->where('is_answer_correct', '=', 1)
        ->count();

        return $result;
    }

    public $timestamps = true;
}
