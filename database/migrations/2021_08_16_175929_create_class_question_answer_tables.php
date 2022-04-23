<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassQuestionAnswerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class', function (Blueprint $table) {
            $table->id('class_id');
            $table->string('class_name', 255);
            $table->integer('school_id')->refereces('school_id')->on('school');
            $table->integer('staff_id')->references('staff_id')->on('staff');
            $table->timestamps();
        });
        Schema::create('answer', function (Blueprint $table) {
            $table->id('answer_id');
            $table->integer('question_id')->references('question_id')->on('question');
            $table->string('answer_content');
            $table->boolean('answer_active')->default(true);
            $table->boolean('answer_is_correct')->default(false);
            $table->timestamps();
        });
        Schema::create('question', function (Blueprint $table) {
            $table->id('question_id');
            $table->integer('quiz_id')->references('quiz_id')->on('quiz');
            $table->string('question_title', 255);
            $table->integer('alternatives_length');
            $table->boolean('question_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class');
        Schema::dropIfExists('answer');
        Schema::dropIfExists('question');
    }
}
