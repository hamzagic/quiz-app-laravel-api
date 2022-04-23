<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'question_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_title',
        'alternatives_length',
        'question_active',
        'quiz_id'
    ];

    public function create($data)
    {
        try {
            $question = DB::table('question')->insertGetId([
                'quiz_id' => $data['quiz_id'],
                'question_title' => $data['title'],
                'alternatives_length' => $data['alternatives_length'],
                'created_at' => Carbon::now()
            ]);
            $result = $this->getById($question);
            return $result;
        } catch(Exception $e) {
           return false;
        }
    }

    public function list()
    {
        $question = DB::table('question')
        ->join('quiz', 'quiz.quiz_id', '=', 'question.quiz_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($question as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id) {
        $question = DB::table('question')
        ->join('quiz', 'quiz.quiz_id', '=', 'question.quiz_id')
        ->where('question_id', '=', $id)
        ->first();

        return $question;
    }

    public function getByIdFull($id)
    {
        $question = DB::table('question')
        ->join('quiz', 'quiz.quiz_id', '=', 'question.quiz_id')
        ->select('*')
        ->where('question.question_id', '=', $id)
        ->get();

        $response = $this->formatResponse($question[0]);

        return $response;
    }

    public function getByIdCount($id)
    {
        $question = DB::table('question')
        ->where('question_id', '=', $id)
        ->count();

        return $question;
    }

    public function updateQuestion($data, $id)
    {
        $result = $this->getByIdCount($id);
        if ($result == 0) return false;

        try {
            DB::table('question')
            ->where('question_id', '=', $id)
            ->update([
                'question_title' => $data['title'],
                'alternatives_length' => $data['alternatives_length'],
                'updated_at' => Carbon::now()
            ]);
            $question = $this->getById($id);
            return $question;
        } catch(Exception $e) {
           return $e->getMessage();
        }
    }

    public function updateStatus($id)
    {
        $question = $this->getByIdCount($id);
        if ($question == 0) return false;

        $active = $this->getById($id);

        try {
            DB::table('question')
            ->where('question_id', '=', $id)
            ->update([
                'question_active' => !$active->question_active
            ]);
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->question_id,
            'quiz' => [
                'quiz_id' => $table->quiz_id,
                'quiz_name' => $table->quiz_name,
                'back_button' => $table->back_button,
                'questions_per_page' => $table->questions_per_page,
                'start_date' => $table->start_date,
                'end_date' => $table->end_date,
                'total_questions' => $table->total_questions,
                'active' => $table->active,
                'created_at' => $table->created_at
            ],
            'question_title' => $table->question_title,
            'alternatives_length' => $table->alternatives_length,
            'active' => $table->question_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
