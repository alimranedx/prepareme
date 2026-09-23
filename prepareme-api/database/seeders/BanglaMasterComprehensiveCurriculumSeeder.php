<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class BanglaMasterComprehensiveCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $bangla = Subject::where('slug', 'bangla')->firstOrFail();

        // 1. Run Literature and Grammar seeders
        $this->call([
            BanglaLiteratureSeeder::class,
            BanglaGrammarSeeder::class,
        ]);

        $chLit = Chapter::where('slug', 'bangla-literature')->firstOrFail();
        $chGrammar = Chapter::where('slug', 'bangla-grammar-and-composition')->firstOrFail();

        // 2. Safe mapping of old topic slugs to the new official syllabus
        $topicMigrationMap = [
            'tagore-and-nazrul' => ['slug' => 'poetry-and-poets', 'chapter_id' => $chLit->id],
            'ancient-era-charyapada' => ['slug' => 'ancient-and-medieval-bangla-literature', 'chapter_id' => $chLit->id],
            'medieval-literature-mangalkavya' => ['slug' => 'ancient-and-medieval-bangla-literature', 'chapter_id' => $chLit->id],
            'modern-era-bengali-prose' => ['slug' => 'bangla-prose-and-essays', 'chapter_id' => $chLit->id],
            'important-bengali-poets' => ['slug' => 'poetry-and-poets', 'chapter_id' => $chLit->id],
            'important-bengali-authors' => ['slug' => 'bangla-prose-and-essays', 'chapter_id' => $chLit->id],
            'famous-epic-and-poetry-books' => ['slug' => 'famous-books-and-authors', 'chapter_id' => $chLit->id],
            'famous-bengali-novels' => ['slug' => 'bangla-novels', 'chapter_id' => $chLit->id],
            'famous-bengali-dramas' => ['slug' => 'bangla-drama', 'chapter_id' => $chLit->id],
            'bengali-periodicals-and-essays' => ['slug' => 'periodicals-and-magazines', 'chapter_id' => $chLit->id],
            'bangla-phonetics-and-orthography' => ['slug' => 'phonetics-orthography-details', 'chapter_id' => $chGrammar->id],
            'prefixes-and-suffixes' => ['slug' => 'prefixes-upasarga', 'chapter_id' => $chGrammar->id],
            'bangla-idioms-and-phrases' => ['slug' => 'idioms-bagdhara', 'chapter_id' => $chGrammar->id],
            'natwa-o-satwa-bidhan' => ['slug' => 'spelling-rules-shuddho-banan', 'chapter_id' => $chGrammar->id],
            'bangla-prottoy' => ['slug' => 'suffixes-prottoy', 'chapter_id' => $chGrammar->id],
            'parts-of-speech-bangla-pada' => ['slug' => 'bangla-grammar-origin-and-phonology', 'chapter_id' => $chGrammar->id],
            'sentence-types-and-transformation' => ['slug' => 'sentence-formation-and-correction', 'chapter_id' => $chGrammar->id],
            'antonyms-synonyms-parivasha' => ['slug' => 'word-meanings-synonyms-antonyms', 'chapter_id' => $chGrammar->id],
        ];

        foreach ($topicMigrationMap as $oldSlug => $dest) {
            $oldTopic = Topic::where('slug', $oldSlug)->first();
            $targetTopic = Topic::where('slug', $dest['slug'])->first();

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

        // Clean up legacy chapters
        Chapter::whereIn('slug', ['bangla-first-paper-literature', 'bangla-second-paper-grammar'])->delete();

        // 3. Sync questions to Model Tests
        $allBanglaQuestions = PublicQuestion::where('subject_id', $bangla->id)->get();

        $banglaModelTest = ModelTest::where('slug', 'bangla-subject-wise-model-test-01')->first();
        if ($banglaModelTest) {
            $syncData = [];
            foreach ($allBanglaQuestions as $idx => $bq) {
                $syncData[$bq->id] = ['sort_order' => $idx + 1];
            }
            $banglaModelTest->questions()->sync($syncData);
            $banglaModelTest->update([
                'total_questions' => $allBanglaQuestions->count(),
                'total_marks' => $allBanglaQuestions->count(),
                'pass_marks' => round($allBanglaQuestions->count() * 0.5, 2),
            ]);
        }

        $bcsModelTest = ModelTest::where('slug', 'bcs-preliminary-grand-model-test-01')->first();
        if ($bcsModelTest) {
            $sampleBangla = $allBanglaQuestions->take(35)->pluck('id');
            $bcsModelTest->questions()->syncWithoutDetaching($sampleBangla);
            $bcsModelTest->update([
                'total_questions' => $bcsModelTest->questions()->count(),
                'total_marks' => $bcsModelTest->questions()->count(),
            ]);
        }
    }
}
