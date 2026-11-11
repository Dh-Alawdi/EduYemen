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
        Schema::create('admission_data', function (Blueprint $table) {
            $table->increments('admission_id');
            $table->unsignedInteger('specialization_id')->unique();
            $table->text('admission_data');
            $table->decimal('minimum_average', 5, 2);
            $table->text('description')->nullable();
            $table->integer('secondary_last_year')->nullable();
            $table->json('required_documents');

            $table->foreign('specialization_id')->references('specialization_id')->on('specializations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_data');
    }
};
