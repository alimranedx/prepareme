<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocr_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('original_file_path');
            $table->string('original_file_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->enum('language', ['ben', 'eng', 'ben+eng'])->default('ben+eng')->index();
            $table->enum('status', ['uploaded', 'processing', 'completed', 'failed'])->default('uploaded')->index();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocr_documents');
    }
};
