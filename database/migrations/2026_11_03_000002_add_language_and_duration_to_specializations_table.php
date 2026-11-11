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
        Schema::table('specializations', function (Blueprint $table) {
            if (!Schema::hasColumn('specializations', 'language')) {
                $table->string('language', 50)->default('العربية')->after('specialization_name');
            }
            if (!Schema::hasColumn('specializations', 'duration_years')) {
                $table->integer('duration_years')->default(4)->after('language');
            }
            if (!Schema::hasColumn('specializations', 'study_plans')) {
                $table->json('study_plans')->nullable()->after('tuition_fees');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specializations', function (Blueprint $table) {
            $table->dropColumn(['language', 'duration_years', 'study_plans']);
        });
    }
};
