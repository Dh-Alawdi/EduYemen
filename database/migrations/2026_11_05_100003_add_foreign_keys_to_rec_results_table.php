<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إضافة الربط المرجعي لنتائج الذكاء الاصطناعي (rec_results)
     * مع جدولي الجامعات والتخصصات (university_id و specialization_id)
     */
    public function up(): void
    {
        Schema::table('rec_results', function (Blueprint $table) {
            if (!Schema::hasColumn('rec_results', 'university_id')) {
                $table->unsignedInteger('university_id')->nullable()->after('r_d_id');
                $table->foreign('university_id')
                      ->references('university_id')
                      ->on('universities')
                      ->nullOnDelete();
            }

            if (!Schema::hasColumn('rec_results', 'specialization_id')) {
                $table->unsignedInteger('specialization_id')->nullable()->after('university_id');
                $table->foreign('specialization_id')
                      ->references('specialization_id')
                      ->on('specializations')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rec_results', function (Blueprint $table) {
            if (Schema::hasColumn('rec_results', 'specialization_id')) {
                $table->dropForeign(['specialization_id']);
                $table->dropColumn('specialization_id');
            }

            if (Schema::hasColumn('rec_results', 'university_id')) {
                $table->dropForeign(['university_id']);
                $table->dropColumn('university_id');
            }
        });
    }
};
