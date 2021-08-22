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
            $table->id();
            $table->string('name', 255);
            $table->integer('school_id')->refereces('id')->on('school');
            $table->integer('staff_id')->references('id')->on('staff');
            $table->timestamps();
        });
        Schema::create('answer', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->integer('alternatives_length');
            $table->string('answers_ids');
            $table->string('correct_answer_id')->references('id')->on('answer');
            $table->boolean('active')->default(true);
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
