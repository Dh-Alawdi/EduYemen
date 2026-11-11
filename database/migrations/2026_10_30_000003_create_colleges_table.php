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
        Schema::create('colleges', function (Blueprint $table) {
            $table->increments('college_id');
            $table->unsignedInteger('university_id');
            $table->string('college_name', 150);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('نشطة');

            $table->foreign('university_id')->references('university_id')->on('universities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};
