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
        Schema::create('university_announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('university_id');
            $table->string('title', 200);
            $table->text('content');
            $table->string('category', 50)->default('عام');
            $table->string('status', 30)->default('نشط');
            $table->timestamps();

            $table->foreign('university_id')->references('university_id')->on('universities')->onDelete('cascade');
        });

        Schema::create('university_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('university_id');
            $table->string('provider_name', 100);
            $table->string('account_number', 100);
            $table->string('account_name', 150);
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('university_id')->references('university_id')->on('universities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_payment_methods');
        Schema::dropIfExists('university_announcements');
    }
};
