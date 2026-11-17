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
        Schema::create('content_moderations', function (Blueprint $table) {
            $table->increments('moderation_id');
            $table->unsignedInteger('university_id');
            $table->string('item_type', 50)->default('specialization'); // specialization, college, announcement, etc.
            $table->unsignedInteger('item_id');
            $table->string('item_title', 200);
            $table->string('status', 30)->default('محجوب'); // محجوب, قيد_المراجعة, تم_الحل
            $table->text('violation_reason');
            $table->text('admin_guidance')->nullable(); // توجيهات مدير النظام للجامعة لتصحيح المخالفة
            $table->text('correction_notes')->nullable(); // رد الجامعة وبيانات التصحيح
            $table->unsignedInteger('suspended_by')->nullable();
            $table->unsignedInteger('resolved_by')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('university_id')->references('university_id')->on('universities')->cascadeOnDelete();
            $table->foreign('suspended_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('resolved_by')->references('user_id')->on('users')->nullOnDelete();

            $table->index(['item_type', 'item_id']);
            $table->index(['university_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_moderations');
    }
};
