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
        'question_active'
    ];

    public function create($data)
    {
        try {
            $question = DB::table('question')->insertGetId([
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
        ->where('question_id', '=', $id)
        ->first();

        return $question;
    }

    public function getByIdFull($id)
    {
        $question = DB::table('question')
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
            'question_title' => $table->question_title,
            'alternatives_length' => $table->alternatives_length,
            'active' => $table->question_active,
            'created_at' => $table->created_at,
            'updated_at' => $table->updated_at,
        );

        return $response;
    }
}
