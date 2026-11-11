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
        Schema::table('reg_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('reg_requests', 'can_cancel')) {
                $table->boolean('can_cancel')->default(true)->after('status');
            }
            if (!Schema::hasColumn('reg_requests', 'is_replacement')) {
                $table->boolean('is_replacement')->default(false)->after('can_cancel');
            }
            if (!Schema::hasColumn('reg_requests', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('decision_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reg_requests', function (Blueprint $table) {
            $table->dropColumn(['can_cancel', 'is_replacement', 'cancelled_at']);
        });
    }
};
