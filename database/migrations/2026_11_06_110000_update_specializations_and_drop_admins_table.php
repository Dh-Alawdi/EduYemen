<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * تعديل جدول specializations ليحتوي على الحقول المحددة:
     * (specialization_id, college_id, specialization_name, status, description, duration_years, tuition_fees, language)
     * وحذف جدول admins لكون صلاحية مدير النظام تتحدد عبر user_type في جدول users.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. حذف جدول admins
        if (Schema::hasTable('admins')) {
            Schema::dropIfExists('admins');
        }

        // 2. تعديل جدول specializations
        Schema::table('specializations', function (Blueprint $table) {
            if (!Schema::hasColumn('specializations', 'duration_years')) {
                $table->unsignedTinyInteger('duration_years')->default(4)->after('description');
            }
            if (!Schema::hasColumn('specializations', 'language')) {
                $table->string('language', 50)->default('العربية')->after('tuition_fees');
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specializations', function (Blueprint $table) {
            if (Schema::hasColumn('specializations', 'duration_years')) {
                $table->dropColumn('duration_years');
            }
            if (Schema::hasColumn('specializations', 'language')) {
                $table->dropColumn('language');
            }
        });

        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->increments('admin_id');
                $table->unsignedInteger('user_id');
                $table->string('full_name', 150);

                $table->foreign('user_id')
                      ->references('user_id')
                      ->on('users')
                      ->cascadeOnDelete();
            });
        }
    }
};
