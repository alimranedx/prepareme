<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('chapters')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->enum('model_test_type', ['full_exam', 'subject_wise', 'chapter_wise', 'topic_practice', 'custom'])->default('full_exam')->index();
            $table->integer('total_questions')->default(100);
            $table->decimal('total_marks', 6, 2)->default(100.00);
            $table->decimal('pass_marks', 6, 2)->default(50.00);
            $table->integer('duration_minutes')->default(60);
            $table->decimal('negative_marking_rate', 4, 2)->default(0.25)->comment('Marks deducted per wrong answer');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->index();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('model_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_test_id')->constrained('model_tests')->cascadeOnDelete();
            $table->foreignId('public_question_id')->constrained('public_questions')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->decimal('marks', 4, 2)->default(1.00);
            $table->timestamps();

            $table->unique(['model_test_id', 'public_question_id']);
        });

        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('model_test_id')->constrained('model_tests')->cascadeOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('total_answered')->default(0);
            $table->integer('total_correct')->default(0);
            $table->integer('total_wrong')->default(0);
            $table->integer('total_skipped')->default(0);
            $table->decimal('score', 6, 2)->default(0.00);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->boolean('is_passed')->default(false);
            $table->enum('status', ['in_progress', 'completed', 'timed_out'])->default('in_progress')->index();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('test_attempts')->cascadeOnDelete();
            $table->foreignId('public_question_id')->constrained('public_questions')->cascadeOnDelete();
            $table->string('selected_option', 10)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->decimal('marks_awarded', 4, 2)->default(0.00);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamps();

            $table->index(['attempt_id', 'public_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_answers');
        Schema::dropIfExists('test_attempts');
        Schema::dropIfExists('model_test_questions');
        Schema::dropIfExists('model_tests');
    }
};
