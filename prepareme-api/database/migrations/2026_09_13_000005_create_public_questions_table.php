<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->foreignId('study_guide_id')->nullable()->constrained('study_guides')->nullOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->text('explanation')->nullable();
            $table->enum('question_type', ['short_answer', 'mcq', 'true_false'])->default('mcq')->index();
            $table->json('options')->nullable();
            $table->string('correct_option')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium')->index();
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_questions');
    }
};
