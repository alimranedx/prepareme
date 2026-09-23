<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Exam;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\QuestionSource;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamAndModelTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        // ========================================================
        // 1. EXAMS (BCS, Bank, Primary, NTRCA, Non-Cadre)
        // ========================================================
        $bcs = Exam::updateOrCreate(
            ['slug' => 'bcs-preliminary'],
            [
                'name' => 'বিসিএস প্রিলিমিনারি (BCS Preliminary)',
                'category' => 'bcs',
                'description' => 'বাংলাদেশ সিভিল সার্ভিস (বিসিএস) প্রিলিমিনারি পরীক্ষার ২০০ নম্বরের পূর্ণাঙ্গ বিষয়ভিত্তিক সিলেবাস ও প্রশ্নব্যাংক।',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'is_featured' => true,
                'sort_order' => 1,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $bank = Exam::updateOrCreate(
            ['slug' => 'bank-job-recruitment'],
            [
                'name' => 'বাংলাদেশ ব্যাংক ও সমন্বিত সরকারি ব্যাংক নিয়োগ',
                'category' => 'bank',
                'description' => 'বাংলাদেশ ব্যাংক এডি, অফিসার ও সমন্বিত ৮/১০ ব্যাংকের ১০০ নম্বরের প্রিলিমিনারি প্রস্তুতি।',
                'total_marks' => 100,
                'duration_minutes' => 60,
                'is_featured' => true,
                'sort_order' => 2,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $primary = Exam::updateOrCreate(
            ['slug' => 'primary-teacher-recruitment'],
            [
                'name' => 'প্রাথমিক সহকারী শিক্ষক নিয়োগ পরীক্ষা (DPE)',
                'category' => 'primary',
                'description' => 'প্রাথমিক শিক্ষা অধিদপ্তর পরিচালিত সহকারী শিক্ষক পদের ৮০ নম্বরের এমসিকিউ পরীক্ষার সিলেবাস।',
                'total_marks' => 80,
                'duration_minutes' => 60,
                'is_featured' => true,
                'sort_order' => 3,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $ntrca = Exam::updateOrCreate(
            ['slug' => 'ntrca-teacher-registration'],
            [
                'name' => 'শিক্ষক নিবন্ধন পরীক্ষা (NTRCA)',
                'category' => 'ntrca',
                'description' => 'বেসরকারি শিক্ষক নিবন্ধন ও প্রত্যয়ন কর্তৃপক্ষের স্কুল ও কলেজ পর্যায়ের ১০০ নম্বরের প্রিলিমিনারি পরীক্ষা।',
                'total_marks' => 100,
                'duration_minutes' => 60,
                'is_featured' => true,
                'sort_order' => 4,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        // Map Exam Subjects (Marks Distribution)
        $banglaSub = Subject::where('slug', 'bangla')->first();
        $englishSub = Subject::where('slug', 'english')->first();
        $mathSub = Subject::where('slug', 'mathematics')->first();
        $gkSub = Subject::where('slug', 'general-knowledge')->first();
        $ictSub = Subject::where('slug', 'ict')->first();

        if ($banglaSub && $englishSub && $mathSub && $gkSub && $ictSub) {
            // BCS marks distribution
            $bcs->subjects()->syncWithoutDetaching([
                $banglaSub->id => ['marks' => 35, 'sort_order' => 1],
                $englishSub->id => ['marks' => 35, 'sort_order' => 2],
                $gkSub->id => ['marks' => 50, 'sort_order' => 3],
                $mathSub->id => ['marks' => 30, 'sort_order' => 4],
                $ictSub->id => ['marks' => 15, 'sort_order' => 5],
            ]);

            // Bank marks distribution
            $bank->subjects()->syncWithoutDetaching([
                $englishSub->id => ['marks' => 30, 'sort_order' => 1],
                $mathSub->id => ['marks' => 30, 'sort_order' => 2],
                $banglaSub->id => ['marks' => 15, 'sort_order' => 3],
                $gkSub->id => ['marks' => 15, 'sort_order' => 4],
                $ictSub->id => ['marks' => 10, 'sort_order' => 5],
            ]);

            // Primary marks distribution
            $primary->subjects()->syncWithoutDetaching([
                $banglaSub->id => ['marks' => 20, 'sort_order' => 1],
                $englishSub->id => ['marks' => 20, 'sort_order' => 2],
                $mathSub->id => ['marks' => 20, 'sort_order' => 3],
                $gkSub->id => ['marks' => 20, 'sort_order' => 4],
            ]);
        }

        // ========================================================
        // 2. QUESTION SOURCES (PREVIOUS YEARS EXAMS)
        // ========================================================
        $source46 = QuestionSource::updateOrCreate(
            ['slug' => '46th-bcs-preliminary'],
            [
                'name' => '৪৬তম বিসিএস প্রিলিমিনারি পরীক্ষা (2024)',
                'exam_id' => $bcs->id,
                'year' => 2024,
                'exam_date' => '2024-04-26',
                'total_questions' => 200,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $source45 = QuestionSource::updateOrCreate(
            ['slug' => '45th-bcs-preliminary'],
            [
                'name' => '৪৫তম বিসিএস প্রিলিমিনারি পরীক্ষা (2023)',
                'exam_id' => $bcs->id,
                'year' => 2023,
                'exam_date' => '2023-05-19',
                'total_questions' => 200,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $sourceBB = QuestionSource::updateOrCreate(
            ['slug' => 'bangladesh-bank-ad-2023'],
            [
                'name' => 'বাংলাদেশ ব্যাংক সহকারী পরিচালক (AD) ২০২৩',
                'exam_id' => $bank->id,
                'year' => 2023,
                'total_questions' => 100,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $sourcePrimary = QuestionSource::updateOrCreate(
            ['slug' => 'primary-teacher-2023'],
            [
                'name' => 'প্রাথমিক সহকারী শিক্ষক নিয়োগ ২০২৩ (১ম ধাপ)',
                'exam_id' => $primary->id,
                'year' => 2023,
                'total_questions' => 80,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        // Attach questions to sources & exams
        $allQuestions = PublicQuestion::all();
        foreach ($allQuestions as $index => $q) {
            if ($index % 3 === 0) {
                $q->update(['source_id' => $source45->id]);
                $q->exams()->syncWithoutDetaching([$bcs->id]);
            } elseif ($index % 3 === 1) {
                $q->update(['source_id' => $sourceBB->id]);
                $q->exams()->syncWithoutDetaching([$bank->id]);
            } else {
                $q->update(['source_id' => $sourcePrimary->id]);
                $q->exams()->syncWithoutDetaching([$primary->id]);
            }

            // Populate normalized question_options if empty
            if ($q->optionsList()->count() === 0 && !empty($q->options)) {
                $order = 1;
                foreach ($q->options as $key => $text) {
                    $q->optionsList()->create([
                        'option_key' => $key,
                        'option_text' => $text,
                        'is_correct' => ($key === $q->correct_option),
                        'sort_order' => $order++,
                    ]);
                }
            }
        }

        // ========================================================
        // 3. MODEL TESTS
        // ========================================================
        $bcsModelTest = ModelTest::updateOrCreate(
            ['slug' => 'bcs-preliminary-grand-model-test-01'],
            [
                'title' => 'বিসিএস প্রিলিমিনারি পূর্ণাঙ্গ মডেল টেস্ট – ০১',
                'exam_id' => $bcs->id,
                'model_test_type' => 'full_exam',
                'description' => 'সর্বশেষ বিসিএস সিলেবাস ও প্রশ্নের প্যাটার্ন অনুযায়ী তৈরি পূর্ণাঙ্গ মডেল টেস্ট। নেগেটিভ মার্কিং ০.৫০।',
                'total_questions' => min($allQuestions->count(), 100),
                'total_marks' => 100.00,
                'pass_marks' => 50.00,
                'duration_minutes' => 60,
                'negative_marking_rate' => 0.50,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        $bankModelTest = ModelTest::updateOrCreate(
            ['slug' => 'bank-recruitment-special-model-test-01'],
            [
                'title' => 'ব্যাংক নিয়োগ স্পেশাল মডেল টেস্ট – ০১',
                'exam_id' => $bank->id,
                'model_test_type' => 'full_exam',
                'description' => 'ইংরেজি ও গণিত প্রধান সমন্বিত সরকারি ব্যাংক নিয়োগ পরীক্ষার স্পেশাল মডেল টেস্ট।',
                'total_questions' => min($allQuestions->count(), 50),
                'total_marks' => 50.00,
                'pass_marks' => 25.00,
                'duration_minutes' => 45,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        $banglaModelTest = ModelTest::updateOrCreate(
            ['slug' => 'bangla-subject-wise-model-test-01'],
            [
                'title' => 'বাংলা ভাষা ও সাহিত্য বিষয়ভিত্তিক টেস্ট – ০১',
                'subject_id' => $banglaSub?->id,
                'model_test_type' => 'subject_wise',
                'description' => 'বাংলা সাহিত্য ও ব্যাকরণের গুরুত্বপূর্ণ ৩৫টি প্রশ্নের বিষয়ভিত্তিক অনুশীলন টেস্ট।',
                'total_questions' => 25,
                'total_marks' => 25.00,
                'pass_marks' => 12.50,
                'duration_minutes' => 25,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        // Attach questions to model tests
        $bcsQuestions = PublicQuestion::take(25)->pluck('id');
        $bcsModelTest->questions()->syncWithoutDetaching($bcsQuestions);

        $bankQuestions = PublicQuestion::whereIn('subject_id', [$englishSub?->id, $mathSub?->id, $ictSub?->id])->pluck('id');
        if ($bankQuestions->isNotEmpty()) {
            $bankModelTest->questions()->syncWithoutDetaching($bankQuestions);
        }

        $banglaQuestions = PublicQuestion::where('subject_id', $banglaSub?->id)->pluck('id');
        if ($banglaQuestions->isNotEmpty()) {
            $banglaModelTest->questions()->syncWithoutDetaching($banglaQuestions);
        }
    }
}
