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
            $table->id('role_id');
            $table->string('role_title', 255)->unique();
            $table->boolean('role_active')->default(true);
        });
        Schema::create('subject', function (Blueprint $table) {
            $table->id('subject_id');
            $table->string('subject_name', 255)->unique();
            $table->boolean('subject_active')->default(true);
            $table->timestamps();
        });
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('staff_first_name', 255);
            $table->string('staff_last_name', 255);
            $table->string('staff_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('staff_active')->default(true);
            $table->integer('role_id')->references('role_id')->on('role');
            $table->integer('subject_id')->references('subject_id')->on('subject')->nullable();
            $table->timestamps();
        });
        Schema::create('school', function (Blueprint $table) {
            $table->id('school_id');
            $table->string('school_name', 255);
            $table->string('school_address');
            $table->string('phone_number');
            $table->boolean('school_active')->default(false);
            $table->integer('staff_id')->references('staff_id')->on('staff')->nullable();
            $table->timestamps();
        });
        Schema::create('student', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('student_first_name', 255);
            $table->string('student_last_name', 255);
            $table->string('student_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('student_active')->default(false);
            $table->integer('school_id')->references('school_id')->on('school');
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
