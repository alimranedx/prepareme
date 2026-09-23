<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Chapter;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnglishMasterComprehensiveCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        $english = Subject::where('slug', 'english')->firstOrFail();

        // 1. Run English Grammar and English Literature seeders
        $this->call([
            EnglishGrammarSeeder::class,
            EnglishLiteratureSeeder::class,
        ]);

        $chGrammar = Chapter::where('slug', 'english-grammar-and-usage')->firstOrFail();
        $chLit = Chapter::where('slug', 'english-literature')->firstOrFail();

        // 2. Safe mapping of old topic slugs to the new official syllabus
        $topicMigrationMap = [
            'parts-of-speech-identification' => ['slug' => 'parts-of-speech-overview-and-identification', 'chapter_id' => $chGrammar->id],
            'appropriate-prepositions-and-idioms' => ['slug' => 'preposition-and-appropriate-usage', 'chapter_id' => $chGrammar->id],
            'voice-and-narration' => ['slug' => 'voice-change-active-to-passive', 'chapter_id' => $chGrammar->id],
            'synonyms-and-antonyms' => ['slug' => 'vocabulary-synonyms-and-antonyms', 'chapter_id' => $chGrammar->id],
            'idioms-phrases-one-word' => ['slug' => 'idioms-phrases-group-verbs', 'chapter_id' => $chGrammar->id],
            'shakespeare-and-romantic-poets' => ['slug' => 'william-shakespeare-master-drama', 'chapter_id' => $chLit->id],
            'literary-periods-and-quotes' => ['slug' => 'periods-of-english-literature', 'chapter_id' => $chLit->id],
            'english-grammar' => ['slug' => 'parts-of-speech-overview-and-identification', 'chapter_id' => $chGrammar->id],
            'english-literature' => ['slug' => 'periods-of-english-literature', 'chapter_id' => $chLit->id],
        ];

        foreach ($topicMigrationMap as $oldSlug => $dest) {
            $oldTopic = Topic::where('subject_id', $english->id)->where('slug', $oldSlug)->first();
            $targetTopic = Topic::where('subject_id', $english->id)->where('slug', $dest['slug'])->first();

            if ($oldTopic && $targetTopic && $oldTopic->id !== $targetTopic->id) {
                // Reassign questions and study guides
                PublicQuestion::where('topic_id', $oldTopic->id)->update([
                    'topic_id' => $targetTopic->id,
                    'chapter_id' => $dest['chapter_id'],
                ]);
                StudyGuide::where('topic_id', $oldTopic->id)->update([
                    'topic_id' => $targetTopic->id,
                ]);
                $oldTopic->delete();
            }
        }

        // Clean up legacy empty chapters
        Chapter::where('subject_id', $english->id)
            ->whereIn('slug', ['english-grammar-syntax', 'english-vocabulary-usage', 'english-literature-periods'])
            ->delete();

        // 3. Subject-wise Model Test for English
        $allEngQuestions = PublicQuestion::where('subject_id', $english->id)->get();

        $engModelTest = ModelTest::updateOrCreate(
            ['slug' => 'english-subject-wise-model-test-01'],
            [
                'title' => 'ইংরেজি ভাষা ও সাহিত্য বিষয়ভিত্তিক টেস্ট – ০১ (BCS & Bank)',
                'subject_id' => $english->id,
                'model_test_type' => 'subject_wise',
                'description' => 'ইংরেজি গ্রামার ও সাহিত্যের উচ্চ-গুরুত্বপূর্ণ ৩৫টি প্রশ্ন সংবলিত পূর্ণাঙ্গ বিষয়ভিত্তিক প্রস্তুতি টেস্ট।',
                'total_questions' => $allEngQuestions->count(),
                'total_marks' => (float) $allEngQuestions->count(),
                'pass_marks' => round($allEngQuestions->count() * 0.5, 2),
                'duration_minutes' => 35,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        if ($engModelTest && $allEngQuestions->isNotEmpty()) {
            $syncData = [];
            foreach ($allEngQuestions as $idx => $eq) {
                $syncData[$eq->id] = ['sort_order' => $idx + 1];
            }
            $engModelTest->questions()->sync($syncData);
        }

        // Also enrich Bank recruitment model test with English questions
        $bankModelTest = ModelTest::where('slug', 'bank-recruitment-special-model-test-01')->first();
        if ($bankModelTest && $allEngQuestions->isNotEmpty()) {
            $bankSampleEng = $allEngQuestions->take(20)->pluck('id');
            $bankModelTest->questions()->syncWithoutDetaching($bankSampleEng);
            $bankModelTest->update([
                'total_questions' => $bankModelTest->questions()->count(),
                'total_marks' => (float) $bankModelTest->questions()->count(),
            ]);
        }

        // Enrich BCS Preliminary Grand Model Test
        $bcsModelTest = ModelTest::where('slug', 'bcs-preliminary-grand-model-test-01')->first();
        if ($bcsModelTest && $allEngQuestions->isNotEmpty()) {
            $bcsSampleEng = $allEngQuestions->take(35)->pluck('id');
            $bcsModelTest->questions()->syncWithoutDetaching($bcsSampleEng);
            $bcsModelTest->update([
                'total_questions' => $bcsModelTest->questions()->count(),
                'total_marks' => (float) $bcsModelTest->questions()->count(),
            ]);
        }
    }
}
