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
        Schema::create('specializations', function (Blueprint $table) {
            $table->increments('specialization_id');
            $table->unsignedInteger('college_id');
            $table->string('specialization_name', 150);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('نشط');
            $table->decimal('tuition_fees', 10, 2)->default(0.00);

            $table->foreign('college_id')->references('college_id')->on('colleges')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
