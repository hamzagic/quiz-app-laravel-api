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
            $table->id();
            $table->integer('quiz_id')->refereces('quiz_id')->on('quiz');
            $table->integer('question_id')->refereces('question_id')->on('question');
        });

        Schema::create('question_answer', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id')->refereces('question_id')->on('question');
            $table->integer('answer_id')->refereces('answer_id')->on('answer');
            $table->integer('quiz_id')->refereces('quiz_id')->on('quiz');
            $table->boolean('is_answer_correct')->default(false);
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
