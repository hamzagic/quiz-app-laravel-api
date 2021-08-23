<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ManyToManyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_question', function (Blueprint $table) {
            $table->primary(['quiz, question']);
            $table->integer('quiz_id');
            $table->integer('question_id');
        });

        Schema::create('question_answer', function (Blueprint $table) {
            $table->primary(['question, answer']);
            $table->integer('question_id');
            $table->integer('answer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_question');
        Schema::dropIfExists('question_answer');
    }
}
