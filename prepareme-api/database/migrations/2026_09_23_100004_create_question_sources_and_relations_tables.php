<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $table->integer('year')->nullable()->index();
            $table->date('exam_date')->nullable();
            $table->integer('total_questions')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('public_questions', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->after('subject_id')->constrained('chapters')->nullOnDelete();
            $table->foreignId('source_id')->nullable()->after('difficulty')->constrained('question_sources')->nullOnDelete();
            $table->decimal('marks', 4, 2)->default(1.00)->after('correct_option');
            $table->decimal('negative_marks', 4, 2)->default(0.25)->after('marks');
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_question_id')->constrained('public_questions')->cascadeOnDelete();
            $table->string('option_key', 10)->comment('A, B, C, D');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['public_question_id', 'option_key']);
        });

        Schema::create('question_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_question_id')->constrained('public_questions')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['public_question_id', 'topic_id']);
        });

        Schema::create('question_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_question_id')->constrained('public_questions')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['public_question_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_exams');
        Schema::dropIfExists('question_topics');
        Schema::dropIfExists('question_options');

        Schema::table('public_questions', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['source_id']);
            $table->dropColumn(['chapter_id', 'source_id', 'marks', 'negative_marks']);
        });

        Schema::dropIfExists('question_sources');
    }
};
