<?php

namespace Tests\Feature\Study;

use App\Models\Exam;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\Subject;
use Database\Seeders\ComprehensiveCurriculumSeeder;
use Database\Seeders\CurriculumSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculumHierarchyAndSimulatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            CurriculumSeeder::class,
            ComprehensiveCurriculumSeeder::class,
        ]);
    }

    public function test_can_fetch_chapters_of_a_subject(): void
    {
        $subject = Subject::where('slug', 'bangla')->first();
        $this->assertNotNull($subject);

        $response = $this->getJson("/api/v1/subjects/{$subject->slug}/chapters");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'subject' => ['id', 'name', 'slug'],
                'data' => [
                    '*' => ['id', 'name', 'slug', 'topics_count']
                ]
            ]);

        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }

    public function test_can_fetch_exams_and_syllabus(): void
    {
        $exam = Exam::where('slug', 'bcs-preliminary')->first();
        $this->assertNotNull($exam);

        $response = $this->getJson("/api/v1/exams/{$exam->slug}/syllabus");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'exam' => ['id', 'name', 'slug', 'total_marks'],
                'syllabus' => [
                    '*' => ['id', 'name', 'allocated_marks', 'chapters']
                ]
            ]);
    }

    public function test_can_fetch_previous_questions_and_sources(): void
    {
        $responseSources = $this->getJson('/api/v1/question-sources');
        $responseSources->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'year']
                ]
            ]);

        $responseQuestions = $this->getJson('/api/v1/previous-questions');
        $responseQuestions->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'question', 'answer', 'explanation', 'previous_exam_tag']
                ]
            ]);
    }

    public function test_can_start_and_submit_model_test_with_negative_marking(): void
    {
        $modelTest = ModelTest::first();
        $this->assertNotNull($modelTest);

        // 1. Start attempt
        $startRes = $this->postJson("/api/v1/model-tests/{$modelTest->slug}/start");
        $startRes->assertStatus(200)
            ->assertJsonStructure([
                'attempt_id',
                'model_test',
                'questions' => [
                    '*' => ['serial', 'id', 'question', 'options']
                ]
            ]);

        $attemptId = $startRes->json('attempt_id');
        $questions = $startRes->json('questions');
        $this->assertNotEmpty($questions);

        // 2. Submit answers (first question correct, second question wrong)
        $q1 = PublicQuestion::find($questions[0]['id']);
        $answers = [
            [
                'question_id' => $q1->id,
                'selected_option' => $q1->correct_option, // correct
                'time_spent_seconds' => 15,
            ],
        ];

        if (count($questions) > 1) {
            $q2 = PublicQuestion::find($questions[1]['id']);
            $wrongOption = $q2->correct_option === 'ক' ? 'খ' : 'ক';
            $answers[] = [
                'question_id' => $q2->id,
                'selected_option' => $wrongOption, // wrong
                'time_spent_seconds' => 10,
            ];
        }

        $submitRes = $this->postJson("/api/v1/test-attempts/{$attemptId}/submit", [
            'answers' => $answers,
            'time_taken_seconds' => 45,
        ]);

        $submitRes->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'attempt_id',
                'score',
                'total_correct',
                'total_wrong',
                'is_passed',
            ]);

        $this->assertEquals(1, $submitRes->json('total_correct'));

        // 3. View Result
        $resultRes = $this->getJson("/api/v1/test-attempts/{$attemptId}/result");
        $resultRes->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'score',
                    'total_answered',
                    'total_correct',
                    'total_wrong',
                ]
            ]);
    }
}
