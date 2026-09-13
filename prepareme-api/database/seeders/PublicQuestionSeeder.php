<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Models\PublicQuestion;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class PublicQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        $bangla = Subject::where('slug', 'bangla')->first();
        $english = Subject::where('slug', 'english')->first();
        $math = Subject::where('slug', 'mathematics')->first();
        $gk = Subject::where('slug', 'general-knowledge')->first();
        $ict = Subject::where('slug', 'ict')->first();

        $sandhi = Topic::where('slug', 'sandhi')->first();
        $verbs = Topic::where('slug', 'right-form-of-verbs')->first();
        $percentage = Topic::where('slug', 'percentage-and-profit-loss')->first();
        $constitution = Topic::where('slug', 'constitution-of-bangladesh')->first();
        $networking = Topic::where('slug', 'computer-networking')->first();

        $questions = [
            // Bangla
            [
                'subject_id' => $bangla->id,
                'topic_id' => $sandhi?->id,
                'question' => "নিচের কোনটি নিপাতনে সিদ্ধ সন্ধির উদাহরণ?",
                'answer' => "বনস্পতি",
                'explanation' => "যেসব সন্ধি কোনো ব্যাকরণগত সাধারণ নিয়মের অধীনে পড়ে না, সেগুলোকে নিপাতনে সিদ্ধ সন্ধি বলে। যেমন: বন + পতি = বনস্পতি, তৎ + কর = তস্কর, আ + চর্য = আশ্চর্য।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'বিদ্যালয়',
                    'B' => 'বনস্পতি',
                    'C' => 'হিমালয়',
                    'D' => 'সূর্যোদয়',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $bangla->id,
                'topic_id' => $sandhi?->id,
                'question' => "'গায়ক' শব্দের সঠিক সন্ধি বিচ্ছেদ কোনটি?",
                'answer' => "গৈ + অক",
                'explanation' => "ঐ-কারের পর অ-কার থাকলে উভয় মিলে 'আয়' হয়। তাই গৈ + অক = গায়ক, নৈ + অক = নায়ক।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'গা + অক',
                    'B' => 'গাই + অক',
                    'C' => 'গৈ + অক',
                    'D' => 'গো + অক',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],

            // English
            [
                'subject_id' => $english->id,
                'topic_id' => $verbs?->id,
                'question' => "Choose the correct sentence regarding Subject-Verb Agreement:",
                'answer' => "Neither the teacher nor the students were present.",
                'explanation' => "When subjects are connected by 'neither... nor' or 'either... or', the verb agrees with the subject closest to it. Here 'the students' is plural, so 'were' is correct.",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'Neither the teacher nor the students was present.',
                    'B' => 'Neither the teacher nor the students were present.',
                    'C' => 'Neither the teacher nor the students is present.',
                    'D' => 'Neither the teacher nor the students has been present.',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $english->id,
                'topic_id' => $verbs?->id,
                'question' => "What is the synonym of the word 'Prolific'?",
                'answer' => "Productive",
                'explanation' => "'Prolific' means producing much fruit or foliage or many works of art, literature. Its closest synonym is 'Productive' or 'Fruitful'.",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'Barren',
                    'B' => 'Unproductive',
                    'C' => 'Productive',
                    'D' => 'Scarce',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::HARD,
                'status' => ContentStatus::PUBLISHED,
            ],

            // Math
            [
                'subject_id' => $math->id,
                'topic_id' => $percentage?->id,
                'question' => "একটি পণ্যের দাম ২৫% বৃদ্ধি পেল। পূর্বের দামে ফিরে যেতে হলে বর্তমান দাম শতকরা কত কমাতে হবে?",
                'answer' => "২০%",
                'explanation' => "ধরি পূর্বের দাম ১০০ টাকা। ২৫% বৃদ্ধিতে বর্তমান দাম ১২৫ টাকা।\n১২৫ টাকায় কমাতে হবে ২৫ টাকা।\n১ টাকায় কমাতে হবে ২৫/১২৫ টাকা।\n১০০ টাকায় কমাতে হবে (২৫ × ১০০) / ১২৫ = ২০%।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '২৫%',
                    'B' => '২০%',
                    'C' => '২২.৫%',
                    'D' => '১৮%',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $math->id,
                'topic_id' => $percentage?->id,
                'question' => "টাকায় ৩টি করে লেবু কিনে টাকায় ২টি করে বিক্রি করলে শতকরা কত লাভ হবে?",
                'answer' => "৫০%",
                'explanation' => "৩টি লেবুর ক্রয়মূল্য ১ টাকা, সুতরাং ১টির ক্রয়মূল্য ১/৩ টাকা।\n২টি লেবুর বিক্রয়মূল্য ১ টাকা, সুতরাং ১টির বিক্রয়মূল্য ১/২ টাকা।\nলাভ = (১/২ - ১/৩) = ১/৬ টাকা।\nশতকরা লাভ = ((১/৬) / (১/৩)) × ১০০% = (৩/৬) × ১০০% = ৫০%।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '৩৩.৩৩%',
                    'B' => '৫০%',
                    'C' => '২০%',
                    'D' => '৬০%',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],

            // General Knowledge
            [
                'subject_id' => $gk->id,
                'topic_id' => $constitution?->id,
                'question' => "গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধানের কোন অনুচ্ছেদে 'আইনের দৃষ্টিতে সমতা' নিশ্চিত করা হয়েছে?",
                'answer' => "অনুচ্ছেদ ২৭",
                'explanation' => "সংবিধানের ২৭ নং অনুচ্ছেদ অনুযায়ী: 'সকল নাগরিক আইনের দৃষ্টিতে সমান এবং আইনের সমান আশ্রয় লাভের অধিকারী।' এটি তৃতীয় ভাগে বর্ণিত মৌলিক অধিকারসমূহের অন্যতম প্রধান স্তম্ভ।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'অনুচ্ছেদ ১৯',
                    'B' => 'অনুচ্ছেদ ২৭',
                    'C' => 'অনুচ্ছেদ ৩১',
                    'D' => 'অনুচ্ছেদ ৩৯',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $gk->id,
                'topic_id' => $constitution?->id,
                'question' => "বাংলাদেশের সংবিধান গণপরিষদে কত তারিখে গৃহীত হয়?",
                'answer' => "৪ নভেম্বর ১৯৭২",
                'explanation' => "বাংলাদেশের সংবিধান ১৯৭২ সালের ৪ নভেম্বর গণপরিষদে গৃহীত হয় এবং ওই দিনটিকে 'সংবিধান দিবস' হিসেবে পালন করা হয়। সংবিধান কার্যকর হয় ১৯৭২ সালের ১৬ ডিসেম্বর থেকে।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '২৬ মার্চ ১৯৭২',
                    'B' => '৪ নভেম্বর ১৯৭২',
                    'C' => '১৬ ডিসেম্বর ১৯৭২',
                    'D' => '১০ এপ্রিল ১৯৭২',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],

            // ICT
            [
                'subject_id' => $ict->id,
                'topic_id' => $networking?->id,
                'question' => "OSI রেফারেন্স মডেলের মোট লেয়ার (Layer) সংখ্যা কতটি?",
                'answer' => "৭টি",
                'explanation' => "OSI (Open Systems Interconnection) মডেলে মোট ৭টি লেয়ার রয়েছে:\n১. Physical Layer\n২. Data Link Layer\n৩. Network Layer\n৪. Transport Layer\n৫. Session Layer\n৬. Presentation Layer\n৭. Application Layer",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '৪টি',
                    'B' => '৫টি',
                    'C' => '৭টি',
                    'D' => '৮টি',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $ict->id,
                'topic_id' => $networking?->id,
                'question' => "IPv4 অ্যাড্রেস কত বিটের হয়ে থাকে?",
                'answer' => "৩২ বিট",
                'explanation' => "IPv4 (Internet Protocol version 4) অ্যাড্রেস ৩২ বিটের (৪ বাইট) হয়ে থাকে, যা ডট-ডেসিমেল পদ্ধতিতে লেখা হয়। অপরপক্ষে IPv6 অ্যাড্রেস ১২৮ বিটের হয়ে থাকে।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '১৬ বিট',
                    'B' => '৩২ বিট',
                    'C' => '৬৪ বিট',
                    'D' => '১২৮ বিট',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
        ];

        foreach ($questions as $q) {
            $q['created_by'] = $adminId;
            $q['updated_by'] = $adminId;
            PublicQuestion::create($q);
        }
    }
}
