<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->boolean('active')->default(true);
        });
        Schema::create('subject', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('email');
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->integer('role_id')->references('id')->on('role');
            $table->integer('subject_id')->references('id')->on('subject')->nullable();
            $table->timestamps();
        });
        Schema::create('school', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('address');
            $table->boolean('active')->default(true);
            $table->integer('staff_id')->references('id')->on('staff');
            $table->timestamps();
        });
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('email');
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->integer('school_id')->references('id')->on('school');
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
        Schema::dropIfExists('role');
        Schema::dropIfExists('subject');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('school');
        Schema::dropIfExists('student');
    }
}
