<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['bcs', 'bank', 'primary', 'ntrca', 'non_cadre', 'other'])->default('bcs')->index();
            $table->text('description')->nullable();
            $table->integer('total_marks')->default(100);
            $table->integer('duration_minutes')->default(60);
            $table->boolean('is_featured')->default(false)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->integer('marks')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['exam_id', 'subject_id']);
        });

        Schema::create('exam_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->tinyInteger('importance_rating')->default(2)->comment('1 to 3 stars');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['exam_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_topics');
        Schema::dropIfExists('exam_subjects');
        Schema::dropIfExists('exams');
    }
};
