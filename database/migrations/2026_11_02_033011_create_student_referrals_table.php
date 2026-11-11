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
        Schema::create('student_referrals', function (Blueprint $table) {
            $table->increments('referral_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('university_id');
            $table->string('referral_code', 30)->unique();
            $table->string('status', 30)->default('فعال');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('used_at')->nullable();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('university_id')->references('university_id')->on('universities')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('notification_tone', 50)->default('chime')->after('notification_sound');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_referrals');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_tone');
        });
    }
};
