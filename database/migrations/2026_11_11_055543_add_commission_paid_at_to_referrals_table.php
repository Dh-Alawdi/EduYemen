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
        // The actual table is 'referrals' (student_referrals is a VIEW)
        Schema::table('referrals', function (Blueprint $table) {
            if (!Schema::hasColumn('referrals', 'commission_paid_at')) {
                $table->timestamp('commission_paid_at')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('referrals', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            if (Schema::hasColumn('referrals', 'commission_paid_at')) {
                $table->dropColumn('commission_paid_at');
            }
            if (Schema::hasColumn('referrals', 'used_at')) {
                $table->dropColumn('used_at');
            }
        });
    }
};
