<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reg_requests', function (Blueprint $table) {
            $table->increments('req_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('university_id');
            $table->string('full_name', 150);
            $table->string('nationality', 50);
            $table->string('gender', 10);
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->string('province', 100);
            $table->string('secondary_school', 200);
            $table->string('seat_number', 30);
            $table->integer('grad_year');
            $table->string('sec_section', 50);
            $table->string('residence', 255);
            $table->integer('total_score');
            $table->decimal('sec_avg', 5, 2);
            $table->dateTime('req_date');
            $table->string('phone', 20);
            $table->string('email', 150)->nullable();
            $table->string('guardian_name', 150);
            $table->string('guardian', 100)->nullable();
            $table->string('guardian_rel', 50);
            $table->string('guardian_occup', 100)->nullable();
            $table->string('home_address', 255);
            $table->string('gu_phone', 20);
            $table->string('desired_spec', 150);
            $table->string('status', 30)->default('قيد المراجعة');
            $table->dateTime('decision_date')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->foreign('student_id')->references('student_id')->on('students')->restrictOnDelete();
            $table->foreign('university_id')->references('university_id')->on('universities')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reg_requests');
    }
};
