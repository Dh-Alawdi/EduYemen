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
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_mode', 20)->default('light')->after('account_status');
            $table->string('theme_color', 30)->default('navy')->after('theme_mode');
            $table->boolean('notifications_muted')->default(false)->after('theme_color');
            $table->boolean('notification_sound')->default(true)->after('notifications_muted');
            $table->string('referral_code', 30)->nullable()->unique()->after('notification_sound');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('city', 100)->nullable()->after('address');
            $table->string('high_school_name', 150)->nullable()->after('city');
            $table->decimal('high_school_avg', 5, 2)->nullable()->after('high_school_name');
            $table->integer('grad_year')->nullable()->after('high_school_avg');
            $table->string('desired_major', 150)->nullable()->after('grad_year');
            $table->text('bio')->nullable()->after('desired_major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'theme_mode',
                'theme_color',
                'notifications_muted',
                'notification_sound',
                'referral_code',
            ]);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'high_school_name',
                'high_school_avg',
                'grad_year',
                'desired_major',
                'bio',
            ]);
        });
    }
};
