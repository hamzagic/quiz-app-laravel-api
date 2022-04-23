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
        'question_id',
        'answer_content',
        'answer_active',
        'answer_is_correct'
    ];

    public function create($data)
    {
        try {
            $answer = DB::table('answer')->insertGetId([
                'question_id' => $data['question_id'],
                'answer_content' => $data['content'],
                'answer_is_correct' => $data['correct'],
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
        $answers = DB::table('answer')
        ->join('question', 'question.question_id', '=', 'answer.question_id')
        ->select('*')
        ->get();

        $result = array();
        foreach($answers as $item) {
            array_push($result, $this->formatResponse($item));
        }

        return $result;
    }

    public function getById($id)
    {
        $answer = DB::table('answer')
        ->join('question', 'question.question_id', '=', 'answer.question_id')
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
                'answer_is_correct' => $data['correct'],
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

    public function formatResponse($table)
    {
        $response = array(
            'id' => $table->answer_id,
            'question' => [
                'question_id' => $table->question_id,
                'question_title' => $table->question_title,
                'alternatives_length' => $table->alternatives_length,
                'active' => $table->question_active,
                'created_at' => $table->created_at,
                'updated_at' => $table->updated_at
            ],
            'answer_title' => $table->answer_content,
            'is_correct' => $table->answer_is_correct,
            'active' => $table->answer_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
