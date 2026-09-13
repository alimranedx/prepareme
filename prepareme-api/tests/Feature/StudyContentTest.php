<?php

namespace Tests\Feature;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Enums\UserRole;
use App\Models\PublicQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_users_only_see_published_study_guides(): void
    {
        $subject = Subject::create([
            'name' => 'Bangla',
            'slug' => 'bangla',
            'status' => ContentStatus::PUBLISHED,
        ]);

        $topic = Topic::create([
            'subject_id' => $subject->id,
            'name' => 'Grammar',
            'slug' => 'grammar',
            'status' => ContentStatus::PUBLISHED,
        ]);

        $publishedGuide = StudyGuide::create([
            'topic_id' => $topic->id,
            'title' => 'Published Guide',
            'slug' => 'published-guide',
            'content' => 'Content here',
            'status' => ContentStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        $draftGuide = StudyGuide::create([
            'topic_id' => $topic->id,
            'title' => 'Draft Guide',
            'slug' => 'draft-guide',
            'content' => 'Secret content',
            'status' => ContentStatus::DRAFT,
        ]);

        // Public index response
        $response = $this->getJson('/api/v1/study-guides');
        $response->assertOk();
        $titles = collect($response->json('data'))->pluck('title');

        $this->assertTrue($titles->contains('Published Guide'));
        $this->assertFalse($titles->contains('Draft Guide'));

        // Direct show endpoint for draft guide
        $draftResponse = $this->getJson("/api/v1/study-guides/{$draftGuide->slug}");
        $draftResponse->assertStatus(403);
    }

    public function test_admin_can_view_draft_study_guides(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $subject = Subject::create([
            'name' => 'Math',
            'slug' => 'math',
            'status' => ContentStatus::PUBLISHED,
        ]);

        $topic = Topic::create([
            'subject_id' => $subject->id,
            'name' => 'Algebra',
            'slug' => 'algebra',
            'status' => ContentStatus::PUBLISHED,
        ]);

        $draftGuide = StudyGuide::create([
            'topic_id' => $topic->id,
            'title' => 'Secret Draft Algebra',
            'slug' => 'secret-draft-algebra',
            'content' => 'Unpublished algebraic methods',
            'status' => ContentStatus::DRAFT,
        ]);

        $response = $this->actingAs($admin)->getJson("/api/v1/study-guides/{$draftGuide->slug}");
        $response->assertOk()
            ->assertJsonPath('data.title', 'Secret Draft Algebra');
    }

    public function test_public_question_filtering(): void
    {
        $subject1 = Subject::create(['name' => 'Sub1', 'slug' => 'sub1', 'status' => ContentStatus::PUBLISHED]);
        $subject2 = Subject::create(['name' => 'Sub2', 'slug' => 'sub2', 'status' => ContentStatus::PUBLISHED]);

        PublicQuestion::create([
            'subject_id' => $subject1->id,
            'question' => 'What is the capital of Bangladesh?',
            'answer' => 'Dhaka',
            'question_type' => QuestionType::MCQ,
            'difficulty' => DifficultyLevel::EASY,
            'status' => ContentStatus::PUBLISHED,
        ]);

        PublicQuestion::create([
            'subject_id' => $subject2->id,
            'question' => 'Solve 2 + 2 * 2',
            'answer' => '6',
            'question_type' => QuestionType::SHORT_ANSWER,
            'difficulty' => DifficultyLevel::HARD,
            'status' => ContentStatus::PUBLISHED,
        ]);

        $filterResponse = $this->getJson("/api/v1/public-questions?subject_id={$subject1->id}");
        $filterResponse->assertOk();
        $this->assertCount(1, $filterResponse->json('data'));
        $this->assertEquals('Dhaka', $filterResponse->json('data.0.answer'));
    }
}
