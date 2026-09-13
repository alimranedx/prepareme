<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->foreignId('ocr_document_id')->nullable()->constrained('ocr_documents')->nullOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->text('explanation')->nullable();
            $table->string('source_title')->nullable();
            $table->string('source_page', 50)->nullable();
            $table->enum('status', ['active', 'archived'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_questions');
    }
};
