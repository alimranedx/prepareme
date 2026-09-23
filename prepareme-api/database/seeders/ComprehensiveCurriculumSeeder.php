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

class ComprehensiveCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        // =========================================================================
        // 1. SUBJECTS & CHAPTERS
        // =========================================================================

        // --- SUBJECT 1: BANGLA ---
        $bangla = Subject::updateOrCreate(
            ['slug' => 'bangla'],
            [
                'name' => 'বাংলা ভাষা ও সাহিত্য',
                'description' => 'বিসিএস ও সরকারি চাকরির জন্য প্রাচীন, মধ্য ও আধুনিক বাংলা সাহিত্য এবং পূর্ণাঙ্গ ব্যাকরণ নির্মিতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chBanglaLit = Chapter::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-first-paper-literature'],
            [
                'name' => 'বাংলা ১ম পত্র / সাহিত্য',
                'description' => 'প্রাচীন যুগ (চর্যাপদ), মধ্যযুগ, আধুনিক যুগ, গুরুত্বপূর্ণ কবি, সাহিত্যিক, নাটক, উপন্যাস ও সাহিত্যিকদের উপাধি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chBanglaGrammar = Chapter::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-second-paper-grammar'],
            [
                'name' => 'বাংলা ২য় পত্র / ভাষা ও ব্যাকরণ',
                'description' => 'ধ্বনি ও বর্ণ, সন্ধি, সমাস, উপসর্গ, প্রত্যয়, কারক-বিভক্তি, পদ পরিবর্তন, নত্ব-ষত্ব বিধান ও শব্দার্থ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // Topics for Bangla Literature
        $tCharya = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'ancient-era-charyapada'],
            [
                'chapter_id' => $chBanglaLit->id,
                'name' => 'প্রাচীন যুগ: চর্যাপদ ও আবিষ্কার',
                'description' => 'চর্যাপদের আবিষ্কার, রচনাকাল, কবিগণ, ভাষাতাত্ত্বিক বৈশিষ্ট্য ও বিগত বিসিএস প্রশ্নোত্তর।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tMedieval = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'medieval-literature-mangalkavya'],
            [
                'chapter_id' => $chBanglaLit->id,
                'name' => 'মধ্যযুগ: মঙ্গলকাব্য, বৈষ্ণব পদাবলী ও শ্রীকৃষ্ণকীর্তন',
                'description' => 'মঙ্গলকাব্যের কবিগণ, শ্রীকৃষ্ণকীর্তন পুথি, শাহ মুহম্মদ সগীর, আলাওল ও আরাকান রাজসভা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tModernPoets = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'modern-poets-and-authors'],
            [
                'chapter_id' => $chBanglaLit->id,
                'name' => 'আধুনিক যুগ: মাইকেল মধুসূদন, বঙ্কিমচন্দ্র ও মীর মশাররফ',
                'description' => 'বাংলা সাহিত্যের আধুনিকতার প্রবর্তক, মহাকাব্য, নাটক এবং প্রথম উপন্যাসিকদের সৃষ্টি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tTagoreNazrul = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'tagore-and-nazrul'],
            [
                'chapter_id' => $chBanglaLit->id,
                'name' => 'রবীন্দ্রনাথ ঠাকুর ও কাজী নজরুল ইসলাম',
                'description' => 'বিসিএস পরীক্ষার সর্বাধিক গুরুত্বপূর্ণ অংশ: কাব্য, নাটক, উপন্যাস, ছোটগল্প ও নিষিদ্ধ গ্রন্থ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tTitles = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'literary-titles-and-pseudonyms'],
            [
                'chapter_id' => $chBanglaLit->id,
                'name' => 'সাহিত্যিকদের উপাধি, ছদ্মনাম ও প্রথম গ্রন্থ',
                'description' => 'ভানুসিংহ ঠাকুর, বীরবল, বনফুল, যাযাবর সহ সকল সাহিত্যিকের উপাধি ও ছদ্মনামের পূর্ণাঙ্গ তালিকা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 5,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        // Topics for Bangla Grammar
        $tSound = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-phonetics-and-orthography'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'ধ্বনি, বর্ণ ও উচ্চারণ রীতি',
                'description' => 'স্বরধ্বনি, ব্যঞ্জনধ্বনি, উচ্চারণ স্থান ভিত্তিক শ্রেণিবিভাগ এবং ন-ত্ব ও ষ-ত্ব বিধান।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tSandhi = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'sandhi'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'সন্ধি (স্বর, ব্যঞ্জন ও নিপাতনে সিদ্ধ)',
                'description' => 'সন্ধি নির্ণয়ের শর্টকাট কৌশল ও চাকরি পরীক্ষায় আসা নিপাতনে সিদ্ধ সন্ধির সংগ্রহ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tSamas = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'samas'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'সমাস নির্ণয় ও প্রকারভেদ',
                'description' => 'দ্বন্দ্ব, কর্মধারয়, তৎপুরুষ, বহুব্রীহি, দ্বিগু ও অব্যয়ীভাব সমাস চেনার সহজ নিয়ম।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tPrefix = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'prefixes-and-suffixes'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'উপসর্গ, অনুসর্গ ও প্রত্যয়',
                'description' => 'খাঁটি বাংলা, সংস্কৃত ও বিদেশি উপসর্গ চেনার সহজ উপায় এবং কৃৎ ও তদ্ধিত প্রত্যয়।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tKarak = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'karak-o-bibhakti'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'কারক ও বিভক্তি নির্ণয়',
                'description' => 'কর্তা, কর্ম, করণ, সম্প্রদান, অপাদান ও অধিকরণ কারক সহজে সনাক্তকরণ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 5,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tIdioms = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-idioms-and-phrases'],
            [
                'chapter_id' => $chBanglaGrammar->id,
                'name' => 'বাগধারা, এক কথায় প্রকাশ ও বিপরীত শব্দ',
                'description' => 'পরীক্ষায় বারবার আসা গুরুত্বপূর্ণ বাগধারা ও এক কথায় প্রকাশের সংকলন।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 6,
                'is_high_yield' => false,
                'created_by' => $adminId,
            ]
        );


        // --- SUBJECT 2: ENGLISH LANGUAGE & LITERATURE ---
        $english = Subject::updateOrCreate(
            ['slug' => 'english'],
            [
                'name' => 'English Language & Literature',
                'description' => 'Master English grammar, vocabulary, reading comprehension and English literary periods for BCS and Bank jobs.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $chEngGrammar = Chapter::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-grammar-syntax'],
            [
                'name' => 'English Grammar & Syntax',
                'description' => 'Parts of speech, right form of verbs, subject-verb agreement, voice, narration, prepositions and clauses.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chEngVocab = Chapter::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-vocabulary-usage'],
            [
                'name' => 'Vocabulary & Usage',
                'description' => 'Synonyms & Antonyms, Idioms & Phrases, One Word Substitution, Spellings and Analogies.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $chEngLit = Chapter::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-literature-periods'],
            [
                'name' => 'English Literature',
                'description' => 'Literary periods (Elizabethan, Romantic, Victorian, Modern), major authors, masterworks and famous quotes.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        // English Topics
        $tParts = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'parts-of-speech-identification'],
            [
                'chapter_id' => $chEngGrammar->id,
                'name' => 'Parts of Speech: Identification & Interchanges',
                'description' => 'Noun, Pronoun, Adjective, Adverb identification, Gerund vs Participle distinction.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tSva = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'right-form-of-verbs'],
            [
                'chapter_id' => $chEngGrammar->id,
                'name' => 'Subject-Verb Agreement & Right Form of Verbs',
                'description' => 'The most tested rules in BCS & Bank AD examinations with tricky test patterns.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tPreps = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'appropriate-prepositions-and-idioms'],
            [
                'chapter_id' => $chEngGrammar->id,
                'name' => 'Appropriate Prepositions & Phrasal Verbs',
                'description' => 'High frequency preposition pairings tested by IBA, Arts Faculty and BPSC.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tVoice = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'voice-and-narration'],
            [
                'chapter_id' => $chEngGrammar->id,
                'name' => 'Voice Change & Direct-Indirect Narration',
                'description' => 'Active to passive voice conversions, imperative sentences, exceptions and reported speech.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tSynAnt = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'synonyms-and-antonyms'],
            [
                'chapter_id' => $chEngVocab->id,
                'name' => 'Synonyms & Antonyms for Bank AD & BCS',
                'description' => 'GRE standard vocabulary, contextual word meanings, root words, prefixes and suffixes.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tIdiomsEng = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'idioms-phrases-one-word'],
            [
                'chapter_id' => $chEngVocab->id,
                'name' => 'Idioms, Phrases & One Word Substitutions',
                'description' => 'Frequently asked idioms and one word substitutions in competitive recruitment.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => false,
                'created_by' => $adminId,
            ]
        );

        $tShakespeare = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'shakespeare-and-romantic-poets'],
            [
                'chapter_id' => $chEngLit->id,
                'name' => 'William Shakespeare & Romantic Poets',
                'description' => 'Hamlet, Macbeth, Othello, King Lear; Wordsworth, Coleridge, Keats, Shelley, Byron.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tPeriods = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'literary-periods-and-quotes'],
            [
                'chapter_id' => $chEngLit->id,
                'name' => 'Literary Periods, Famous Works & Quotes',
                'description' => 'Chronological timeline of English literature with recurring exam quotes and authors.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );


        // --- SUBJECT 3: MATHEMATICS & MENTAL ABILITY ---
        $math = Subject::updateOrCreate(
            ['slug' => 'mathematics'],
            [
                'name' => 'গাণিতিক যুক্তি ও মানসিক দক্ষতা',
                'description' => 'পাটিগণিত, বীজগণিত, জ্যামিতি, পরিমিতি ও মানসিক দক্ষতার নির্ভুল সমাধান ও শর্টকাট টেকনিক।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        $chArith = Chapter::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'arithmetic'],
            [
                'name' => 'পাটিগণিত (Arithmetic)',
                'description' => 'বাস্তব সংখ্যা, লসাগু-গসাগু, শতকরা, লাভ-ক্ষতি, সরল ও চক্রবৃদ্ধি সুদ, অনুপাত, কাজ ও সময়, গতিবেগ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chAlg = Chapter::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'algebra'],
            [
                'name' => 'বীজগণিত (Algebra)',
                'description' => 'বীজগাণিতিক সূত্রাবলি, উৎপাদক, সূচক ও লগারিদম, সরল ও দ্বিঘাত সমীকরণ, সমান্তর ও গুণোত্তর ধারা, সেট।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $chGeo = Chapter::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'geometry-mensuration'],
            [
                'name' => 'জ্যামিতি, পরিমিতি ও সম্ভাব্যতা',
                'description' => 'রেখা, কোণ, ত্রিভুজ, চতুর্ভুজ, বৃত্ত, ক্ষেত্রফল, পরিমিতি, ত্রিকোণমিতি, বিন্যাস ও সমাবেশ, সম্ভাব্যতা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        $chMental = Chapter::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'mental-ability'],
            [
                'name' => 'মানসিক দক্ষতা (Mental Ability & Logic)',
                'description' => 'সংখ্যা ও বর্ণভিত্তিক ধারা, দিক ও রক্তের সম্পর্ক নির্ণয়, ঘড়ি ও ক্যালেন্ডার, চিত্রভিত্তিক যুক্তি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'created_by' => $adminId,
            ]
        );

        // Math Topics
        $tProfit = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'percentage-and-profit-loss'],
            [
                'chapter_id' => $chArith->id,
                'name' => 'শতকরা ও লাভ-ক্ষতি (Percentage & Profit-Loss)',
                'description' => 'শতকরার দ্রুত হিসাব এবং লাভ ও ক্ষতির গাণিতিক সমস্যা দ্রুত সমাধানের নিয়ম।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tInterest = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'simple-and-compound-interest'],
            [
                'chapter_id' => $chArith->id,
                'name' => 'সরল সুদ ও চক্রবৃদ্ধি মুনাফা (Simple & Compound Interest)',
                'description' => 'I = Pnr সূত্র এবং চক্রবৃদ্ধি মুনাফার ম্যাজিক হিসাব কৌশল।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tWorkSpeed = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'work-time-pipes-and-speed'],
            [
                'chapter_id' => $chArith->id,
                'name' => 'ঐকিক নিয়ম, সময়-কাজ, নল-চৌবাচ্চা ও গতিবেগ',
                'description' => 'ট্রেন ও নৌকার বেগ এবং যৌথভাবে কাজ সম্পন্ন করার ফর্মুলা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tLog = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'indices-and-logarithms'],
            [
                'chapter_id' => $chAlg->id,
                'name' => 'সূচক ও লগারিদম (Indices & Logarithms)',
                'description' => 'সূচকের ধর্মাবলি এবং লগারিদমের মান নির্ণয়ের পরীক্ষায় আসা নিয়মাবলী।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tSeries = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'arithmetic-and-geometric-series'],
            [
                'chapter_id' => $chAlg->id,
                'name' => 'সমান্তর ও গুণোত্তর ধারা (Series & Progression)',
                'description' => 'n-তম পদ ও n সংখ্যক পদের সমষ্টি নির্ণয়ের সূত্র ও বিগত প্রশ্নাবলি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tGeoTriangles = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'triangles-circles-and-mensuration'],
            [
                'chapter_id' => $chGeo->id,
                'name' => 'ত্রিভুজ, চতুর্ভুজ ও বৃত্তের পরিমিতি',
                'description' => 'পিথাগোরাসের উপপাদ্য, ত্রিভুজের ক্ষেত্রফল এবং বৃত্ত সংক্রান্ত উপপাদ্য।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tProbability = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'permutations-combinations-probability'],
            [
                'chapter_id' => $chGeo->id,
                'name' => 'বিন্যাস, সমাবেশ ও সম্ভাব্যতা (Permutation & Probability)',
                'description' => 'nPr, nCr এর প্রয়োগ এবং মুদ্রা ও ছক্কা নিক্ষেপের সম্ভাব্যতা হিসাব।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tReasoning = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'mental-ability-reasoning'],
            [
                'chapter_id' => $chMental->id,
                'name' => 'দিক নির্ণয়, সম্পর্ক ও ঘড়ি-ক্যালেন্ডার',
                'description' => 'কম্পাস দিক নির্ণয়, রক্তের আত্মীয়তা এবং ঘণ্টার ও মিনিটের কাঁটার মধ্যবর্তী কোণ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );


        // --- SUBJECT 4: GENERAL KNOWLEDGE (BD & INTL) ---
        $gk = Subject::updateOrCreate(
            ['slug' => 'general-knowledge'],
            [
                'name' => 'সাধারণ জ্ঞান (বাংলাদেশ ও আন্তর্জাতিক)',
                'description' => 'বাংলাদেশের ইতিহাস, মুক্তিযুদ্ধ, সংবিধান, অর্থনীতি এবং আন্তর্জাতিক সম্পর্ক ও বৈশ্বিক রাজনীতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'created_by' => $adminId,
            ]
        );

        $chBd = Chapter::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'bangladesh-affairs'],
            [
                'name' => 'বাংলাদেশ বিষয়াবলী (Bangladesh Affairs)',
                'description' => 'প্রাচীন বাংলা থেকে মুক্তিযুদ্ধ, সংবিধান, প্রশাসন, অর্থনীতি, জাতীয় সম্পদ ও সাম্প্রতিক অর্জন।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chIntl = Chapter::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'international-affairs'],
            [
                'name' => 'আন্তর্জাতিক বিষয়াবলী (International Affairs)',
                'description' => 'বিশ্ব ইতিহাস, মহাদেশ পরিচিতি, আন্তর্জাতিক সংস্থা (জাতিসংঘ, বিশ্বব্যাংক), চুক্তি ও ভূ-রাজনীতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // GK Topics
        $tConstitution = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'constitution-of-bangladesh'],
            [
                'chapter_id' => $chBd->id,
                'name' => 'গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান',
                'description' => 'সংবিধানের মূলনীতি, মৌলিক অধিকার, গুরুত্বপূর্ণ অনুচ্ছেদসমূহ ও সংশোধনীসমূহ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tLibWar = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'liberation-war-1971'],
            [
                'chapter_id' => $chBd->id,
                'name' => '১৯৭১ সালের মুক্তিযুদ্ধ ও মুজিবনগর সরকার',
                'description' => 'অপারেশন সার্চলাইট, ১১টি সেক্টর ও কমান্ডারগণ, বীরশ্রেষ্ঠগণ ও যৌথবাহিনীর চূড়ান্ত বিজয়।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tEconomy = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'bangladesh-economy-and-megaprojects'],
            [
                'chapter_id' => $chBd->id,
                'name' => 'বাংলাদেশের অর্থনীতি, মেগা প্রকল্প ও জাতীয় অর্জন',
                'description' => 'বাজেট, পদ্মাসেতু, মেট্রোরেল, রূপপুর বিদ্যুৎ কেন্দ্র ও রপ্তানি বাণিজ্য।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tUn = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'united-nations-and-global-organizations'],
            [
                'chapter_id' => $chIntl->id,
                'name' => 'জাতিসংঘ ও প্রধান আন্তর্জাতিক সংস্থাসমূহ',
                'description' => 'জাতিসংঘের প্রধান অঙ্গ, বিশ্বব্যাংক, আইএমএফ, ব্রিকস, ন্যাটোর সদর দপ্তর ও কার্যপদ্ধতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tGeopolitics = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'global-geopolitics-and-treaties'],
            [
                'chapter_id' => $chIntl->id,
                'name' => 'ভূ-রাজনীতি, সীমারেখা ও আন্তর্জাতিক চুক্তি',
                'description' => 'আন্তর্জাতিক প্রণালী, সীমারেখা (র‍্যাডক্লিফ, ম্যাকমোহন), নোবেল পুরস্কার ও সাম্প্রতিক বিশ্ব।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );


        // --- SUBJECT 5: SCIENCE & ICT ---
        $ict = Subject::updateOrCreate(
            ['slug' => 'ict'],
            [
                'name' => 'বিজ্ঞান ও তথ্যপ্রযুক্তি (Science & ICT)',
                'description' => 'সাধারণ বিজ্ঞান (পদার্থ, রসায়ন, জীববিজ্ঞান) এবং তথ্য ও যোগাযোগ প্রযুক্তি (ICT)।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 5,
                'created_by' => $adminId,
            ]
        );

        $chGenScience = Chapter::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'general-science'],
            [
                'name' => 'সাধারণ বিজ্ঞান (General Science)',
                'description' => 'পদার্থ বিজ্ঞান (আলো, শব্দ, বিদ্যুৎ), রসায়ন (পর্যায় সারণি, অ্যাসিড) ও জীববিজ্ঞান (মানবদেহ, রোগ)।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chIct = Chapter::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'ict-fundamentals'],
            [
                'name' => 'তথ্য ও যোগাযোগ প্রযুক্তি (ICT)',
                'description' => 'কম্পিউটার আর্কিটেকচার, মেমোরি, ডাটাবেস, নেটওয়ার্কিং, ক্লাউড কম্পিউটিং ও সাইবার নিরাপত্তা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // Science & ICT Topics
        $tBio = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'human-biology-and-health-science'],
            [
                'chapter_id' => $chGenScience->id,
                'name' => 'মানবদেহ, পুষ্টিবিজ্ঞান ও সংক্রামক ব্যাধি',
                'description' => 'রক্তের গ্রুপ, ভিটামিনের উৎস ও অভাবজনিত রোগ, ভাইরাস ও ব্যাকটেরিয়া গঠিত রোগ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tPhysics = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'everyday-physics-and-chemistry'],
            [
                'chapter_id' => $chGenScience->id,
                'name' => 'দৈনন্দিন পদার্থ ও রসায়ন বিজ্ঞান',
                'description' => 'আলোর প্রতিফলন ও প্রতিসরণ, শব্দের বেগ, অ্যাসিড-ক্ষারকের pH মান ও পর্যায় সারণি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tHardware = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'computer-hardware-and-memory'],
            [
                'chapter_id' => $chIct->id,
                'name' => 'কম্পিউটার হার্ডওয়্যার, মেমরি ও নম্বর সিস্টেম',
                'description' => 'CPU, RAM vs ROM, ক্যাশ মেমরি, বাইনারি ও হেক্সাডেসিমাল কনভার্শন।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        $tNetworking = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'computer-networking'],
            [
                'chapter_id' => $chIct->id,
                'name' => 'কম্পিউটার নেটওয়ার্ক, ইন্টারনেট ও ক্লাউড',
                'description' => 'OSI 7 Layers, IP Addressing (IPv4/IPv6), DNS, রাউটার ও ওয়াইফাই টেকনোলজি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        // --- SUBJECT 6: TECHNICAL & ENGINEERING ---
        $tech = Subject::updateOrCreate(
            ['slug' => 'technical-subjects'],
            [
                'name' => 'প্রকৌশল ও টেকনিক্যাল বিষয়াবলী',
                'description' => 'কম্পিউটার সায়েন্স, আইটি অফিসার এবং ইলেকট্রিক্যাল ইঞ্জিনিয়ার পদের টেকনিক্যাল পরীক্ষা ও ভাইভার পূর্ণাঙ্গ প্রস্তুতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 6,
                'created_by' => $adminId,
            ]
        );

        $chCse = Chapter::updateOrCreate(
            ['subject_id' => $tech->id, 'slug' => 'cse-software-engineering'],
            [
                'name' => 'কম্পিউটার সায়েন্স ও সফটওয়্যার ইঞ্জিনিয়ারিং',
                'description' => 'ডাটা স্ট্রাকচার, অ্যালগরিদম, ডিবিএমএস, এসকিউএল কোয়েরি ও সফটওয়্যার আর্কিটেকচার।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $chEee = Chapter::updateOrCreate(
            ['subject_id' => $tech->id, 'slug' => 'eee-electronics'],
            [
                'name' => 'ইলেকট্রিক্যাল ও ডিজিটাল ইলেকট্রনিক্স',
                'description' => 'সার্কিট অ্যানালাইসিস, লজিক গেটস, বুলিয়ান অ্যালজেব্রা, পাওয়ার সিস্টেম ও সিগন্যাল।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        Topic::updateOrCreate(
            ['subject_id' => $tech->id, 'slug' => 'computer-science-fundamentals'],
            [
                'chapter_id' => $chCse->id,
                'name' => 'ডাটা স্ট্রাকচার ও ডাটাবেস ম্যানেজমেন্ট (DBMS)',
                'description' => 'Array, Stack, Queue, Tree, SQL Queries, Normalization ও ACID প্রোপার্টিজ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );

        Topic::updateOrCreate(
            ['subject_id' => $tech->id, 'slug' => 'basic-electrical-and-electronics'],
            [
                'chapter_id' => $chEee->id,
                'name' => 'মৌলিক ইলেকট্রিক্যাল ও ডিজিটাল ইলেকট্রনিক্স',
                'description' => 'Ohm\'s Law, Kirchhoff\'s Laws, Logic Gates, Boolean Algebra ও ট্রানজিস্টর।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'is_high_yield' => true,
                'created_by' => $adminId,
            ]
        );


        // =========================================================================
        // 2. EXAMS & SYLLABUS MAPPINGS
        // =========================================================================
        $bcs = Exam::updateOrCreate(
            ['slug' => 'bcs-preliminary'],
            [
                'name' => 'বিসিএস প্রিলিমিনারি (BCS Preliminary)',
                'category' => 'bcs',
                'description' => 'বাংলাদেশ সরকারি কর্ম কমিশন (BPSC) পরিচালিত বিসিএস প্রিলিমিনারি পরীক্ষার ২০০ নম্বরের পূর্ণাঙ্গ বিষয়ভিত্তিক সিলেবাস, প্রশ্নব্যাংক ও নেগেটিভ মার্কিং মডেল টেস্ট।',
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
                'description' => 'বাংলাদেশ ব্যাংক এডি, অফিসার (জেনারেল/ক্যাশ) এবং সমন্বিত ৮/১০ ব্যাংকের ১০০ নম্বরের প্রিলিমিনারি পরীক্ষার প্রস্তুতি।',
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
                'description' => 'প্রাথমিক শিক্ষা অধিদপ্তর পরিচালিত সহকারী শিক্ষক পদের ৮০ নম্বরের এমসিকিউ পরীক্ষার সিলেবাস ও প্রশ্নব্যাংক।',
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

        // Subject mappings to exams
        $bcs->subjects()->sync([
            $bangla->id => ['marks' => 35, 'sort_order' => 1],
            $english->id => ['marks' => 35, 'sort_order' => 2],
            $gk->id => ['marks' => 50, 'sort_order' => 3],
            $math->id => ['marks' => 30, 'sort_order' => 4],
            $ict->id => ['marks' => 15, 'sort_order' => 5],
        ]);

        $bank->subjects()->sync([
            $english->id => ['marks' => 30, 'sort_order' => 1],
            $math->id => ['marks' => 30, 'sort_order' => 2],
            $bangla->id => ['marks' => 15, 'sort_order' => 3],
            $gk->id => ['marks' => 15, 'sort_order' => 4],
            $ict->id => ['marks' => 10, 'sort_order' => 5],
        ]);

        $primary->subjects()->sync([
            $bangla->id => ['marks' => 20, 'sort_order' => 1],
            $english->id => ['marks' => 20, 'sort_order' => 2],
            $math->id => ['marks' => 20, 'sort_order' => 3],
            $gk->id => ['marks' => 20, 'sort_order' => 4],
        ]);

        // =========================================================================
        // 3. QUESTION SOURCES (PREVIOUS EXAM PAPERS)
        // =========================================================================
        $src46Bcs = QuestionSource::updateOrCreate(
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

        $src45Bcs = QuestionSource::updateOrCreate(
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

        $src44Bcs = QuestionSource::updateOrCreate(
            ['slug' => '44th-bcs-preliminary'],
            [
                'name' => '৪৪তম বিসিএস প্রিলিমিনারি পরীক্ষা (2022)',
                'exam_id' => $bcs->id,
                'year' => 2022,
                'exam_date' => '2022-05-27',
                'total_questions' => 200,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $src43Bcs = QuestionSource::updateOrCreate(
            ['slug' => '43rd-bcs-preliminary'],
            [
                'name' => '৪৩তম বিসিএস প্রিলিমিনারি পরীক্ষা (2021)',
                'exam_id' => $bcs->id,
                'year' => 2021,
                'exam_date' => '2021-10-29',
                'total_questions' => 200,
                'status' => ContentStatus::PUBLISHED,
                'created_by' => $adminId,
            ]
        );

        $srcBbAd = QuestionSource::updateOrCreate(
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

        $srcPrimary23 = QuestionSource::updateOrCreate(
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

        // =========================================================================
        // 4. AUTHENTIC PREVIOUS QUESTIONS SEEDING WITH DETAILED EXPLANATIONS
        // =========================================================================
        $questionsData = [
            // --- BANGLA QUESTIONS ---
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaLit->id,
                'topic_id' => $tCharya->id,
                'source_id' => $src45Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "চর্যাপদ কোন ছন্দে রচিত?",
                'options' => ['অক্ষরবৃত্ত ছন্দ', 'মাত্রাবৃত্ত ছন্দ', 'স্বরবৃত্ত ছন্দ', 'পয়ার ছন্দ'],
                'correct_option' => 'খ',
                'explanation' => "চর্যাপদ মূলত 'মাত্রাবৃত্ত' (বা পদাকুলক) ছন্দে রচিত। বাংলা সাহিত্যের আদি নিদর্শন চর্যাপদের পদগুলো গান হিসেবে গাওয়া হতো এবং এতে মাত্রা সংখ্যার হিসাবের প্রাধান্য রয়েছে।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaLit->id,
                'topic_id' => $tCharya->id,
                'source_id' => $src46Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "বাংলা সাহিত্যের আদি নিদর্শন চর্যাপদের সর্বাধিক পদ রচয়িতা কে?",
                'options' => ['লুইপা', 'কাহ্নপা', 'ভুসুকুপা', 'শবরপা'],
                'correct_option' => 'খ',
                'explanation' => "চর্যাপদে কাহ্নপা সর্বাধিক ১৩টি পদ রচনা করেন। দ্বিতীয় সর্বোচ্চ পদ রচনা করেন ভুসুকুপা (৮টি পদ)। চর্যাপদের আদি পদকর্তা হলেন লুইপা (২টি পদ)।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaLit->id,
                'topic_id' => $tTagoreNazrul->id,
                'source_id' => $src44Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "কাজী নজরুল ইসলামের প্রথম প্রকাশিত কাব্যগ্রন্থ কোনটি?",
                'options' => ['বিষের বাঁশী', 'অগ্নি-বীণা', 'দোলন-চাঁপা', 'সর্বহারা'],
                'correct_option' => 'খ',
                'explanation' => "কাজী নজরুল ইসলামের প্রথম কাব্যগ্রন্থ 'অগ্নি-বীণা' ১৯২২ সালে প্রকাশিত হয়। এর প্রথম কবিতা 'প্রলয়োল্লাস' এবং বিখ্যাত 'বিদ্রোহী' কবিতাটি এই কাব্যের দ্বিতীয় কবিতা।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaLit->id,
                'topic_id' => $tTagoreNazrul->id,
                'source_id' => $src43Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "রবীন্দ্রনাথ ঠাকুর তাঁর কোন নাটকটি কাজী নজরুল ইসলামকে উৎসর্গ করেছিলেন?",
                'options' => ['তাসের দেশ', 'কালের যাত্রা', 'বসন্ত', 'রক্তকরবী'],
                'correct_option' => 'গ',
                'explanation' => "রবীন্দ্রনাথ ঠাকুর তাঁর গীতিনাট্য 'বসন্ত' নজরুলকে উৎসর্গ করেছিলেন। অন্যদিকে রবীন্দ্রনাথ 'কালের যাত্রা' নাটকটি উৎসর্গ করেছিলেন কথাসাহিত্যিক শরৎচন্দ্র চট্টোপাধ্যায়কে।",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaGrammar->id,
                'topic_id' => $tSandhi->id,
                'source_id' => $src45Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "কোনটি নিপাতনে সিদ্ধ সন্ধির উদাহরণ?",
                'options' => ['বিদ্যালয়', 'গায়ক', 'পরস্পর', 'মনস্তাপ'],
                'correct_option' => 'গ',
                'explanation' => "যেসব সন্ধি সাধারণ কোনো নিয়ম মানে না, তাদের নিপাতনে সিদ্ধ সন্ধি বলে। 'পর + পর = পরস্পর', 'বৃহৎ + পতি = বৃহস্পতি', 'বন + পতি = বনস্পতি' নিপাতনে সিদ্ধ সন্ধির দৃষ্টান্ত।",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaGrammar->id,
                'topic_id' => $tSamas->id,
                'source_id' => $srcPrimary23->id,
                'exam_id' => $primary->id,
                'question' => "'সিংহাসন' কোন সমাসের উদাহরণ?",
                'options' => ['মধ্যপদলোপী কর্মধারয়', 'উপমান কর্মধারয়', 'রূপক কর্মধারয়', 'বহুব্রীহি সমাস'],
                'correct_option' => 'ক',
                'explanation' => "'সিংহ চিহ্নিত আসন = সিংহাসন'। ব্যাসবাক্যের মাঝের ব্যাখ্যামূলক পদ (চিহ্নিত) লোপ পাওয়ায় এটি মধ্যপদলোপী কর্মধারয় সমাস।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $bangla->id,
                'chapter_id' => $chBanglaGrammar->id,
                'topic_id' => $tKarak->id,
                'source_id' => $srcBbAd->id,
                'exam_id' => $bank->id,
                'question' => "'পাপে বিরত হও' — 'পাপে' কোন কারকে কোন বিভক্তি?",
                'options' => ['করণে ৭মী', 'অপাদানে ৭মী', 'অধিকরণে ৭মী', 'কর্মে ৭মী'],
                'correct_option' => 'খ',
                'explanation' => "যা থেকে কিছু বিচ্যুত, জাত, ভীত বা বিরত হয় তাকে অপাদান কারক বলে। পাপে (পাপ + এ) এখানে ৭মী বিভক্তিযুক্ত অপাদান কারক।",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],

            // --- ENGLISH QUESTIONS ---
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngGrammar->id,
                'topic_id' => $tSva->id,
                'source_id' => $src46Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "The quality of these mangoes ___ not good.",
                'options' => ['is', 'are', 'were', 'have been'],
                'correct_option' => 'ক',
                'explanation' => "The true grammatical subject is 'The quality' (singular uncountable noun), not the plural modifier 'these mangoes'. Therefore, the singular verb 'is' is required.",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngGrammar->id,
                'topic_id' => $tSva->id,
                'source_id' => $srcBbAd->id,
                'exam_id' => $bank->id,
                'question' => "Neither the teacher nor the students ___ present in the seminar hall.",
                'options' => ['was', 'were', 'is', 'has been'],
                'correct_option' => 'খ',
                'explanation' => "Under the rule of proximity, when subjects are linked by 'neither...nor', the verb agrees in number with the subject closest to it. Here 'students' is plural, so 'were' is correct.",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngGrammar->id,
                'topic_id' => $tPreps->id,
                'source_id' => $src45Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "He died ___ cancer last night.",
                'options' => ['from', 'of', 'by', 'for'],
                'correct_option' => 'খ',
                'explanation' => "A person dies 'of' a disease (e.g. cancer, cholera), dies 'from' an external cause (e.g. overwork, wound), dies 'by' violence/poison, and dies 'for' a noble cause.",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngGrammar->id,
                'topic_id' => $tParts->id,
                'source_id' => $srcPrimary23->id,
                'exam_id' => $primary->id,
                'question' => "Look at the 'flying' bird. Here 'flying' is a/an —",
                'options' => ['Gerund', 'Participle', 'Infinitive', 'Adverb'],
                'correct_option' => 'খ',
                'explanation' => "Verb + ing functioning as an adjective modifying a noun ('bird') is a Present Participle. If it had functioned as a noun, it would be a Gerund.",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngLit->id,
                'topic_id' => $tShakespeare->id,
                'source_id' => $src44Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "\"To be or not to be, that is the question\" — is a famous soliloquy from which tragedy?",
                'options' => ['Macbeth', 'Othello', 'Hamlet', 'King Lear'],
                'correct_option' => 'গ',
                'explanation' => "This iconic philosophical soliloquy is uttered by Prince Hamlet in William Shakespeare's masterpiece tragedy 'Hamlet' (Act III, Scene 1).",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $english->id,
                'chapter_id' => $chEngVocab->id,
                'topic_id' => $tSynAnt->id,
                'source_id' => $srcBbAd->id,
                'exam_id' => $bank->id,
                'question' => "What is the antonym of the word 'EPHEMERAL'?",
                'options' => ['Transient', 'Fleeting', 'Eternal', 'Brief'],
                'correct_option' => 'গ',
                'explanation' => "'Ephemeral' means lasting for a very short time (ক্ষণস্থায়ী). Its direct antonym is 'Eternal' (চিরন্তন/শাশ্বত) or 'Perpetual'.",
                'difficulty' => DifficultyLevel::HARD,
            ],

            // --- MATHEMATICS QUESTIONS ---
            [
                'subject_id' => $math->id,
                'chapter_id' => $chArith->id,
                'topic_id' => $tProfit->id,
                'source_id' => $src45Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "একটি পণ্যের ক্রয়মূল্য ৮০০ টাকা। পণ্যটি ১০% ক্ষতিতে বিক্রয় করা হলে, বিক্রয়মূল্য কত টাকা?",
                'options' => ['৭০০ টাকা', '৭২০ টাকা', '৭৫০ টাকা', '৭৮০ টাকা'],
                'correct_option' => 'খ',
                'explanation' => "১০% ক্ষতিতে বিক্রয়মূল্য = ১০০ - ১০ = ৯০%। অতএব বিক্রয়মূল্য = ৮০০ এর ৯০% = (৮০০ × ৯০) / ১০০ = ৭২০ টাকা।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $math->id,
                'chapter_id' => $chArith->id,
                'topic_id' => $tInterest->id,
                'source_id' => $srcBbAd->id,
                'exam_id' => $bank->id,
                'question' => "১০% সরল সুদে কত বছরে ৫০০ টাকার সুদ ১০০ টাকা হবে?",
                'options' => ['১ বছর', '২ বছর', '৩ বছর', '৪ বছর'],
                'correct_option' => 'খ',
                'explanation' => "আমরা জানি, I = Pnr। সুতরাং সময় n = I / (Pr) = ১০০ / (৫০০ × ০.১০) = ১০০ / ৫০ = ২ বছর।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $math->id,
                'chapter_id' => $chAlg->id,
                'topic_id' => $tLog->id,
                'source_id' => $src46Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "log₂ 32 এর মান কত?",
                'options' => ['৩', '৪', '৫', '৬'],
                'correct_option' => 'গ',
                'explanation' => "৩২ = ২⁵। সুতরাং log₂ 32 = log₂ (2⁵) = 5 × log₂ 2 = 5 × 1 = ৫।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $math->id,
                'chapter_id' => $chAlg->id,
                'topic_id' => $tSeries->id,
                'source_id' => $srcPrimary23->id,
                'exam_id' => $primary->id,
                'question' => "১ + ৩ + ৫ + ৭ + ...... ধারাটির প্রথম ১০টি পদের সমষ্টি কত?",
                'options' => ['৮০', '৯০', '১০০', '১১০'],
                'correct_option' => 'গ',
                'explanation' => "প্রথম n সংখ্যক বিজোড় স্বাভাবিক সংখ্যার যোগফল = n²। এখানে n = ১০, সুতরাং সমষ্টি = ১০² = ১০০।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $math->id,
                'chapter_id' => $chGeo->id,
                'topic_id' => $tProbability->id,
                'source_id' => $src44Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "একটি নিরপেক্ষ ছক্কা নিক্ষেপ করলে মৌলিক সংখ্যা আসার সম্ভাব্যতা কত?",
                'options' => ['১/৬', '১/৩', '১/২', '২/৩'],
                'correct_option' => 'গ',
                'explanation' => "ছক্কার মোট সম্ভাব্য ফলাফল = {১, ২, ৩, ৪, ৫, ৬} (৬টি)। মৌলিক সংখ্যাগুলো হলো {২, ৩, ৫} (৩টি)। অতএব সম্ভাব্যতা = ৩/৬ = ১/২।",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],
            [
                'subject_id' => $math->id,
                'chapter_id' => $chMental->id,
                'topic_id' => $tReasoning->id,
                'source_id' => $src43Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "ঘড়িতে যখন ৩টা বেজে ৩০ মিনিট তখন ঘণ্টার কাঁটা ও মিনিটের কাঁটার মধ্যবর্তী কোণ কত ডিগ্রি?",
                'options' => ['৬০°', '৭০°', '৭৫°', '৮০°'],
                'correct_option' => 'গ',
                'explanation' => "কোণ নির্ণয়ের সূত্র = |(৬০H - ১১M) / ২| = |(৬০ × ৩ - ১১ × ৩০) / ২| = |(১৮০ - ৩৩০) / ২| = |-১৫০ / ২| = ৭৫°।",
                'difficulty' => DifficultyLevel::MEDIUM,
            ],

            // --- GENERAL KNOWLEDGE QUESTIONS ---
            [
                'subject_id' => $gk->id,
                'chapter_id' => $chBd->id,
                'topic_id' => $tConstitution->id,
                'source_id' => $src46Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "বাংলাদেশের সংবিধানের কত নম্বর অনুচ্ছেদে 'আইনের দৃষ্টিতে সমতা' নিশ্চিত করা হয়েছে?",
                'options' => ['অনুচ্ছেদ ২৫', 'অনুচ্ছেদ ২৭', 'অনুচ্ছেদ ৩১', 'অনুচ্ছেদ ৩২'],
                'correct_option' => 'খ',
                'explanation' => "অনুচ্ছেদ ২৭ অনুযায়ী: 'সকল নাগরিক আইনের দৃষ্টিতে সমান এবং আইনের সমান আশ্রয় লাভের অধিকারী।' অনুচ্ছেদ ২৮ এ ধর্ম-বর্ণ-নারী বৈষম্য বিলোপ এবং অনুচ্ছেদ ৩১ এ আইনের আশ্রয় লাভের অধিকার বর্ণিত হয়েছে।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $gk->id,
                'chapter_id' => $chBd->id,
                'topic_id' => $tLibWar->id,
                'source_id' => $src45Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "১৯৭১ সালের মুক্তিযুদ্ধে নৌ-কমান্ডো পরিচালিত বিখ্যাত অপারেশনটির নাম কী?",
                'options' => ['অপারেশন সার্চলাইট', 'অপারেশন জ্যাকপট', 'অপারেশন ডেভিল', 'অপারেশন ক্লোজআপ'],
                'correct_option' => 'খ',
                'explanation' => "১৯৭১ সালের ১৫ আগস্ট চট্টগ্রাম ও মংলা সমুদ্রবন্দর এবং চাঁদপুর ও নারায়ণগঞ্জ নদীবন্দরে পাকিস্তানি যুদ্ধজাহাজ ধ্বংস করতে বীর বাঙালি নৌ-কমান্ডোরা একযোগে 'অপারেশন জ্যাকপট' পরিচালনা করেন।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $gk->id,
                'chapter_id' => $chIntl->id,
                'topic_id' => $tUn->id,
                'source_id' => $src44Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "জাতিসংঘের নিরাপত্তা পরিষদের স্থায়ী সদস্য সংখ্যা কত?",
                'options' => ['৫টি', '১০টি', '১৫টি', '২০টি'],
                'correct_option' => 'ক',
                'explanation' => "নিরাপত্তা পরিষদের মোট সদস্য সংখ্যা ১৫টি। এর মধ্যে স্থায়ী সদস্য ৫টি (যুক্তরাষ্ট্র, যুক্তরাজ্য, ফ্রান্স, রাশিয়া ও চীন যাদের ভেটো ক্ষমতা রয়েছে) এবং অস্থায়ী সদস্য ১০টি।",
                'difficulty' => DifficultyLevel::EASY,
            ],

            // --- SCIENCE & ICT QUESTIONS ---
            [
                'subject_id' => $ict->id,
                'chapter_id' => $chIct->id,
                'topic_id' => $tHardware->id,
                'source_id' => $src46Bcs->id,
                'exam_id' => $bcs->id,
                'question' => "কোনটি কম্পিউটারের ভোলাটাইল (Volatile / অস্থায়ী) মেমোরি?",
                'options' => ['ROM', 'RAM', 'Hard Disk', 'Flash Drive'],
                'correct_option' => 'খ',
                'explanation' => "RAM (Random Access Memory) একটি ভোলাটাইল বা বিদ্যুৎ চলে গেলে ডাটা মুছে যাওয়া অস্থায়ী মেমোরি। অন্যদিকে ROM, Hard Disk ও Flash Drive নন-ভোলাটাইল বা স্থায়ী স্টোরেজ।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $ict->id,
                'chapter_id' => $chIct->id,
                'topic_id' => $tNetworking->id,
                'source_id' => $srcBbAd->id,
                'exam_id' => $bank->id,
                'question' => "OSI রেফারেন্স মডেলে মোট কতটি স্তর (Layer) থাকে?",
                'options' => ['৪টি', '৫টি', '৭টি', '৯টি'],
                'correct_option' => 'গ',
                'explanation' => "OSI (Open Systems Interconnection) মডেলে ৭টি লেয়ার থাকে: Physical, Data Link, Network, Transport, Session, Presentation, এবং Application।",
                'difficulty' => DifficultyLevel::EASY,
            ],
            [
                'subject_id' => $ict->id,
                'chapter_id' => $chGenScience->id,
                'topic_id' => $tBio->id,
                'source_id' => $srcPrimary23->id,
                'exam_id' => $primary->id,
                'question' => "কোন ভিটামিনের অভাবে মানবদেহে স্কার্ভি (Scurvy) রোগ দেখা দেয়?",
                'options' => ['ভিটামিন A', 'ভিটামিন B', 'ভিটামিন C', 'ভিটামিন D'],
                'correct_option' => 'গ',
                'explanation' => "ভিটামিন C (অ্যাসকরবিক অ্যাসিড)-এর অভাবে মাড়ি ফুলে যাওয়া ও রক্ত পড়ার রোগ 'স্কার্ভি' হয়। ভিটামিন A এর অভাবে রাতকানা এবং ভিটামিন D এর অভাবে রিকেটস রোগ হয়।",
                'difficulty' => DifficultyLevel::EASY,
            ],
        ];

        // Seed questions with options and exam mappings
        foreach ($questionsData as $item) {
            $options = [
                'ক' => $item['options'][0],
                'খ' => $item['options'][1],
                'গ' => $item['options'][2],
                'ঘ' => $item['options'][3],
            ];

            $question = PublicQuestion::updateOrCreate(
                [
                    'question' => $item['question'],
                    'subject_id' => $item['subject_id'],
                ],
                [
                    'chapter_id' => $item['chapter_id'],
                    'topic_id' => $item['topic_id'],
                    'source_id' => $item['source_id'],
                    'answer' => $options[$item['correct_option']],
                    'options' => $options,
                    'correct_option' => $item['correct_option'],
                    'explanation' => $item['explanation'],
                    'difficulty' => $item['difficulty'],
                    'question_type' => QuestionType::MCQ,
                    'status' => ContentStatus::PUBLISHED,
                    'marks' => 1.00,
                    'negative_marks' => 0.50,
                    'created_by' => $adminId,
                ]
            );

            // Populate normalized question_options
            $question->optionsList()->delete();
            $order = 1;
            foreach ($options as $key => $text) {
                $question->optionsList()->create([
                    'option_key' => $key,
                    'option_text' => $text,
                    'is_correct' => ($key === $item['correct_option']),
                    'sort_order' => $order++,
                ]);
            }

            // Sync with exam
            $question->exams()->syncWithoutDetaching([$item['exam_id']]);
        }

        // =========================================================================
        // 5. RICH MODEL TESTS
        // =========================================================================
        $allQuestions = PublicQuestion::published()->get();

        $bcsGrandTest = ModelTest::updateOrCreate(
            ['slug' => 'bcs-preliminary-grand-model-test-01'],
            [
                'title' => 'বিসিএস প্রিলিমিনারি পূর্ণাঙ্গ মডেল টেস্ট – ০১',
                'exam_id' => $bcs->id,
                'model_test_type' => 'full_exam',
                'description' => 'সর্বশেষ বিসিএস প্রশ্নের মান ও প্যাটার্ন অনুযায়ী তৈরি পূর্ণাঙ্গ মডেল টেস্ট। নেগেটিভ মার্কিং ০.৫০।',
                'total_questions' => min($allQuestions->count(), 50),
                'total_marks' => (float) min($allQuestions->count(), 50),
                'pass_marks' => (float) (min($allQuestions->count(), 50) * 0.5),
                'duration_minutes' => 45,
                'negative_marking_rate' => 0.50,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );
        $bcsGrandTest->questions()->sync($allQuestions->pluck('id'));

        $bankTest = ModelTest::updateOrCreate(
            ['slug' => 'bank-recruitment-special-model-test-01'],
            [
                'title' => 'বাংলাদেশ ব্যাংক ও সমন্বিত ব্যাংক স্পেশাল টেস্ট – ০১',
                'exam_id' => $bank->id,
                'model_test_type' => 'full_exam',
                'description' => 'ইংরেজি, গণিত ও সাধারণ জ্ঞান নির্ভর সমন্বিত সরকারি ব্যাংক মডেল টেস্ট। নেগেটিভ মার্কিং ০.২৫।',
                'total_questions' => min($allQuestions->count(), 30),
                'total_marks' => (float) min($allQuestions->count(), 30),
                'pass_marks' => (float) (min($allQuestions->count(), 30) * 0.5),
                'duration_minutes' => 30,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );
        $bankQuestions = PublicQuestion::whereIn('subject_id', [$english->id, $math->id, $ict->id, $gk->id])->pluck('id');
        $bankTest->questions()->sync($bankQuestions);

        $primaryTest = ModelTest::updateOrCreate(
            ['slug' => 'primary-teacher-recruitment-test-01'],
            [
                'title' => 'প্রাথমিক শিক্ষক নিয়োগ স্পেশাল মডেল টেস্ট – ০১',
                'exam_id' => $primary->id,
                'model_test_type' => 'full_exam',
                'description' => 'ডিপিই ৮০ নম্বরের পরীক্ষার অনুরূপ সহকারী শিক্ষক স্পেশাল টেস্ট। নেগেটিভ মার্কিং ০.২৫।',
                'total_questions' => 20,
                'total_marks' => 20.00,
                'pass_marks' => 10.00,
                'duration_minutes' => 20,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );
        $primaryTest->questions()->sync($allQuestions->take(20)->pluck('id'));

        $banglaSubjectTest = ModelTest::updateOrCreate(
            ['slug' => 'bangla-subject-wise-model-test-01'],
            [
                'title' => 'বাংলা সাহিত্য ও ব্যাকরণ বিষয়ভিত্তিক টেস্ট – ০১',
                'subject_id' => $bangla->id,
                'model_test_type' => 'subject_wise',
                'description' => 'চর্যাপদ, আধুনিক সাহিত্যিক এবং সন্ধি-সমাস-কারকের উপর বিশেষ বিষয়ভিত্তিক পরীক্ষা।',
                'total_questions' => 15,
                'total_marks' => 15.00,
                'pass_marks' => 7.50,
                'duration_minutes' => 15,
                'negative_marking_rate' => 0.25,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );
        $banglaQIds = PublicQuestion::where('subject_id', $bangla->id)->pluck('id');
        $banglaSubjectTest->questions()->sync($banglaQIds);
    }
}
