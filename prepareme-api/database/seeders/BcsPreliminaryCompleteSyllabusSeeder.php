<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Enums\SectionType;
use App\Models\Chapter;
use App\Models\Exam;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\QuestionOption;
use App\Models\QuestionSource;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class BcsPreliminaryCompleteSyllabusSeeder extends Seeder
{
    private function upsertSubject(string $slug, array $data): Subject
    {
        $subject = Subject::withTrashed()->where('slug', $slug)->first();
        if ($subject) {
            $subject->restore();
            $subject->update($data);
            return $subject;
        }
        return Subject::create(array_merge(['slug' => $slug], $data));
    }

    private function upsertChapter(string $slug, array $data): Chapter
    {
        $chapter = Chapter::withTrashed()->where('slug', $slug)->first();
        if ($chapter) {
            $chapter->restore();
            $chapter->update($data);
            return $chapter;
        }
        return Chapter::create(array_merge(['slug' => $slug], $data));
    }

    private function upsertTopic(string $slug, array $data): Topic
    {
        $topic = Topic::withTrashed()->where('slug', $slug)->first();
        if ($topic) {
            $topic->restore();
            $topic->update($data);
            return $topic;
        }
        return Topic::create(array_merge(['slug' => $slug], $data));
    }

    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        // =========================================================================
        // 1. BCS PRELIMINARY EXAM MODEL
        // =========================================================================
        $bcs = Exam::withTrashed()->where('slug', 'bcs-preliminary')->first();
        if ($bcs) {
            $bcs->restore();
            $bcs->update([
                'name' => 'বিসিএস প্রিলিমিনারি (BCS Preliminary)',
                'category' => 'bcs',
                'description' => 'বাংলাদেশ সরকারি কর্ম কমিশন (BPSC) পরিচালিত বিসিএস প্রিলিমিনারি পরীক্ষার ২০০ নম্বরের ১০টি বিষয়ের পূর্ণাঙ্গ অফিসিয়াল সিলেবাস, বিষয়ভিত্তিক অধ্যায়, বিষয়ভিত্তিক স্টাডি গাইড ও প্রশ্নব্যাংক।',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'is_featured' => true,
                'sort_order' => 1,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]);
        } else {
            $bcs = Exam::create([
                'slug' => 'bcs-preliminary',
                'name' => 'বিসিএস প্রিলিমিনারি (BCS Preliminary)',
                'category' => 'bcs',
                'description' => 'বাংলাদেশ সরকারি কর্ম কমিশন (BPSC) পরিচালিত বিসিএস প্রিলিমিনারি পরীক্ষার ২০০ নম্বরের ১০টি বিষয়ের পূর্ণাঙ্গ অফিসিয়াল সিলেবাস, বিষয়ভিত্তিক অধ্যায়, বিষয়ভিত্তিক স্টাডি গাইড ও প্রশ্নব্যাংক।',
                'total_marks' => 200,
                'duration_minutes' => 120,
                'is_featured' => true,
                'sort_order' => 1,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]);
        }

        // =========================================================================
        // 2. THE 10 OFFICIAL BCS PRELIMINARY SUBJECTS (TOTAL 200 MARKS)
        // =========================================================================

        // --- 1. BANGLA LANGUAGE & LITERATURE (35 MARKS) ---
        $bangla = $this->upsertSubject('bangla', [
            'name' => 'বাংলা ভাষা ও সাহিত্য',
            'description' => 'বাংলা সাহিত্য (২০ নম্বর) ও বাংলা ব্যাকরণ নির্মিতি (১৫ নম্বর) সহ মোট ৩৫ নম্বরের পূর্ণাঙ্গ প্রস্তুতি।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $chBanglaLit = $this->upsertChapter('bangla-literature-chapter', [
            'subject_id' => $bangla->id,
            'name' => 'বাংলা সাহিত্য (প্রাচীন, মধ্য ও আধুনিক যুগ)',
            'description' => 'চর্যাপদ, মঙ্গলকাব্য, বৈষ্ণব পদাবলী, শ্রীকৃষ্ণকীর্তন, মহাকাব্য, রবীন্দ্র-নজরুল ও আধুনিক যুগ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $chBanglaGrammar = $this->upsertChapter('bangla-grammar-chapter', [
            'subject_id' => $bangla->id,
            'name' => 'বাংলা ব্যাকরণ ও নির্মিতি',
            'description' => 'ধ্বনি, বর্ণ, শব্দ, সমাস, সন্ধি, কারক-বিভক্তি, উপসর্গ, নত্ব-ষত্ব বিধান ও বাগধারা।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'created_by' => $adminId,
        ]);

        $tCharya = $this->upsertTopic('ancient-era-charyapada', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaLit->id,
            'name' => 'প্রাচীন যুগ: চর্যাপদ ও আবিষ্কারের ইতিহাস',
            'description' => 'চর্যাপদের আবিষ্কার (১৯০৭), হরপ্রসাদ শাস্ত্রী, কালক্রম, পদকর্তাগণ, ভাষা ও ব্যাকরণগত বৈশিষ্ট্য।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tMedieval = $this->upsertTopic('medieval-literature-mangalkavya', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaLit->id,
            'name' => 'মধ্যযুগ: মঙ্গলকাব্য, বৈষ্ণব পদাবলী ও শ্রীকৃষ্ণকীর্তন',
            'description' => 'শ্রীকৃষ্ণকীর্তন কাব্য, মঙ্গলকাব্যের প্রধান কবিগণ (মুকুন্দরাম, ভারতচন্দ্র), প্রণয়োপাকখ্যান ও আরাকান রাজসভা।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tModernPoets = $this->upsertTopic('modern-poets-and-authors', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaLit->id,
            'name' => 'আধুনিক যুগ: মাইকেল, বঙ্কিম, ঈশ্বরচন্দ্র ও মীর মশাররফ',
            'description' => 'বাংলা গদ্যের জনক, প্রথম মহাকাব্য, প্রথম বাংলা উপন্যাস ও আধুনিক নাট্যকারদের সৃষ্টি।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 3,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tRabindraNazrul = $this->upsertTopic('rabindranath-and-kazi-nazrul', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaLit->id,
            'name' => 'বিশ্বকবি রবীন্দ্রনাথ ঠাকুর ও জাতীয় কবি কাজী নজরুল ইসলাম',
            'description' => 'রবীন্দ্রনাথের নোবেল বিজয়, কাব্য, ছোটগল্প, নাটক; নজরুলের বিদ্রোহী কাব্য, পত্রিকা ও গ্রন্থাবলী।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 4,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tGrammarSound = $this->upsertTopic('bangla-phonetics-and-orthography', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaGrammar->id,
            'name' => 'ধ্বনি, বর্ণ, উচ্চারণ স্থান ও নত্ব-ষত্ব বিধান',
            'description' => 'স্বরধ্বনি, ব্যঞ্জনধ্বনি, উচ্চারণ স্থানভিত্তিক নাম এবং ণ-ত্ব ও ষ-ত্ব বিধানের প্রধান নিয়মসমূহ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tSamas = $this->upsertTopic('samas-and-shobdo', [
            'subject_id' => $bangla->id,
            'chapter_id' => $chBanglaGrammar->id,
            'name' => 'সমাস নির্ণয়, প্রকারভেদ ও শব্দ গঠন',
            'description' => 'দ্বন্দ্ব, কর্মধারয়, তৎপুরুষ, বহুব্রীহি, দ্বিগু ও অব্যয়ীভাব সমাস চেনার শর্টকাট কৌশল।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 2. ENGLISH LANGUAGE & LITERATURE (35 MARKS) ---
        $english = $this->upsertSubject('english', [
            'name' => 'English Language & Literature',
            'description' => 'Comprehensive preparation for English Literature (15 Marks) & English Grammar (20 Marks).',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'created_by' => $adminId,
        ]);

        $chEngLit = $this->upsertChapter('english-literature-chapter', [
            'subject_id' => $english->id,
            'name' => 'English Literature',
            'description' => 'Literary periods (Elizabethan, Romantic, Victorian, Modern), major authors, masterworks and famous quotes.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $chEngGrammar = $this->upsertChapter('english-grammar-chapter', [
            'subject_id' => $english->id,
            'name' => 'English Grammar & Syntax',
            'description' => 'Parts of speech, right form of verbs, subject-verb agreement, voice, narration, prepositions and clauses.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'created_by' => $adminId,
        ]);

        $tShakespeare = $this->upsertTopic('william-shakespeare-and-dramatists', [
            'subject_id' => $english->id,
            'chapter_id' => $chEngLit->id,
            'name' => 'William Shakespeare & Elizabethan Dramatists',
            'description' => 'Tragedies (Hamlet, Macbeth, Othello, King Lear), Comedies, Sonnets & Famous Soliloquies.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tRomanticPoets = $this->upsertTopic('romantic-and-victorian-poets', [
            'subject_id' => $english->id,
            'chapter_id' => $chEngLit->id,
            'name' => 'Romantic & Victorian Literary Periods',
            'description' => 'William Wordsworth, S.T. Coleridge, John Keats, P.B. Shelley, Lord Byron & Lord Tennyson.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tPartsOfSpeech = $this->upsertTopic('parts-of-speech-and-gerund', [
            'subject_id' => $english->id,
            'chapter_id' => $chEngGrammar->id,
            'name' => 'Parts of Speech Identification & Gerund vs Participle',
            'description' => 'Noun, Adjective, Adverb identification, Interchange of parts of speech, Gerund & Participle distinctions.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tSubjectVerb = $this->upsertTopic('subject-verb-agreement-rules', [
            'subject_id' => $english->id,
            'chapter_id' => $chEngGrammar->id,
            'name' => 'Subject-Verb Agreement & Right Form of Verbs',
            'description' => 'Essential rules of Subject-Verb Agreement frequently tested in BCS Preliminary examinations.',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 3. BANGLADESH AFFAIRS (30 MARKS) ---
        $bdAffairs = $this->upsertSubject('bangladesh-affairs', [
            'name' => 'বাংলাদেশ বিষয়াবলী (Bangladesh Affairs)',
            'description' => 'বাংলাদেশের ইতিহাস, মুক্তিযুদ্ধ, সংবিধান, রাজনীতি, অর্থনীতি, মেগা প্রকল্প ও জাতীয় অর্জন (৩০ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 3,
            'created_by' => $adminId,
        ]);

        $chBdHistory = $this->upsertChapter('bangladesh-history-and-liberation-war', [
            'subject_id' => $bdAffairs->id,
            'name' => 'ইতিহাস, ১৯৫২ ভাষা আন্দোলন ও ১৯৭১ সালের মুক্তিযুদ্ধ',
            'description' => 'প্রাচীন বাংলা, ১৯৫২ ভাষা আন্দোলন, ১৯৬৬ সালের ৬-দফা, মুজিবনগর সরকার, ১১টি সেক্টর ও ৭ বীরশ্রেষ্ঠ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $chBdConst = $this->upsertChapter('constitution-and-governance-of-bangladesh', [
            'subject_id' => $bdAffairs->id,
            'name' => 'গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান ও সরকার ব্যবস্থা',
            'description' => 'সংবিধান প্রণয়ন, মূলনীতি, মৌলিক অধিকার, গুরুত্বপূর্ণ অনুচ্ছেদসমূহ ও সংশোধনীসমূহ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 2,
            'created_by' => $adminId,
        ]);

        $tLibWarBd = $this->upsertTopic('liberation-war-1971-details', [
            'subject_id' => $bdAffairs->id,
            'chapter_id' => $chBdHistory->id,
            'name' => '১৯৭১ সালের মুক্তিযুদ্ধ, মুজিবনগর সরকার ও বীরশ্রেষ্ঠগণ',
            'description' => '৭ই মার্চের ভাষণ, গণহত্যা, ১৭ই এপ্রিল মুজিবনগর সরকার গঠন, ১১টি সেক্টর ও ৭ জন বীরশ্রেষ্ঠের অবদান।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);

        $tConstBd = $this->upsertTopic('bangladesh-constitution-articles', [
            'subject_id' => $bdAffairs->id,
            'chapter_id' => $chBdConst->id,
            'name' => 'বাংলাদেশের সংবিধান: মৌলিক অধিকার ও গুরুত্বপূর্ণ অনুচ্ছেদ',
            'description' => 'সংবিধানের ১৫৩টি অনুচ্ছেদ, ১১টি ভাগ, ৪টি মূলনীতি ও গুরুত্বপূর্ণ সংশোধনীসমূহ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 4. INTERNATIONAL AFFAIRS (20 MARKS) ---
        $intlAffairs = $this->upsertSubject('international-affairs', [
            'name' => 'আন্তর্জাতিক বিষয়াবলী (International Affairs)',
            'description' => 'বৈশ্বিক ইতিহাস, ভূ-রাজনীতি, জাতিসংঘ, বিশ্বব্যাংক, চুক্তি, পরিবেশ কূটনীতি ও আন্তর্জাতিক সংস্থাসমূহ (২০ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 4,
            'created_by' => $adminId,
        ]);

        $chIntlOrg = $this->upsertChapter('international-organizations-and-alliances', [
            'subject_id' => $intlAffairs->id,
            'name' => 'জাতিসংঘ, আঞ্চলিক জোট ও বৈশ্বিক সংস্থাসমূহ',
            'description' => 'জাতিসংঘ (UN), বিশ্বব্যাংক, IMF, BRICS, NATO, SAARC, ASEAN, EU ও আন্তর্জাতিক সদর দপ্তরসমূহ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tUN = $this->upsertTopic('united-nations-and-specialized-agencies', [
            'subject_id' => $intlAffairs->id,
            'chapter_id' => $chIntlOrg->id,
            'name' => 'জাতিসংঘ (UN) ও তার বিশেষায়িত সংস্থাসমূহ',
            'description' => 'সাধারণ পরিষদ, নিরাপত্তা পরিষদ, UNHCR, UNICEF, UNESCO, WHO ও আন্তর্জাতিক আদালত (ICJ)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 5. MATHEMATICAL REASONING (15 MARKS) ---
        $math = $this->upsertSubject('mathematics', [
            'name' => 'গাণিতিক যুক্তি (Mathematical Reasoning)',
            'description' => 'পাটিগণিত, বীজগণিত, জ্যামিতি, সেট, সূচক-লগারিদম, সমান্তর ধারা ও সম্ভাব্যতা (১৫ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 5,
            'created_by' => $adminId,
        ]);

        $chAlgebra = $this->upsertChapter('algebra-and-series', [
            'subject_id' => $math->id,
            'name' => 'বীজগণিত, সূচক, লগারিদম ও ধারা',
            'description' => 'বীজগাণিতিক সূত্রাবলি, উৎপাদক, সূচক ও লগারিদম, সমান্তর ও গুণোত্তর ধারা।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tLogarithm = $this->upsertTopic('indices-and-logarithms-math', [
            'subject_id' => $math->id,
            'chapter_id' => $chAlgebra->id,
            'name' => 'সূচক ও লগারিদম (Indices & Logarithms)',
            'description' => 'সূচকের ধর্মাবলি এবং লগারিদমের মান নির্ণয়ের শর্টকাট সূত্রাবলি।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 6. MENTAL ABILITY (15 MARKS) ---
        $mental = $this->upsertSubject('mental-ability', [
            'name' => 'মানসিক দক্ষতা (Mental Ability & Logic)',
            'description' => 'ভাষাগত যুক্তি, সংখ্যাগত ধারা, দিক নির্ণয়, রক্তের সম্পর্ক, ঘড়ি-ক্যালেন্ডার ও চিত্রভিত্তিক যুক্তি (১৫ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 6,
            'created_by' => $adminId,
        ]);

        $chMentalLogic = $this->upsertChapter('verbal-and-non-verbal-reasoning', [
            'subject_id' => $mental->id,
            'name' => 'ভার্বাল ও নন-ভার্বাল লজিক্যাল রিজনিং',
            'description' => 'সংখ্যা ও বর্ণভিত্তিক ধারা, দিক নির্ণয়, রক্তের সম্পর্ক, আয়না প্রতিবিম্ব ও ঘড়ি-ক্যালেন্ডার।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tMentalSeries = $this->upsertTopic('mental-ability-series-and-direction', [
            'subject_id' => $mental->id,
            'chapter_id' => $chMentalLogic->id,
            'name' => 'সংখ্যা ও বর্ণভিত্তিক ধারা এবং দিক নির্ণয় কৌশল',
            'description' => 'লজিক্যাল ধারা সমাধান, কম্পাস দিক নির্ণয় এবং আত্মীয়তার সম্পর্ক বের করার নিয়ম।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 7. GENERAL SCIENCE (15 MARKS) ---
        $science = $this->upsertSubject('general-science', [
            'name' => 'সাধারণ বিজ্ঞান (General Science)',
            'description' => 'ভৌত বিজ্ঞান (৫ নম্বর), জীববিজ্ঞান (৫ নম্বর) ও আধুনিক বিজ্ঞান ও রসায়ন (৫ নম্বর) সহ মোট ১৫ নম্বর।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 7,
            'created_by' => $adminId,
        ]);

        $chBioHealth = $this->upsertChapter('biology-and-health-science', [
            'subject_id' => $science->id,
            'name' => 'জীববিজ্ঞান, মানবদেহ ও সংক্রামক ব্যাধি',
            'description' => 'রক্ত সংবহনতন্ত্র, পুষ্টিবিজ্ঞান, ভিটামিনের কাজ ও অভাবজনিত রোগ, ভাইরাস ও ব্যাকটেরিয়া।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tBioTopic = $this->upsertTopic('human-body-and-nutrition', [
            'subject_id' => $science->id,
            'chapter_id' => $chBioHealth->id,
            'name' => 'মানবদেহ, পুষ্টিবিজ্ঞান ও ভিটামিনের উৎস',
            'description' => 'রক্তের গ্রুপ, হৃদপিণ্ড, খাদ্য ও পুষ্টি, ভিটামিন A, B, C, D, E, K এবং অভাবজনিত রোগব্যাধি।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 8. COMPUTER & ICT (15 MARKS) ---
        $ict = $this->upsertSubject('ict', [
            'name' => 'কম্পিউটার ও তথ্য প্রযুক্তি (Computer & ICT)',
            'description' => 'কম্পিউটার প্রযুক্তি (১০ নম্বর) ও তথ্য প্রযুক্তি-নেটওয়ার্কিং (৫ নম্বর) সহ মোট ১৫ নম্বর।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 8,
            'created_by' => $adminId,
        ]);

        $chHardware = $this->upsertChapter('computer-hardware-and-architecture', [
            'subject_id' => $ict->id,
            'name' => 'কম্পিউটার হার্ডওয়্যার, মেমরি ও আর্কিটেকচার',
            'description' => 'CPU, ইনপুট-আউটপুট ডিভাইস, RAM vs ROM, ক্যাশ মেমরি ও নম্বর সিস্টেম (বাইনারি, হেক্সাডেসিমাল)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tCpuMemory = $this->upsertTopic('cpu-memory-and-number-systems', [
            'subject_id' => $ict->id,
            'chapter_id' => $chHardware->id,
            'name' => 'CPU, মেমরি (RAM/ROM) ও রূপান্তর নম্বর সিস্টেম',
            'description' => 'আরিথমেটিক লজিক ইউনিট (ALU), রেজিস্টার, প্রাইমারি ও সেকেন্ডারি মেমরি এবং বাইনারি হিসাব।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 9. GEOGRAPHY, ENVIRONMENT & DISASTER MANAGEMENT (10 MARKS) ---
        $geography = $this->upsertSubject('geography-environment', [
            'name' => 'ভূগোল, পরিবেশ ও দুর্যোগ ব্যবস্থাপনা',
            'description' => 'বাংলাদেশের ভূ-প্রকৃতি, পরিবেশবিদ্যা, জলবায়ু পরিবর্তন ও প্রাকৃতিক দুর্যোগ ব্যবস্থাপনা (১০ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 9,
            'created_by' => $adminId,
        ]);

        $chGeoEnv = $this->upsertChapter('bangladesh-geography-and-disaster-management', [
            'subject_id' => $geography->id,
            'name' => 'ভূ-প্রকৃতি, পরিবেশ ও দুর্যোগ ব্যবস্থাপনা কৌশল',
            'description' => 'বাংলাদেশের ভূ-প্রাকৃতিক অঞ্চল, নদ-নদী, সাশার ভূ-প্রকৃতি, সাইক্লোন, ভূকম্পন ও দুর্যোগ ব্যবস্থাপনা।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tGeoTopic = $this->upsertTopic('bangladesh-physical-geography-and-disasters', [
            'subject_id' => $geography->id,
            'chapter_id' => $chGeoEnv->id,
            'name' => 'বাংলাদেশের ভূ-প্রকৃতি, জলবায়ু ও দুর্যোগ ব্যবস্থাপনা',
            'description' => 'টারশিয়ারি, প্লাইস্টোসিন ও সাম্প্রতিক সমভূমি, নদ-নদীর উৎপত্তি, ঘূর্ণিঝড় ও পূর্বাভাস কৌশল।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // --- 10. ETHICS, VALUES & GOOD GOVERNANCE (10 MARKS) ---
        $ethics = $this->upsertSubject('ethics-governance', [
            'name' => 'নৈতিকতা, মূল্যবোধ ও সুশাসন',
            'description' => 'নৈতিকতার ধারণা, মূল্যবোধের ধারণা ও বিকাশ, সুশাসনের উপাদান ও ই-গভর্ন্যান্স (১০ নম্বর)।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 10,
            'created_by' => $adminId,
        ]);

        $chEthicsGov = $this->upsertChapter('ethics-values-and-governance-concept', [
            'subject_id' => $ethics->id,
            'name' => 'নৈতিকতা, সামাজিক মূল্যবোধ ও সুশাসন',
            'description' => 'নৈতিকতার মূল উপাদান, সুশাসনের আটটি স্তম্ভ (UNDP/World Bank), জবাবদিহিতা ও ই-গভর্ন্যান্স।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'created_by' => $adminId,
        ]);

        $tEthicsTopic = $this->upsertTopic('concept-of-ethics-and-good-governance', [
            'subject_id' => $ethics->id,
            'chapter_id' => $chEthicsGov->id,
            'name' => 'নৈতিকতা, মূল্যবোধের উপাদান ও সুশাসনের সংজ্ঞায়ন',
            'description' => 'বিশ্বব্যাংক ও জাতিসংঘ কর্তৃক সুশাসনের সংজ্ঞা, স্বচ্ছতা, আইনের শাসন ও নাগরিক মূল্যবোধ।',
            'status' => ContentStatus::PUBLISHED,
            'sort_order' => 1,
            'is_high_yield' => true,
            'created_by' => $adminId,
        ]);


        // =========================================================================
        // 3. MAP ALL 10 SUBJECTS TO BCS PRELIMINARY WITH EXACT OFFICIAL MARKS (200)
        // =========================================================================
        $bcs->subjects()->sync([
            $bangla->id => ['marks' => 35, 'sort_order' => 1],
            $english->id => ['marks' => 35, 'sort_order' => 2],
            $bdAffairs->id => ['marks' => 30, 'sort_order' => 3],
            $intlAffairs->id => ['marks' => 20, 'sort_order' => 4],
            $math->id => ['marks' => 15, 'sort_order' => 5],
            $mental->id => ['marks' => 15, 'sort_order' => 6],
            $science->id => ['marks' => 15, 'sort_order' => 7],
            $ict->id => ['marks' => 15, 'sort_order' => 8],
            $geography->id => ['marks' => 10, 'sort_order' => 9],
            $ethics->id => ['marks' => 10, 'sort_order' => 10],
        ]);


        // =========================================================================
        // 4. CREATE HIGH-QUALITY STUDY GUIDES FOR BCS TOPICS
        // =========================================================================

        // --- Study Guide for Ancient Era / Charyapada ---
        $sgCharya = StudyGuide::updateOrCreate(
            ['slug' => 'ancient-era-charyapada-comprehensive-guide'],
            [
                'topic_id' => $tCharya->id,
                'title' => 'প্রাচীন যুগ: চর্যাপদ আবিষ্কার, পদকর্তা ও সাহিত্যিক বৈশিষ্ট্য',
                'summary' => 'বিসিএস প্রিলিমিনারি পরীক্ষায় চর্যাপদ থেকে নিশ্চিত ২-৩ নম্বরের প্রশ্ন থাকে। আবিষ্কারের ইতিহাস, আবিষ্কারক, টীকাকার ও কবিদের বিস্তারিত গাইড।',
                'content' => 'চর্যাপদ হলো বাংলা সাহিত্যের প্রাচীনতম লিখিত নিদর্শন। মহামহোপাধ্যায় হরপ্রসাদ শাস্ত্রী ১৯০৭ সালে নেপালের রাজদরবারের গ্রন্থাগার (রয়েল লাইব্রেরি) থেকে চর্যাচর্যাবিনিশ্চয় নামক পুথি আবিষ্কার করেন। ১৯১৬ সালে বঙ্গীয় সাহিত্য পরিষদ থেকে "হাজার বছরের পুরাণ বাঙ্গালা ভাষায় রচিত বৌদ্ধ গান ও দোহা" নামে এটি গ্রন্থাকারে প্রকাশিত হয়।',
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        $sgCharya->sections()->delete();
        $sgCharya->sections()->createMany([
            [
                'title' => 'চর্যাপদের আবিষ্কার ও প্রকাশনা সংক্ষেপ',
                'content' => '১. আবিষ্কারক: হরপ্রসাদ শাস্ত্রী (১৯০৭ সালে নেপাল থেকে)।
২. প্রকাশনা: বঙ্গীয় সাহিত্য পরিষদ (১৯১৬ সালে)।
৩. মূল পুঁথির নাম: চর্যাচর্যাবিনিশ্চয়।
৪. মোট পদ সংখ্যা: সাড়ে ছেচল্লিশটি (৪৬.৫টি)। ৩টি সম্পূর্ণ (২৪, ২৫, ৪৮) ও ১টি পদের শেষাংশ (২৩) পাওয়া যায়নি।',
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => 'প্রধান পদকর্তাগণ ও স্মরণ রাখার টেকনিক',
                'content' => 'চর্যাপদের মোট পদকর্তা ২৪ জন (মুনীদত্তের মতে ২৩ জন)।
• লুইপা: চর্যাপদের আদি কবি (প্রথম পদের রচয়িতা "কাআ তরুবর পঞ্চ বি ডাল")।
• কাহ্নপা: চর্যাপদের সর্বাধিক পদ রচয়িতা (১৩টি পদ)।
• ভুসুকুপা: নিজেকে "বাঙালি" বলে পরিচয় দিয়েছেন ("আজি ভুসুকু বঙ্গালী ভইলী")। মোট ৮টি পদ রচনা করেছেন।
• শবরপা: আদি ও প্রধান বাঙালি কবিদের অন্যতম।',
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);


        // --- Study Guide for UN & International Orgs ---
        $sgUN = StudyGuide::updateOrCreate(
            ['slug' => 'united-nations-and-specialized-agencies-guide'],
            [
                'topic_id' => $tUN->id,
                'title' => 'জাতিসংঘ (UN) ও প্রধান আন্তর্জাতিক সংস্থাসমূহ: সম্পূর্ণ মাস্টার গাইড',
                'summary' => 'বিসিএস প্রিলিমিনারিতে জাতিসংঘ ও আন্তর্জাতিক সংস্থাসমূহ থেকে প্রতি বছর ৩-৪টি প্রশ্ন আসে। জাতিসংঘের ৬টি অঙ্গ, বিশেষায়িত সংস্থা ও সদর দপ্তর।',
                'content' => 'জাতিসংঘ হলো দ্বিতীয় বিশ্বযুদ্ধের পর বিশ্বশান্তি ও নিরাপত্তা নিশ্চিত করার উদ্দেশ্যে ১৯৪৫ সালের ২৪ অক্টোবর প্রতিষ্ঠিত সর্ববৃহৎ আন্তর্জাতিক সংস্থা। সান ফ্রান্সিসকো সম্মেলনে ৫১টি সদস্য রাষ্ট্র নিয়ে জাতিসংঘ প্রতিষ্ঠিত হয়। বর্তমান সদস্য সংখ্যা ১৯৩।',
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        $sgUN->sections()->delete();
        $sgUN->sections()->createMany([
            [
                'title' => 'জাতিসংঘের ৬টি প্রধান অঙ্গসংস্থা',
                'content' => '১. সাধারণ পরিষদ (General Assembly): সদর দপ্তর নিউইয়র্ক।
২. নিরাপত্তা পরিষদ (Security Council): ৫টি স্থায়ী সদস্য (P5: যুক্তরাষ্ট্র, যুক্তরাজ্য, ফ্রান্স, রাশিয়া, চীন) ও ১০টি অস্থায়ী সদস্য।
৩. অর্থনৈতিক ও সামাজিক পরিষদ (ECOSOC): ৫৪টি সদস্য রাষ্ট্র।
৪. আন্তর্জাতিক আদালত (ICJ): সদর দপ্তর দ্য হেগ, নেদারল্যান্ডস (বিচারক ১৫ জন, মেয়াদ ৯ বছর)।
৫. ওফাত পরিষদ (Trusteeship Council): ১৯৯৪ সাল থেকে স্থগিত।
৬. সচিবালয় (Secretariat): প্রধান মহাসচিব (বর্তমান: আন্তোনিও গুতেরেস)।',
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
        ]);


        // --- Study Guide for Constitution of Bangladesh ---
        $sgConst = StudyGuide::updateOrCreate(
            ['slug' => 'bangladesh-constitution-master-guide'],
            [
                'topic_id' => $tConstBd->id,
                'title' => 'বাংলাদেশের সংবিধান: মৌলিক অধিকার ও গুরুত্বপূর্ণ অনুচ্ছেদসমূহ',
                'summary' => 'বিসিএস প্রিলিমিনারি পরীক্ষার জন্য বাংলাদেশের সংবিধানের গুরুত্বপূর্ণ অনুচ্ছেদ, ১৭টি সংশোধনী ও ৪টি মূলনীতির বিস্তারিত আলোচনা।',
                'content' => '১৯৭২ সালের ৪ নভেম্বর গণপরিষদে বাংলাদেশের সংবিধান গৃহীত হয় এবং ১৬ ডিসেম্বর ১৯৭২ থেকে কার্যকর হয়। সংবিধানে মোট ১৫৩টি অনুচ্ছেদ, ১১টি ভাগ, ১টি প্রস্তাবনা ও ৭টি তফসিল রয়েছে।',
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        $sgConst->sections()->delete();
        $sgConst->sections()->createMany([
            [
                'title' => 'সংবিধানের ৪টি রাষ্ট্রীয় মূলনীতি (অনুচ্ছেদ ৮)',
                'content' => '১. জাতীয়তাবাদ (অনুচ্ছেদ ৯)
২. সমাজতন্ত্র ও শোষণমুক্তি (অনুচ্ছেদ ১০)
৩. গণতন্ত্র ও মানবাধিকার (অনুচ্ছেদ ১১)
৪. ধর্মনিরপেক্ষতা ও ধর্মীয় স্বাধীনতা (অনুচ্ছেদ ১২)',
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => 'পরীক্ষায় বারবার আসা প্রধান অনুচ্ছেদসমূহ',
                'content' => '• অনুচ্ছেদ ৭: সংবিধানের প্রাধান্য।
• অনুচ্ছেদ ৭(ক): সংবিধান বাতিল, স্থগিতকরণ ইত্যাদি অপরাধ।
• অনুচ্ছেদ ১১: গণতন্ত্র ও মানবাধিকার।
• অনুচ্ছেদ ২৭: আইনের দৃষ্টিতে সমতা।
• অনুচ্ছেদ ২৮: ধর্ম প্রভৃতির কারণে বৈষম্য না করা।
• অনুচ্ছেদ ২৯: সরকারি নিয়োগে সুযোগের সমতা।
• অনুচ্ছেদ ৩২: জীবন ও ব্যক্তি স্বাধীনতার রক্ষণ।
• অনুচ্ছেদ ৩৭: সমাবেশের স্বাধীনতা।
• অনুচ্ছেদ ৩.৩৯: চিন্তা ও বিবেকের স্বাধীনতা এবং বাক-স্বাধীনতা।',
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);
    }
}
