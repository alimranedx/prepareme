<?php

namespace Tests\Feature;

use App\Enums\OcrDocumentStatus;
use App\Jobs\ProcessOcrDocumentJob;
use App\Models\OcrDocument;
use App\Models\User;
use App\Services\Ocr\OcrManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OcrWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_invalid_mime_type_for_ocr(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $fakeFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->postJson('/api/v1/my/ocr-documents', [
            'image' => $fakeFile,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_user_can_upload_valid_image_and_trigger_job(): void
    {
        Storage::fake('local');
        Queue::fake();

        $user = User::factory()->create();
        $fakeImage = UploadedFile::fake()->image('bcs_book_page.jpg', 800, 1000);

        $response = $this->actingAs($user)->postJson('/api/v1/my/ocr-documents', [
            'image' => $fakeImage,
            'language' => 'ben+eng',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.original_file_name', 'bcs_book_page.jpg')
            ->assertJsonPath('data.status', 'uploaded');

        $this->assertDatabaseHas('ocr_documents', [
            'user_id' => $user->id,
            'original_file_name' => 'bcs_book_page.jpg',
            'status' => 'uploaded',
        ]);

        Queue::assertPushed(ProcessOcrDocumentJob::class);
    }

    public function test_ocr_processing_job_execution_and_result_generation(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $fakeImage = UploadedFile::fake()->image('bangla_page.jpg');
        $storedPath = $fakeImage->store('private/ocr/' . $user->id, 'local');

        $document = OcrDocument::create([
            'user_id' => $user->id,
            'original_file_path' => $storedPath,
            'original_file_name' => 'bangla_page.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
            'language' => 'ben+eng',
            'status' => OcrDocumentStatus::UPLOADED,
        ]);

        $job = new ProcessOcrDocumentJob($document);
        $job->handle(new OcrManager());

        $document->refresh();

        $this->assertEquals(OcrDocumentStatus::COMPLETED, $document->status);
        $this->assertNotNull($document->latestResult);
        $this->assertNotEmpty($document->latestResult->raw_text);
        $this->assertEquals('dev-fallback', $document->latestResult->provider);
    }

    public function test_user_cannot_access_another_users_ocr_document(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $document = OcrDocument::create([
            'user_id' => $owner->id,
            'original_file_path' => 'private/sample.jpg',
            'original_file_name' => 'sample.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 500,
            'language' => 'ben',
            'status' => OcrDocumentStatus::COMPLETED,
        ]);

        $response = $this->actingAs($intruder)->getJson("/api/v1/my/ocr-documents/{$document->id}");
        $response->assertStatus(403);
    }

    public function test_user_can_edit_ocr_result_and_save_as_personal_question(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $document = OcrDocument::create([
            'user_id' => $user->id,
            'original_file_path' => 'private/ocr/test.jpg',
            'original_file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 500,
            'language' => 'ben+eng',
            'status' => OcrDocumentStatus::COMPLETED,
        ]);

        $document->results()->create([
            'raw_text' => "Uncorrected raw extracted text from book",
            'provider' => 'dev-fallback',
        ]);

        $response = $this->actingAs($user)->postJson("/api/v1/my/ocr-documents/{$document->id}/save-as-question", [
            'question' => 'Edited Question from book page',
            'answer' => 'Edited Answer',
            'explanation' => 'Detailed explanation after review',
            'source_title' => 'MP3 General Knowledge',
            'source_page' => '102',
            'corrected_text' => 'Corrected high-quality text',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.question', 'Edited Question from book page')
            ->assertJsonPath('data.source_title', 'MP3 General Knowledge');

        $this->assertDatabaseHas('personal_questions', [
            'user_id' => $user->id,
            'ocr_document_id' => $document->id,
            'question' => 'Edited Question from book page',
        ]);

        $this->assertDatabaseHas('ocr_results', [
            'ocr_document_id' => $document->id,
            'corrected_text' => 'Corrected high-quality text',
        ]);
    }
}
