<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_result', function (Blueprint $table) {
            $table->id('quiz_result_id');
            $table->string('quiz_id')->references('quiz_id')->on('quiz');
            $table->string('student_id')->references('student_id')->on('student');
            $table->integer('total_correct')->nullable();
            $table->integer('total_incorrect')->nullable();
        });

        Schema::create('student_response', function (Blueprint $table) {
            $table->id('student_response_id');
            $table->string('quiz_id')->references('quiz_id')->on('quiz');
            $table->string('student_id')->references('student_id')->on('student');
            $table->string('question_id')->references('question_id')->on('question');
            $table->string('student_answer_id')->references('answer_id')->on('answer');
            $table->boolean('is_correct')->default(false);
            $table->date('done_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_results');
        Schema::dropIfExists('student_response');
    }
}
