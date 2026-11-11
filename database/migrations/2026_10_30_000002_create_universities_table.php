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
        Schema::create('universities', function (Blueprint $table) {
            $table->increments('university_id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('university_name', 100);
            $table->string('university_type', 50); // حكومية / أهلية
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('campus_image', 255)->nullable();
            $table->string('district', 100);
            $table->string('contact_phone', 20)->nullable();
            $table->string('address', 255);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('status', 30)->default('نشطة');
            $table->year('year_establ')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
