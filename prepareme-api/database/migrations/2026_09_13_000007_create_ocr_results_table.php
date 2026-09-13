<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocr_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocr_document_id')->constrained('ocr_documents')->cascadeOnDelete();
            $table->longText('raw_text');
            $table->longText('corrected_text')->nullable();
            $table->string('provider', 50)->nullable();
            $table->string('provider_reference')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocr_results');
    }
};
