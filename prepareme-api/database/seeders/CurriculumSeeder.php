<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\SectionType;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        // ========================================================
        // 1. SUBJECT: BANGLA LANGUAGE & LITERATURE
        // ========================================================
        $bangla = Subject::updateOrCreate(
            ['slug' => 'bangla'],
            [
                'name' => 'বাংলা ভাষা ও সাহিত্য',
                'description' => 'বিসিএস ও সকল সরকারি চাকরির জন্য বাংলা ব্যাকরণ এবং প্রাচীন, মধ্য ও আধুনিক বাংলা সাহিত্যের পূর্ণাঙ্গ প্রস্তুতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $grammar = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-grammar'],
            [
                'name' => 'বাংলা ব্যাকরণ ও নির্মিতি',
                'description' => 'ধ্বনি ও বর্ণ, সন্ধি, সমাস, কারক ও বিভক্তি, ণ-ত্ব ও ষ-ত্ব বিধান এবং বাক্য সংকোচন।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $sandhi = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'sandhi'],
            [
                'parent_id' => $grammar->id,
                'name' => 'সন্ধি (স্বর ও ব্যঞ্জন সন্ধি)',
                'description' => 'স্বরসন্ধি, ব্যঞ্জনসন্ধি ও নিপাতনে সিদ্ধ সন্ধির শর্টকাট কৌশল।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $samas = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'samas'],
            [
                'parent_id' => $grammar->id,
                'name' => 'সমাস নির্ণয় ও প্রকারভেদ',
                'description' => 'দ্বন্দ্ব, কর্মধারয়, তৎপুরুষ, বহুব্রীহি, দ্বিগু ও অব্যয়ীভাব সমাস চেনার ম্যাজিক টেকনিক।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $karak = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'karak-o-bibhakti'],
            [
                'parent_id' => $grammar->id,
                'name' => 'কারক ও বিভক্তি নির্ণয়',
                'description' => 'কর্তা, কর্ম, করণ, সম্প্রদান, অপাদান ও অধিকরণ কারক সহজে চেনার উপায়।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        $literature = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-literature'],
            [
                'name' => 'বাংলা সাহিত্য (প্রাচীন, মধ্য ও আধুনিক যুগ)',
                'description' => 'চর্যাপদ, মঙ্গলকাব্য, শ্রীকৃষ্ণকীর্তন, রবীন্দ্রনাথ ঠাকুর, কাজী নজরুল ইসলাম ও আধুনিক সাহিত্যিকবৃন্দ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $ancientLiterature = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'ancient-and-medieval-literature'],
            [
                'parent_id' => $literature->id,
                'name' => 'প্রাচীন ও মধ্যযুগ: চর্যাপদ ও বৈষ্ণব পদাবলী',
                'description' => 'হরপ্রসাদ শাস্ত্রী কর্তৃক চর্যাপদ আবিষ্কার, কবিগণ, শ্রীকৃষ্ণকীর্তন ও আরাকান রাজসভা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $modernLiterature = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'tagore-and-nazrul'],
            [
                'parent_id' => $literature->id,
                'name' => 'রবীন্দ্রনাথ ঠাকুর ও কাজী নজরুল ইসলাম',
                'description' => 'বিসিএস ও ব্যাংক পরীক্ষায় প্রতি বছর নিশ্চিত আসা দুই যুগস্রষ্টার সাহিত্যকর্ম ও তথ্যকোষ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // 2. SUBJECT: ENGLISH LANGUAGE & LITERATURE
        // ========================================================
        $english = Subject::updateOrCreate(
            ['slug' => 'english'],
            [
                'name' => 'English Language & Literature',
                'description' => 'Comprehensive grammar, vocabulary, idioms, reading comprehension and literary periods for competitive job exams.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $engGrammar = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-grammar'],
            [
                'name' => 'English Grammar & Syntax',
                'description' => 'Parts of speech, right form of verbs, subject-verb agreement and conditional sentences.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $verbs = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'right-form-of-verbs'],
            [
                'parent_id' => $engGrammar->id,
                'name' => 'Subject-Verb Agreement & Right Form of Verbs',
                'description' => 'Essential grammar rules frequently tested in BCS and Bank exams.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $partsOfSpeech = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'parts-of-speech-identification'],
            [
                'parent_id' => $engGrammar->id,
                'name' => 'Parts of Speech: Identification & Usage',
                'description' => 'Noun, Adjective, Adverb, Gerund vs Participle identification techniques.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $prepositions = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'appropriate-prepositions-and-idioms'],
            [
                'parent_id' => $engGrammar->id,
                'name' => 'Appropriate Prepositions & Idioms',
                'description' => 'High-frequency prepositions and idioms commonly tested in Bank AD and BCS prelims.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        $engLiterature = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-literature'],
            [
                'name' => 'English Literature & Periods',
                'description' => 'Elizabethan, Romantic, Victorian, and Modern periods with major authors and quotes.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $shakespeare = Topic::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'shakespeare-and-romantic-poets'],
            [
                'parent_id' => $engLiterature->id,
                'name' => 'William Shakespeare & Romantic Poets',
                'description' => 'Tragedies, comedies, quotes and romantic poetry frequently tested in BCS.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // 3. SUBJECT: MATHEMATICS & MENTAL ABILITY
        // ========================================================
        $math = Subject::updateOrCreate(
            ['slug' => 'mathematics'],
            [
                'name' => 'গাণিতিক যুক্তি ও মানসিক দক্ষতা',
                'description' => 'পাটিগণিত, বীজগণিত, জ্যামিতি ও মানসিক দক্ষতার শর্টকাট টেকনিক ও নির্ভুল সমাধান।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        $arithmetic = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'arithmetic'],
            [
                'name' => 'পাটিগণিত (Quantitative Aptitude)',
                'description' => 'শতকরা, লাভ-ক্ষতি, সুদকষা, অনুপাত, কাজ ও সময় এবং গতিবেগ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $percentage = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'percentage-and-profit-loss'],
            [
                'parent_id' => $arithmetic->id,
                'name' => 'শতকরা ও লাভ-ক্ষতি (Percentage & Profit-Loss)',
                'description' => 'শতকরার দ্রুত হিসাব এবং লাভ ও ক্ষতির গাণিতিক সমস্যা সমাধানের শর্টকাট।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $interest = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'simple-and-compound-interest'],
            [
                'parent_id' => $arithmetic->id,
                'name' => 'সুদকষা ও চক্রবৃদ্ধি মুনাফা (Simple & Compound Interest)',
                'description' => 'সরল সুদ ও চক্রবৃদ্ধি মুনাফার পার্থক্য এবং দ্রুত হিসাবের সূত্র।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $algebra = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'algebra-and-logarithms'],
            [
                'name' => 'বীজগণিত ও লগারিদম (Algebra & Indices)',
                'description' => 'বীজগাণিতিক সূত্রাবলী, উৎপাদকে বিশ্লেষণ, সূচক ও লগারিদমের নিয়ম।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $mentalAbility = Topic::updateOrCreate(
            ['subject_id' => $math->id, 'slug' => 'mental-ability-reasoning'],
            [
                'name' => 'মানসিক দক্ষতা (Mental Ability & Logic)',
                'description' => 'দিক নির্ণয়, রক্তের সম্পর্ক, ঘড়ি ও ক্যালেন্ডার সংক্রান্ত সমস্যা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 3,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // 4. SUBJECT: GENERAL KNOWLEDGE (BD & INTL)
        // ========================================================
        $gk = Subject::updateOrCreate(
            ['slug' => 'general-knowledge'],
            [
                'name' => 'সাধারণ জ্ঞান (বাংলাদেশ ও আন্তর্জাতিক)',
                'description' => 'বাংলাদেশের সংবিধান, মুক্তিযুদ্ধ, ভূ-রাজনীতি, আন্তর্জাতিক সংস্থা ও সাম্প্রতিক বিষয়াবলী।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'created_by' => $adminId,
            ]
        );

        $bdAffairs = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'bangladesh-affairs'],
            [
                'name' => 'বাংলাদেশ বিষয়াবলী',
                'description' => 'মুক্তিযুদ্ধ, ভৌগোলিক অবস্থান, জাতীয় সংসদ ও সংবিধান।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $constitution = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'constitution-of-bangladesh'],
            [
                'parent_id' => $bdAffairs->id,
                'name' => 'গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান',
                'description' => 'মূলনীতি, মৌলিক অধিকার এবং গুরুত্বপূর্ণ অনুচ্ছেদসমূহ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $liberationWar = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'liberation-war-1971'],
            [
                'parent_id' => $bdAffairs->id,
                'name' => '১৯৭১ সালের মুক্তিযুদ্ধ ও মুজিবনগর সরকার',
                'description' => 'অপারেশন সার্চলাইট, সেক্টর কমান্ডারগণ, বীরশ্রেষ্ঠ ও স্বাধীনতার দলিল।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $intlAffairs = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'international-affairs'],
            [
                'name' => 'আন্তর্জাতিক বিষয়াবলী',
                'description' => 'জাতিসংঘ, ব্রিকস, বিশ্বব্যাংক, ভূ-রাজনীতি ও আন্তর্জাতিক চুক্তি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // 5. SUBJECT: INFORMATION & COMMUNICATION TECH (ICT)
        // ========================================================
        $ict = Subject::updateOrCreate(
            ['slug' => 'ict'],
            [
                'name' => 'তথ্য ও যোগাযোগ প্রযুক্তি (ICT)',
                'description' => 'কম্পিউটার ডিভাইস, মেমোরি, ডাটাবেস, ক্লাউড কম্পিউটিং এবং সাইবার নিরাপত্তা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 5,
                'created_by' => $adminId,
            ]
        );

        $ictBasics = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'computer-hardware-and-memory'],
            [
                'name' => 'কম্পিউটার হার্ডওয়্যার ও মেমোরি সিস্টেম',
                'description' => 'CPU, RAM, ROM, ক্যাশ মেমোরি ও স্টোরেজ ইউনিট কনভার্শন।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $networking = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'computer-networking'],
            [
                'name' => 'কম্পিউটার নেটওয়ার্ক ও ইন্টারনেট প্রোটোকল',
                'description' => 'OSI Model, TCP/IP, IP Addressing, DNS ও সাইবার নিরাপত্তা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // 6. SUBJECT: TECHNICAL SUBJECTS & ENGINEERING
        // ========================================================
        $technical = Subject::updateOrCreate(
            ['slug' => 'technical-subjects'],
            [
                'name' => 'প্রকৌশল ও টেকনিক্যাল বিষয়াবলী',
                'description' => 'কম্পিউটার সায়েন্স, ইলেকট্রিক্যাল ও প্রকৌশল পদের টেকনিক্যাল পরীক্ষা ও লিখিত পরীক্ষার প্রস্তুতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 6,
                'created_by' => $adminId,
            ]
        );

        $cse = Topic::updateOrCreate(
            ['subject_id' => $technical->id, 'slug' => 'computer-science-fundamentals'],
            [
                'name' => 'ডাটা স্ট্রাকচার ও ডাটাবেস ম্যানেজমেন্ট (DBMS)',
                'description' => 'Array, Stack, Queue, Tree, SQL Queries, Normalization ও ACID প্রোপার্টিজ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $eee = Topic::updateOrCreate(
            ['subject_id' => $technical->id, 'slug' => 'basic-electrical-and-electronics'],
            [
                'name' => 'মৌলিক ইলেকট্রিক্যাল ও ডিজিটাল ইলেকট্রনিক্স',
                'description' => 'Ohm\'s Law, Kirchhoff\'s Laws, Logic Gates, Boolean Algebra ও ট্রানজিস্টর।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // ========================================================
        // COMPREHENSIVE STUDY GUIDES ACROSS ALL SUBJECTS
        // ========================================================

        // Guide 1: Bangla - Sandhi
        $guideSandhi = StudyGuide::updateOrCreate(
            ['slug' => 'mastering-sandhi-rules'],
            [
                'topic_id' => $sandhi->id,
                'title' => 'বিসিএস ও ব্যাংক পরীক্ষার জন্য সন্ধির পূর্ণাঙ্গ নিয়ম ও শর্টকাট কৌশল',
                'summary' => 'বাংলা ব্যাকরণে সন্ধির প্রধান নিয়ম, খাঁটি বাংলা সন্ধি এবং নিপাতনে সিদ্ধ সন্ধির নির্ভুল তালিকা।',
                'content' => "সন্ধি শব্দের অর্থ মিলন। পাশাপাশি অবস্থিত দুটি ধ্বনির মিলনকে সন্ধি বলে। দ্রুত উচ্চারণের ফলে সন্নিহিত দুটি ধ্বনির একটির প্রভাবে অন্যটি পরিবর্তিত হলে কিংবা উভয়ের মিলনে নতুন ধ্বনির সৃষ্টি হলে তাকে সন্ধি বলা হয়।\n\nবাংলা ব্যাকরণে সন্ধি দুই প্রকার: স্বরসন্ধি ও ব্যঞ্জনসন্ধি। কিন্তু সংস্কৃত ব্যাকরণে সন্ধি তিন প্রকার: স্বরসন্ধি, ব্যঞ্জনসন্ধি ও বিসর্গ সন্ধি। চাকরি পরীক্ষায় বেশিরভাগ প্রশ্ন সংস্কৃত নিয়ম এবং বিশেষ নিপাতনে সিদ্ধ সন্ধি থেকে এসে থাকে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ]
        );
        $guideSandhi->sections()->delete();
        $guideSandhi->sections()->createMany([
            [
                'title' => '১. মৌলিক ধারণা ও স্বরসন্ধির নিয়ম',
                'content' => "অ-কার কিংবা আ-কারের পর অ-কার কিংবা আ-কার থাকলে উভয়ে মিলে আ-কার হয়। আ-কার পূর্ববর্তী ব্যঞ্জনের সাথে যুক্ত হয়।\n\nউদাহরণ:\n• নর + অধম = নরাধম\n• হিম + আলয় = হিমালয়\n• বিদ্যা + আলয় = বিদ্যালয়\n• মহা + আশয় = মহাশয়",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. চাকরি পরীক্ষায় সর্বাধিক আসা নিপাতনে সিদ্ধ সন্ধি',
                'content' => "যেসব সন্ধি কোনো সাধারণ নিয়মের অধীনে পড়ে না, সেগুলোকে 'নিপাতনে সিদ্ধ সন্ধি' বলা হয়। পরীক্ষায় এই শব্দগুলো বারবার আসে:\n\n১. কুল + অটা = কুলটা (কুল + আটা নয়)\n২. গো + অক্ষ = গবাক্ষ\n৩. প্র + ঊঢ় = প্রৌঢ়\n৪. অন্য + অন্য = অন্যান্য\n৫. বন + পতি = বনস্পতি\n৬. বৃহৎ + পতি = বৃহস্পতি\n৭. তৎ + কর = তস্কর\n৮. আ + চর্য = আশ্চর্য\n৯. পতৎ + অঞ্জলি = পতঞ্জলি\n১০. ষট্ + দশ = ষোড়শ",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '৩. দ্রুত মনে রাখার সূত্র ও সারসংক্ষেপ',
                'content' => "বিসর্গ সন্ধির একটি চমৎকার শর্টকাট সূত্র:\nশব্দের মাঝে ও-কার (ো), রেফ (র্), তালব্য-শ (শ), মূর্ধন্য-ষ (ষ), বা দন্ত্য-স (স) থাকলে এদের পরিবর্তে বিসর্গ (ঃ) বসিয়ে প্রথম অংশটি আলাদা করা যায়।\nযেমন: মনস্তাপ = মনঃ + তাপ, তিরোধান = তিরঃ + ধান, শিরচ্ছেদ = শিরঃ + ছেদ।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 3,
            ],
            [
                'title' => '৪. বিগত বিসিএস ও ব্যাংক প্রশ্ন সমাধান',
                'content' => "বিগত বিসিএস পরীক্ষার প্রশ্নসমূহ সমাধান:\n• 'গায়ক' শব্দের সঠিক সন্ধি বিচ্ছেদ কোনটি? [উত্তর: গৈ + অক (৩৬তম বিসিএস)]\n• 'পদ্ধতি' শব্দের সন্ধি বিচ্ছেদ কোনটি? [উত্তর: পদ্ + হতি (৩৮তম বিসিএস)]\n• 'রবীন্দ্র' শব্দের সন্ধি বিচ্ছেদ কোনটি? [উত্তর: রবি + ইন্দ্র (৪৩তম বিসিএস)]\n• 'চলচ্চিত্র' শব্দের সন্ধি বিচ্ছেদ কোনটি? [উত্তর: চলৎ + চিত্র]",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 4,
            ],
        ]);

        // Guide 2: Bangla - Samas
        $guideSamas = StudyGuide::updateOrCreate(
            ['slug' => 'samas-shortcut-rules'],
            [
                'topic_id' => $samas->id,
                'title' => 'সমাস নির্ণয়ের অব্যর্থ শর্টকাট টেকনিক (দ্বন্দ্ব, কর্মধারয় ও বহুব্রীহি)',
                'summary' => 'অর্থের প্রাধান্য লক্ষ্য করে বাক্য বা শব্দ দেখেই ৫ সেকেন্ডে সমাস চেনার আধুনিক কৌশল।',
                'content' => "সমাস শব্দের অর্থ সংক্ষেপ, মিলন, বা একাধিক পদের একপদীকরণ। পরস্পর অর্থসঙ্গতিবিশিষ্ট দুই বা ততোধিক পদ মিলিত হয়ে একটি নতুন শব্দ গঠন করার প্রক্রিয়াকে সমাস বলে।\n\nসমাস মূলত ৬ প্রকার: দ্বন্দ্ব, কর্মধারয়, তৎপুরুষ, বহুব্রীহি, দ্বিগু এবং অব্যয়ীভাব সমাস। কিন্তু ড. মুহম্মদ শহীদুল্লাহ্ প্রধানত ৪ প্রকারের উল্লেখ করেছেন। পরীক্ষার জন্য অর্থের প্রাধান্যই সমাস চেনার সবচেয়ে বড় চাবিকাঠি।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(4),
                'created_by' => $adminId,
            ]
        );
        $guideSamas->sections()->delete();
        $guideSamas->sections()->createMany([
            [
                'title' => '১. পদের অর্থের প্রাধান্য ভিত্তিক সমাস চেনার চার্ট',
                'content' => "• উভয় পদের অর্থ প্রধান = দ্বন্দ্ব সমাস (মা-বাবা, ভাই-বোন)\n• পরপদের অর্থ প্রধান = কর্মধারয়, তৎপুরুষ ও দ্বিগু সমাস (নীলপদ্ম, রাজপুত্র, চৌরাস্তা)\n• কোনো পদের অর্থ না বুঝিয়ে ৩য় ব্যক্তি/বস্তু বোঝালে = বহুব্রীহি সমাস (পীতাম্বর, দশানন)\n• পূর্বপদের অব্যয়ের অর্থ প্রধান = অব্যয়ীভাব সমাস (উপকূল, যথারীতি)",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. কর্মধারয় সমাস সহজে চেনার ট্রিক',
                'content' => "কর্মধারয় সমাস ৪ প্রকার:\n১. মধ্যপদলোপী: মাঝের ব্যাখ্যামূলক পদ লোপ পায় (সিংহাসন = সিংহ চিহ্নিত আসন, স্মৃতিসৌধ)\n২. উপমান: দুটি বাস্তব/সত্য বিষয়ের তুলনা (তুষারশুভ্র = তুষারের ন্যায় শুভ্র, ভ্রমরকৃষ্ণ)\n৩. উপমিত: একটি বাস্তব কিন্তু অতিশয়োক্তি তুলনা (চন্দ্রমুখ = মুখ চন্দ্রের ন্যায়, পুরুষসিংহ)\n৪. রূপক: বিমূর্ত গুণকে দৃশ্যমান কিছুর সাথে অভেদ কল্পনা (মনমাঝি = মন রূপ মাঝি, বিশাদসিন্ধু)",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '৩. বিগত বিসিএস পরীক্ষার প্রশ্ন ও সমাধান',
                'content' => "• 'জায়াপত্তি' কোন সমাস? [উত্তর: দ্বন্দ্ব সমাস (দম্পতি) - ৩৪তম বিসিএস]\n• 'উপকূল' কোন সমাস? [উত্তর: অব্যয়ীভাব সমাস (কূলের সমীপে) - ৩৭তম বিসিএস]\n• 'পলান্ন' কোন সমাস? [উত্তর: মধ্যপদলোপী কর্মধারয় (পল মিশ্রিত অন্ন) - ৪১তম বিসিএস]",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 3,
            ],
        ]);

        // Guide 3: Bangla - Charyapada
        $guideCharya = StudyGuide::updateOrCreate(
            ['slug' => 'charyapada-ancient-literature'],
            [
                'topic_id' => $ancientLiterature->id,
                'title' => 'চর্যাপদ: বাংলা সাহিত্যের প্রাচীনতম নিদর্শন ও বিগত সালের প্রশ্নোত্তর',
                'summary' => 'চর্যাপদ আবিষ্কার, ভাষাতাত্ত্বিক বৈশিষ্ট্য, পদকর্তা ও ড. সুনীতিকুমার চট্টোপাধ্যায়ের গুরুত্বপূর্ণ সিদ্ধান্তসমূহ।',
                'content' => "বাংলা ভাষা ও সাহিত্যের আদি নিদর্শন 'চর্যাপদ'। এটি মূলত বৌদ্ধ সহজিয়া সাধকদের রচিত সাধন সঙ্গীত। ১৯০৭ সালে মহামহোপাধ্যায় হরপ্রসাদ শাস্ত্রী নেপালের রাজদরবারের রয়েল লাইব্রেরি থেকে চর্যাপদের পুথি আবিষ্কার করেন। ১৯১৬ সালে বঙ্গীয় সাহিত্য পরিষদ থেকে 'হাজার বছরের পুরাণ বাঙ্গালা ভাষায় রচিত বৌদ্ধগান ও দোহা' নামে এটি প্রথম প্রকাশিত হয়।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(4),
                'created_by' => $adminId,
            ]
        );
        $guideCharya->sections()->delete();
        $guideCharya->sections()->createMany([
            [
                'title' => '১. চর্যাপদের গুরুত্বপূর্ণ পরীক্ষাধর্মী তথ্যাবলী',
                'content' => "• আবিষ্কারক: মহামহোপাধ্যায় হরপ্রসাদ শাস্ত্রী (১৯০৭ সালে নেপাল থেকে)\n• প্রকাশকাল: ১৯১৬ খ্রিস্টাব্দে বঙ্গীয় সাহিত্য পরিষদ থেকে\n• মোট পদের সংখ্যা: সাড়ে ছেচল্লিশটি (৪৬.৫টি)\n• মোট পদকর্তা: ড. মুহম্মদ শহীদুল্লাহ্র মতে ২৩ জন; সুকুমার সেনের মতে ২৪ জন\n• সর্বাধিক পদ রচয়িতা: কাহ্নপা (১৩টি পদ), দ্বিতীয় সর্বোচ্চ লুইপা (২টি পদ)\n• চর্যাপদের আদি কবি: লুইপা\n• চর্যাপদের সবচেয়ে আধুনিক কবি: ভুসুকুপা (যিনি নিজেকে বাঙালি বলে দাবি করেছেন: 'আজি ভুসুকু বাঙ্গালী ভইলী')",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. চর্যাপদের ভাষা ও ছন্দ',
                'content' => "• চর্যাপদের ভাষাকে হরপ্রসাদ শাস্ত্রী বলেছেন 'সান্ধ্য ভাষা' বা আলো-আঁধারি ভাষা।\n• ১৯২৬ সালে ড. সুনীতিকুমার চট্টোপাধ্যায় তাঁর 'The Origin and Development of the Bengali Language' (ODBL) গ্রন্থে প্রথম ধ্বনিতাত্ত্বিক ও ব্যাকরণিক প্রমাণের মাধ্যমে চর্যাপদকে খাঁটি বাংলা ভাষার নিদর্শন হিসেবে প্রমাণ করেন।\n• চর্যাপদ মূলত মাত্রাবৃত্ত ছন্দে রচিত।",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 4: Bangla - Tagore & Nazrul
        $guideTagoreNazrul = StudyGuide::updateOrCreate(
            ['slug' => 'rabindranath-and-kazi-nazrul-islam-mastery'],
            [
                'topic_id' => $modernLiterature->id,
                'title' => 'রবীন্দ্রনাথ ঠাকুর ও কাজী নজরুল ইসলাম: ৩-স্টার সাহিত্য প্রস্তুতি',
                'summary' => 'কাব্যগ্রন্থ, নাটক, উপন্যাস, ছোটগল্প এবং তাঁদের প্রথম ও শেষ সাহিত্যকর্মের নির্ভুল তালিকা।',
                'content' => "বিসিএস প্রিলিমিনারি পরীক্ষায় বাংলা সাহিত্য অংশের ৩৫ নম্বরের মধ্যে রবীন্দ্রনাথ ঠাকুর ও কাজী নজরুল ইসলাম থেকে প্রতি বছর ৫-৭ নম্বর সরাসরি আসে। তাঁদের গুরুত্বপূর্ণ উপন্যাস, নাটক, পত্রিকা ও ঐতিহাসিক প্রেক্ষাপটের সৃষ্টিগুলো মনে রাখা অত্যন্ত গুরুত্বপূর্ণ।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ]
        );
        $guideTagoreNazrul->sections()->delete();
        $guideTagoreNazrul->sections()->createMany([
            [
                'title' => '১. রবীন্দ্রনাথ ঠাকুরের মাইলফলক সাহিত্যকর্ম',
                'content' => "• জন্ম: ৭ মে ১৮৬১ (২৫ বৈশাখ ১২৬৮); মৃত্যু: ৭ আগস্ট ১৯৪১ (২২ শ্রাবণ ১৩৪৮)\n• নোবেল পুরস্কার: ১৯১৩ সালে 'Song Offerings' (গীতাঞ্জলির ইংরেজি অনুবাদ) কাব্যের জন্য\n• প্রথম প্রকাশিত কাব্য: 'বনফুল' (১৮৮০)\n• প্রথম প্রকাশিত নাটক: 'বাল্মীকি-প্রতিভা' (১৮৮১)\n• প্রথম প্রকাশিত উপন্যাস: 'বৌ-ঠাকুরাণীর হাট' (১৮৮৩)\n• বিখ্যাত উপন্যাসসমূহ: চোখের বালি, গোরা, ঘরে-বাইরে, চতুরঙ্গ, শেষের কবিতা, চার অধ্যায়\n• উৎসর্গ: শরৎচন্দ্রকে 'কালের যাত্রা', নজরুলকে 'বসন্ত' নাটক উৎসর্গ করেন।",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. কাজী নজরুল ইসলামের যুগান্তকারী সৃষ্টি',
                'content' => "• জন্ম: ২৪ মে ১৮৯৯ (১১ জ্যৈষ্ঠ ১৩০৬); মৃত্যু: ২৯ আগস্ট ১৯৭৬ (১২ ভাদ্র ১৩৮৩)\n• প্রথম প্রকাশিত রচনা: 'বাউণ্ডেলের আত্মকাহিনী' (১৯১৯, সওগাত পত্রিকায়)\n• প্রথম প্রকাশিত কবিতা: 'মুক্তি' (১৯১৯, বঙ্গীয় মুসলমান সাহিত্য পত্রিকায়)\n• প্রথম প্রকাশিত কাব্যগ্রন্থ: 'অগ্নি-বীণা' (১৯২২, এতে ১২টি কবিতা রয়েছে, প্রথম কবিতা 'প্রলয়োল্লাস')\n• নিষিদ্ধ ঘোষিত গ্রন্থসমূহ (৫টি): যুগবাণী, বিষের বাঁশী, ভাঙার গান, প্রলয়শিখা, চন্দ্রবিন্দু\n• সম্পাদিত পত্রিকা: ধূমকেতু (১৯২২), লাঙল (১৯২৫), দৈনিক নবযুগ",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 5: English - Subject-Verb Agreement
        $guideSVA = StudyGuide::updateOrCreate(
            ['slug' => 'subject-verb-agreement-golden-rules'],
            [
                'topic_id' => $verbs->id,
                'title' => 'Subject-Verb Agreement: 10 Golden Rules for BCS & Bank Exams',
                'summary' => 'Master sentence agreement rules with prepositional traps, correlative conjunctions, and collective nouns.',
                'content' => "Subject-Verb Agreement is the most recurring grammar question type across BCS, Bangladesh Bank AD, and Combined Bank exams. The basic rule states: Singular subjects take singular verbs; plural subjects take plural verbs. However, examiners set traps using parenthetical clauses, compound subjects, and indefinite pronouns.",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ]
        );
        $guideSVA->sections()->delete();
        $guideSVA->sections()->createMany([
            [
                'title' => '1. Trap Rule: Intervening Prepositional Phrases',
                'content' => "Words coming between the subject and the verb DO NOT affect agreement. Look at the true head noun!\n\n• The quality of these mangoes [is / are] good. -> Answer: is (Subject is 'quality', not 'mangoes')\n• The colors of the rainbow [is / are] beautiful. -> Answer: are (Subject is 'colors')\n• The mayor, along with his advisors, [is / are] attending. -> Answer: is (as well as, along with, together with take the first subject)",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '2. Rule of Proximity: Either...or / Neither...nor',
                'content' => "When subjects are joined by 'or', 'nor', 'either...or', 'neither...nor', or 'not only...but also', the verb agrees with the NEAREST subject.\n\n• Neither the manager nor the employees [was / were] present. -> were (agrees with employees)\n• Either the boys or the teacher [has / have] arrived. -> has (agrees with teacher)",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '3. Previous Job Exam Questions with Solutions',
                'content' => "• 'Slow and steady ___ the race.' [wins] (38th BCS)\n• 'One of my friends ___ a doctor.' [is] (Combined 5 Banks 2021)\n• 'A number of students ___ present today.' [are] (Note: 'The number of' takes singular, 'A number of' takes plural)\n• 'Neither of the proposals ___ acceptable.' [is] ('Each', 'every', 'neither', 'either' are singular)",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 3,
            ],
        ]);

        // Guide 6: English - Prepositions & Idioms
        $guidePrep = StudyGuide::updateOrCreate(
            ['slug' => 'high-frequency-prepositions-and-idioms'],
            [
                'topic_id' => $prepositions->id,
                'title' => 'High-Frequency Appropriate Prepositions & Idioms for Bank AD',
                'summary' => 'Standard prepositions, phrasal verbs, and idioms tested by IBA, Arts Faculty, and BPSC.',
                'content' => "Appropriate Prepositions require targeted memorization of recurring patterns. In Bank exams set by IBA (Dhaka University) or BISP, prepositions carry 3 to 5 direct marks. This guide isolates the top 50 most frequently tested combinations.",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );
        $guidePrep->sections()->delete();
        $guidePrep->sections()->createMany([
            [
                'title' => '1. Crucial Confusing Preposition Pairs',
                'content' => "• Die of (illness/disease): He died of cancer.\n• Die from (overeating/wound): He died from overwork.\n• Die by (poison/violence): He died by poison.\n• Die for (a noble cause): Martyrs died for the country.\n\n• Blind to (faults): He is blind to his son's faults.\n• Blind of (an eye): He is blind of the left eye.\n\n• Congratulate on: I congratulated him on his success (NOT for).",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '2. High-Yield Idioms & Phrases',
                'content' => "• 'Achilles' heel': A vulnerable point or weak spot.\n• 'Bolt from the blue': An unexpected calamity.\n• 'In a nutshell': In summary or very brief form.\n• 'Apple of discord': Subject of dispute/rivalry.\n• 'White elephant': A very costly possession that is burdensome to maintain.",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
        ]);

        // Guide 7: English - Shakespeare & Romantic Poets
        $guideShake = StudyGuide::updateOrCreate(
            ['slug' => 'william-shakespeare-plays-and-quotes'],
            [
                'topic_id' => $shakespeare->id,
                'title' => 'William Shakespeare: 4 Major Tragedies & Iconic Quotes',
                'summary' => 'Hamlet, Othello, King Lear, Macbeth, and famous soliloquies tested in BCS preliminary.',
                'content' => "William Shakespeare (1564–1616), the National Poet of England and the 'Bard of Avon', wrote 38 plays and 154 sonnets. In the BCS English literature section (15 marks), at least 2 to 3 questions are directly set from his tragedies and famous quotes.",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );
        $guideShake->sections()->delete();
        $guideShake->sections()->createMany([
            [
                'title' => '1. The 4 Great Tragedies (Mnemonic: HOKL)',
                'content' => "1. Hamlet (1601): Tragedy of procrastination / Prince of Denmark. ('Frailty, thy name is woman', 'To be or not to be, that is the question')\n2. Othello (1603): Tragedy of sexual jealousy / The Moor of Venice. Villain: Iago.\n3. King Lear (1605): Tragedy of filial ingratitude / Daughters: Goneril, Regan, Cordelia. ('My love's more richer than my tongue')\n4. Macbeth (1606): Tragedy of vaulting ambition. ('Fair is foul, and foul is fair', 'Life's but a walking shadow')",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '2. Romantic Period Giants (1798–1837)',
                'content' => "• William Wordsworth: Poet of Nature. (Lyrical Ballads published in 1798 launched Romanticism)\n• S. T. Coleridge: Poet of Supernaturalism. ('The Rime of the Ancient Mariner')\n• John Keats: Poet of Beauty & Sensuousness. ('A thing of beauty is a joy forever', 'Beauty is truth, truth beauty')\n• P. B. Shelley: Revolutionary Poet. ('Ode to the West Wind': 'If Winter comes, can Spring be far behind?')",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 8: Math - Percentage & Profit Loss
        $guideMath1 = StudyGuide::updateOrCreate(
            ['slug' => 'percentage-and-profit-loss-hacks'],
            [
                'topic_id' => $percentage->id,
                'title' => 'শতকরা ও লাভ-ক্ষতির অংক নিমেষে সমাধানের শর্টকাট টেকনিক',
                'summary' => 'পরীক্ষার হলে ৩০ সেকেন্ডের মধ্যে লাভ-ক্ষতি ও শতকরা সমস্যার সমাধান বের করার উপায়।',
                'content' => "বিসিএস প্রিলিমিনারি ও ব্যাংক নিয়োগ পরীক্ষায় গণিত অংশে শতকরা ও লাভ-ক্ষতি থেকে নিশ্চিত ২-৩টি প্রশ্ন আসে। গতানুগতিক সমীকরণ পদ্ধতিতে করলে সময় নষ্ট হয়, তাই শর্টকাট ফর্মুলা আয়ত্ত করা অত্যন্ত জরুরি।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ]
        );
        $guideMath1->sections()->delete();
        $guideMath1->sections()->createMany([
            [
                'title' => '১. মৌলিক সূত্রাবলী (Basic Formulas)',
                'content' => "• লাভ = বিক্রয়মূল্য - ক্রয়মূল্য\n• ক্ষতি = ক্রয়মূল্য - বিক্রয়মূল্য\n• শতকরা লাভ = (মোট লাভ / ক্রয়মূল্য) × ১০০%\n• শতকরা ক্ষতি = (মোট ক্ষতি / ক্রয়মূল্য) × ১০০%\n\nমনে রাখবেন: শতকরা লাভ বা ক্ষতি সর্বদা ক্রয়মূল্যের (Cost Price) ওপর হিসাব করা হয়।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. কার্যকর শর্টকাট সূত্র: পরপর দুবার পরিবর্তন',
                'content' => "কোনো জিনিসের দাম বা আয় প্রথমে a% বৃদ্ধি এবং পরে b% বৃদ্ধি বা হ্রাস পেলে সামগ্রিক নিট পরিবর্তন:\nNet Change = a + b + (a × b / 100)%\n\n(বৃদ্ধি পেলে ধনাত্মক +, হ্রাস পেলে ঋণাত্মক - চিহ্ন বসাতে হবে)\n\nউদাহরণ:\nচিনির মূল্য ২০% বৃদ্ধি পেল কিন্তু খরচ ২০% কমানো হলো। খরচের শতকরা কত পরিবর্তন হবে?\nসমাধান:\n= ২০ - ২০ + (২০ × -২০ / ১০০)\n= ০ - ৪ = -৪%\nঅর্থাৎ, খরচ ৪% হ্রাস পাবে।",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '৩. বিগত বিসিএস পরীক্ষার বাস্তব উদাহরণ',
                'content' => "প্রশ্ন: একটি ছাগল ১০০ টাকায় কিনে ১০% লাভে বিক্রি করা হলো। পরে ক্রেতা সেটি ৫% ক্ষতিতে বিক্রি করলে সর্বশেষ বিক্রয়মূল্য কত?\nসমাধান:\nপ্রথম বিক্রয়মূল্য = ১০০ × ১.১০ = ১১০ টাকা।\nদ্বিতীয় বিক্রয়মূল্য = ১১০ × ০.৯৫ = ১০৪.৫০ টাকা।",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 3,
            ],
        ]);

        // Guide 9: Math - Simple & Compound Interest
        $guideInterest = StudyGuide::updateOrCreate(
            ['slug' => 'simple-and-compound-interest-shortcut'],
            [
                'topic_id' => $interest->id,
                'title' => 'সরল সুদ ও চক্রবৃদ্ধি মুনাফার ম্যাজিক ফর্মুলা (I = Pnr)',
                'summary' => 'ব্যাংক ও বিসিএসের জটিল সুদকষা সমস্যা কয়েক লাইনে নির্ভুল সমাধানের উপায়।',
                'content' => "মুনাফা সংক্রান্ত গাণিতিক সমস্যা মূলত দুই ধরনের: সরল মুনাফা (Simple Interest) এবং চক্রবৃদ্ধি মুনাফা (Compound Profit)। ব্যাংকের পরীক্ষায় প্রায়ই ২ বছর বা ৩ বছরের চক্রবৃদ্ধি ও সরল মুনাফার পার্থক্য চাওয়া হয়।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );
        $guideInterest->sections()->delete();
        $guideInterest->sections()->createMany([
            [
                'title' => '১. সরল মুনাফা সূত্র: I = Pnr',
                'content' => "যেখানে:\n• I = মোট মুনাফা (Interest)\n• P = মূলধন / আসল (Principal)\n• n = সময় (বছর)\n• r = মুনাফার হার (Rate of Interest = r/100)\n\nমুনাফা-আসল A = P + I = P(1 + nr)",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. ২ বছরের চক্রবৃদ্ধি ও সরল মুনাফার পার্থক্যের সুপার শর্টকাট',
                'content' => "Difference for 2 years = P × (r/100)²\n\nউদাহরণ: ৫% হারে ১০০০ টাকার ২ বছরের চক্রবৃদ্ধি ও সরল মুনাফার পার্থক্য কত?\nDifference = ১০০০ × (৫/১০০)² = ১০০০ × (২৫/১০০০০) = ২.৫ টাকা।",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
        ]);

        // Guide 10: General Knowledge - Constitution
        $guideConst = StudyGuide::updateOrCreate(
            ['slug' => 'bangladesh-constitution-important-articles'],
            [
                'topic_id' => $constitution->id,
                'title' => 'বাংলাদেশের সংবিধান: গুরুত্বপূর্ণ অনুচ্ছেদ ও বিগত সালের পর্যালোচনা',
                'summary' => 'সংবিধানের ৪টি মূলনীতি, মৌলিক অধিকারের ১৮টি অনুচ্ছেদ এবং বিসিএসের জন্য গুরুত্বপূর্ণ অনুচ্ছেদ তালিকা।',
                'content' => "গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান ১৯৭২ সালের ৪ নভেম্বর গণপরিষদে গৃহীত হয় এবং একই বছরের ১৬ ডিসেম্বর (বিজয় দিবস) থেকে কার্যকর হয়। এতে মোট ১৫৩টি অনুচ্ছেদ, ১১টি ভাগ ও ৭টি তফসিল রয়েছে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );
        $guideConst->sections()->delete();
        $guideConst->sections()->createMany([
            [
                'title' => '১. মৌলিক চার রাষ্ট্রনীতি ও অনুচ্ছেদসমূহ',
                'content' => "সংবিধানের ৮ নং অনুচ্ছেদে ৪টি মূল রাষ্ট্রনীতির উল্লেখ রয়েছে:\n১. জাতীয়তাবাদ (অনুচ্ছেদ ৯)\n২. সমাজতন্ত্র ও শোষণমুক্তি (অনুচ্ছেদ ১০)\n৩. গণতন্ত্র ও মানবাধিকার (অনুচ্ছেদ ১১)\n৪. ধর্মনিরপেক্ষতা ও ধর্মীয় স্বাধীনতা (অনুচ্ছেদ ১২)",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. পরীক্ষায় বারবার আসা মৌলিক অধিকারের অনুচ্ছেদ',
                'content' => "• অনুচ্ছেদ ২৭: আইনের দৃষ্টিতে সমতা\n• অনুচ্ছেদ ২৮: ধর্ম প্রভৃতি কারণে বৈষম্য নিষিদ্ধ\n• অনুচ্ছেদ ২৯: সরকারি নিয়োগ লাভে সুযোগের সমতা\n• অনুচ্ছেদ ৩১: আইনের আশ্রয় লাভের অধিকার\n• অনুচ্ছেদ ৩২: জীবন ও ব্যক্তি-স্বাধীনতার অধিকার রক্ষা\n• অনুচ্ছেদ ৩৬: চলাফেরার স্বাধীনতা\n• অনুচ্ছেদ ৩৭: সমাবেশের স্বাধীনতা\n• অনুচ্ছেদ ৩৮: সংগঠনের স্বাধীনতা\n• অনুচ্ছেদ ৩৯: চিন্তা, বিবেক ও বাক-স্বাধীনতা\n• অনুচ্ছেদ ৪৪: মৌলিক অধিকার বলবৎকরণ (হাইকোর্টে রিট দায়ের)",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 11: General Knowledge - Liberation War 1971
        $guideWar = StudyGuide::updateOrCreate(
            ['slug' => 'liberation-war-1971-mujibnagar-government'],
            [
                'topic_id' => $liberationWar->id,
                'title' => '১৯৭১ সালের মুক্তিযুদ্ধ: মুজিবনগর সরকার, ১১টি সেক্টর ও ৭ বীরশ্রেষ্ঠ',
                'summary' => '১০ এপ্রিল স্বাধীনতার ঘোষণাপত্র, ১৭ এপ্রিল শপথ গ্রহণ, সেক্টর কমান্ডারদের দায়িত্ব ও বীরত্বসূচক খেতাব।',
                'content' => "১৯৭১ সালের মহান মুক্তিযুদ্ধ বাঙালি জাতির ইতিহাসের সর্বশ্রেষ্ঠ গৌরবময় অধ্যায়। ২৫ মার্চ মধ্যরাতে পাকিস্তানি হানাদার বাহিনী নিরস্ত্র বাঙালির ওপর 'অপারেশন সার্চলাইট' চালায়। ২৬ মার্চ প্রথম প্রহরে বঙ্গবন্ধু শেখ মুজিবুর রহমান স্বাধীনতার আনুষ্ঠানিক ঘোষণা প্রদান করেন।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );
        $guideWar->sections()->delete();
        $guideWar->sections()->createMany([
            [
                'title' => '১. মুজিবনগর সরকার (গঠন ও শপথ)',
                'content' => "• সরকার গঠিত হয়: ১০ এপ্রিল ১৯৭১ (আগরতলায়)\n• স্বাধীনতার ঘোষণাপত্র গৃহীত হয়: ১০ এপ্রিল ১৯৭১ (কার্যকর ঘোষণা করা হয় ২৬ মার্চ ১৯৭১ থেকে)\n• শপথ গ্রহণ: ১৭ এপ্রিল ১৯৭১ (কুষ্টিয়া জেলার মেহেরপুরের বৈদ্যনাথতলার ভবেরপাড়ায়, বর্তমান মুজিবনগর)\n• রাষ্ট্রপতি: বঙ্গবন্ধু শেখ মুজিবুর রহমান (পাকিস্তানে বন্দি ছিলেন)\n• অস্থায়ী রাষ্ট্রপতি: সৈয়দ নজরুল ইসলাম\n• প্রধানমন্ত্রী: তাজউদ্দীন আহমদ\n• অর্থমন্ত্রী: এম মনসুর আলী\n• স্বরাষ্ট্র, ত্রাণ ও পুনর্বাসন মন্ত্রী: এ এইচ এম কামারুজ্জামান\n• প্রধান সেনাপতি: কর্নেল (পরবর্তীতে জেনারেল) এম এ জি ওসমানী",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. ৭ জন বীরশ্রেষ্ঠের নাম ও কর্মক্ষেত্র',
                'content' => "১. ক্যাপ্টেন মহিউদ্দিন জাহাঙ্গীর (সেনাবাহিনী)\n২. সিপাহী হামিদুর রহমান (সেনাবাহিনী - কনিষ্ঠতম বীরশ্রেষ্ঠ)\n৩. সিপাহী মোস্তফা কামাল (সেনাবাহিনী)\n৪. ইঞ্জিনরুম আর্টিফিসার মোহাম্মদ রুহুল আমিন (নৌবাহিনী)\n৫. ফ্লাইট লেফটেন্যান্ট মতিউর রহমান (বিমানবাহিনী)\n৬. ল্যান্স নায়েক নূর মোহাম্মদ শেখ (ইপিআর/বিজিবি)\n৭. ল্যান্স নায়েক মুন্সী আব্দুর রউফ (ইপিআর/বিজিবি)",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 12: ICT - Memory & Storage Systems
        $guideICT = StudyGuide::updateOrCreate(
            ['slug' => 'computer-memory-and-storage-systems'],
            [
                'topic_id' => $ictBasics->id,
                'title' => 'কম্পিউটার মেমোরি সিস্টেম ও স্টোরেজ ইউনিট কনভার্শন',
                'summary' => 'RAM, ROM, Cache Memory, Virtual Memory ও ডেটা পরিমাপের একক সংক্রান্ত বিসিএস প্রস্তুতি।',
                'content' => "কম্পিউটারের তথ্য সংরক্ষণের জন্য মেমোরি অত্যন্ত গুরুত্বপূর্ণ উপাদান। মেমোরিকে প্রধানত দুটি শ্রেণিতে ভাগ করা যায়: প্রাইমারি/প্রধান মেমোরি (RAM, ROM) এবং সেকেন্ডারি/সহায়ক মেমোরি (Hard Disk, SSD, Flash Drive)। বিসিএস ও ব্যাংক নিয়োগ পরীক্ষায় মেমোরির গতি ও ডেটা কনভার্শন থেকে নিয়মিত প্রশ্ন আসে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(1),
                'created_by' => $adminId,
            ]
        );
        $guideICT->sections()->delete();
        $guideICT->sections()->createMany([
            [
                'title' => '১. মেমোরির গতির ক্রম (Speed Hierarchy)',
                'content' => "সর্বোচ্চ গতি থেকে সর্বনিম্ন গতি:\nRegister (সর্বাধিক দ্রুত) > Cache Memory (L1, L2, L3) > Main Memory (RAM) > Secondary Storage (SSD > HDD)\n\n• Register: প্রসেসরের নিজস্ব অতি দ্রুত মেমোরি।\n• Cache Memory: CPU ও RAM এর মধ্যে গতির ভারসাম্য রক্ষা করে।\n• RAM: Volatile বা উদ্বায়ী (বিদ্যুৎ চলে গেলে ডেটা মুছে যায়)।\n• ROM: Non-volatile বা স্থায়ী (BIOS প্রোগ্রাম সংরক্ষিত থাকে)।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. ডেটা পরিমাপের একক ও বিগত সালের প্রশ্ন',
                'content' => "• ১ Byte = 8 Bits\n• ১ Nibble = 4 Bits\n• ১ Kilobyte (KB) = 1024 Bytes\n• ১ Megabyte (MB) = 1024 KB\n• ১ Gigabyte (GB) = 1024 MB\n• ১ Terabyte (TB) = 1024 GB\n\nবিগত প্রশ্ন:\n• ১ নিবল সমান কত বিট? [উত্তর: ৪ বিট - ৪০তম বিসিএস]\n• কোনটি উদ্বায়ী (Volatile) মেমোরি? [উত্তর: RAM - ৪৩তম বিসিএস]",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 2,
            ],
        ]);

        // Guide 13: Technical Subjects - DBMS & SQL
        $guideDBMS = StudyGuide::updateOrCreate(
            ['slug' => 'database-management-system-and-sql'],
            [
                'topic_id' => $cse->id,
                'title' => 'ডাটাবেস ম্যানেজমেন্ট সিস্টেম (DBMS), নরমালাইজেশন ও SQL কুয়েরি',
                'summary' => 'টেকনিক্যাল ক্যাডার ও ব্যাংক আইটি অফিসার পদের জন্য রিলেশনাল ডাটাবেস ও ACID প্রোপার্টিজ।',
                'content' => "কম্পিউটার সায়েন্স সংশ্লিষ্ট টেকনিক্যাল চাকরি (যেমন বাংলাদেশ ব্যাংক আইটি অফিসার, সোনালী/জনতা ব্যাংক সিনিয়র অফিসার আইটি, বিসিএস তথ্যপ্রযুক্তি ক্যাডার) পরীক্ষায় ডাটাবেস আর্কিটেকচার ও এসকিউএল কুয়েরি থেকে সর্বাধিক প্রশ্ন আসে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(1),
                'created_by' => $adminId,
            ]
        );
        $guideDBMS->sections()->delete();
        $guideDBMS->sections()->createMany([
            [
                'title' => '১. ACID Properties (ট্রানজেকশনের মূল ৪ বৈশিষ্ট্য)',
                'content' => "• Atomicity (অল অর নাথিং): লেনদেনের সকল কাজ সফল হবে অথবা কোনোটিই হবে না।\n• Consistency (সঙ্গতি): ডাটাবেসের সকল নিয়ম ও কনস্ট্রেইন্ট সংরক্ষিত থাকবে।\n• Isolation (বিচ্ছিন্নতা): একটি ট্রানজেকশনের কাজ অন্য চলমান ট্রানজেকশনকে প্রভাবিত করবে না।\n• Durability (স্থায়িত্ব): সফল ট্রানজেকশনের ফলাফল স্থায়ীভাবে ডাটাবেসে সেভ থাকবে।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. ডাটাবেস কি (Keys) এবং নরমালাইজেশন',
                'content' => "• Primary Key: টেবিলে প্রতিটি রেকর্ডকে অদ্বিতীয়ভাবে চিহ্নিত করে (কখনও NULL হতে পারে না)।\n• Foreign Key: দুটি টেবিলের মধ্যে সম্পর্ক (Relationship) স্থাপন করে।\n• Candidate Key: যেসকল অ্যাট্রিবিউট প্রাইমারি কি হওয়ার যোগ্যতা রাখে।\n• 1NF: কোনো ফিল্ডে মাল্টি-ভ্যালুড ডেটা থাকবে না (Atomic Values)।\n• 2NF: 1NF হতে হবে এবং কোনো আংশিক নির্ভরতা (Partial Dependency) থাকবে না।\n• 3NF: 2NF হতে হবে এবং ট্রানজিটিভ নির্ভরতা (Transitive Dependency) থাকবে না।",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);

        // Guide 14: Technical Subjects - Logic Gates
        $guideLogic = StudyGuide::updateOrCreate(
            ['slug' => 'digital-logic-gates-and-boolean-algebra'],
            [
                'topic_id' => $eee->id,
                'title' => 'ডিজিটাল লজিক গেট, ইউনিভার্সাল গেট ও বুলিয়ান অ্যালজেব্রা',
                'summary' => 'AND, OR, NOT, NAND, NOR, XOR গেটের ট্রুথ টেবিল ও ডিমরগানের উপপাদ্য।',
                'content' => "ডিজিটাল ইলেকট্রনিক্স ও টেকনিক্যাল নিয়োগ পরীক্ষায় মৌলিক ও সার্বজনীন লজিক গেটের কার্যাবলী এবং বুলিয়ান রাশিমালা সরলীকরণ থেকে প্রতি বছর প্রশ্ন করা হয়।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(1),
                'created_by' => $adminId,
            ]
        );
        $guideLogic->sections()->delete();
        $guideLogic->sections()->createMany([
            [
                'title' => '১. লজিক গেটের প্রকারভেদ ও ইউনিভার্সাল গেট',
                'content' => "• মৌলিক গেট (Basic Gates): ৩টি - AND, OR, NOT\n• সার্বজনীন গেট (Universal Gates): ২টি - NAND, NOR (এদের দ্বারা যেকোনো গেট তৈরি সম্ভব)\n• বিশেষ গেট (Special Gates): ২টি - XOR, XNOR\n\nXOR গেটের আউটপুট সমীকরণ: Y = A ⊕ B = A'B + AB'\n(ইনপুটের মান ভিন্ন হলে আউটপুট ১ হবে; ইনপুট এক হলে আউটপুট ০ হবে)",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. ডি মরগানের উপপাদ্য (De Morgan\'s Theorems)',
                'content' => "১. (A + B)' = A' . B'\n(যোগের সার্বিক কমপ্লিমেন্ট সমান আলাদা কমপ্লিমেন্টের গুণফল)\n\n২. (A . B)' = A' + B'\n(গুণের সার্বিক কমপ্লিমেন্ট সমান আলাদা কমপ্লিমেন্টের যোগফল)",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
        ]);
    }
}
