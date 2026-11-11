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
        Schema::create('reg_docs', function (Blueprint $table) {
            $table->increments('reg_doc_id');
            $table->unsignedInteger('req_id');
            $table->string('document_name', 150);
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->dateTime('up_at');
            $table->string('ver_status', 20)->default('قيد المراجعة');
            $table->text('ver_note')->nullable();
            $table->dateTime('verified_at')->nullable();

            $table->foreign('req_id')->references('req_id')->on('reg_requests')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reg_docs');
    }
};
