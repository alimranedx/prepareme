<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->index(['user_id', 'slug']);
        });

        Schema::create('personal_question_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_question_id')->constrained('personal_questions')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['personal_question_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_question_tag');
        Schema::dropIfExists('tags');
    }
};
