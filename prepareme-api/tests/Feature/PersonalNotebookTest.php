<?php

namespace Tests\Feature;

use App\Enums\ContentStatus;
use App\Enums\UserRole;
use App\Models\Bookmark;
use App\Models\PersonalQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalNotebookTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_read_own_personal_question(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/my/questions', [
            'question' => 'What is the date of historic 7th March speech?',
            'answer' => '7 March 1971',
            'explanation' => 'Delivered by Bangabandhu Sheikh Mujibur Rahman.',
            'source_title' => 'Banglapedia',
            'source_page' => 'Vol 2, p. 45',
            'tags' => ['History', 'Important'],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.question', 'What is the date of historic 7th March speech?');

        $this->assertDatabaseHas('personal_questions', [
            'user_id' => $user->id,
            'question' => 'What is the date of historic 7th March speech?',
            'source_title' => 'Banglapedia',
        ]);
    }

    public function test_user_cannot_access_another_users_personal_question(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $question = PersonalQuestion::create([
            'user_id' => $owner->id,
            'question' => 'Owner Private Note',
            'answer' => 'Secret',
            'status' => 'active',
        ]);

        // Intruder tries to view
        $viewResponse = $this->actingAs($intruder)->getJson("/api/v1/my/questions/{$question->id}");
        $viewResponse->assertStatus(403);

        // Intruder tries to update
        $updateResponse = $this->actingAs($intruder)->putJson("/api/v1/my/questions/{$question->id}", [
            'question' => 'Tampered Question',
            'answer' => 'Tampered Answer',
        ]);
        $updateResponse->assertStatus(403);

        // Intruder tries to delete
        $deleteResponse = $this->actingAs($intruder)->deleteJson("/api/v1/my/questions/{$question->id}");
        $deleteResponse->assertStatus(403);

        $this->assertDatabaseHas('personal_questions', [
            'id' => $question->id,
            'question' => 'Owner Private Note',
        ]);
    }

    public function test_bookmark_uniqueness_and_removal(): void
    {
        $user = User::factory()->create();

        $subject = Subject::create(['name' => 'Sub', 'slug' => 'sub', 'status' => ContentStatus::PUBLISHED]);
        $topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Top', 'slug' => 'top', 'status' => ContentStatus::PUBLISHED]);
        $guide = StudyGuide::create(['topic_id' => $topic->id, 'title' => 'Guide', 'slug' => 'guide', 'content' => 'Text', 'status' => ContentStatus::PUBLISHED]);

        // Add bookmark
        $res1 = $this->actingAs($user)->postJson('/api/v1/my/bookmarks', [
            'study_guide_id' => $guide->id,
        ]);
        $res1->assertCreated();

        // Duplicate bookmark should not create multiple records
        $res2 = $this->actingAs($user)->postJson('/api/v1/my/bookmarks', [
            'study_guide_id' => $guide->id,
        ]);
        $res2->assertStatus(201); // idempotent firstOrCreate

        $this->assertEquals(1, Bookmark::where('user_id', $user->id)->where('study_guide_id', $guide->id)->count());

        // Remove bookmark
        $delRes = $this->actingAs($user)->deleteJson("/api/v1/my/bookmarks/{$guide->id}");
        $delRes->assertOk();
        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'study_guide_id' => $guide->id,
        ]);
    }

    public function test_reading_progress_updates(): void
    {
        $user = User::factory()->create();

        $subject = Subject::create(['name' => 'Sub', 'slug' => 'sub', 'status' => ContentStatus::PUBLISHED]);
        $topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Top', 'slug' => 'top', 'status' => ContentStatus::PUBLISHED]);
        $guide = StudyGuide::create(['topic_id' => $topic->id, 'title' => 'Guide', 'slug' => 'guide', 'content' => 'Text', 'status' => ContentStatus::PUBLISHED]);

        $response = $this->actingAs($user)->putJson("/api/v1/my/progress/{$guide->id}", [
            'progress_percent' => 100,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.progress_percent', 100);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'study_guide_id' => $guide->id,
            'status' => 'completed',
        ]);
    }
}
