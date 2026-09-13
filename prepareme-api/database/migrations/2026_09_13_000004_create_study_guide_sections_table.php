<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_guide_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_guide_id')->constrained('study_guides')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->enum('section_type', ['explanation', 'example', 'formula', 'summary', 'practice'])->default('explanation')->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_guide_sections');
    }
};
