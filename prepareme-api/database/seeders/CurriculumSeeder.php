<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\SectionType;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        // 1. Bangla
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
                'name' => 'বাংলা ব্যাকরণ',
                'description' => 'ধ্বনি, বর্ণ, সন্ধি, সমাস, কারক ও বিভক্তি সংক্রান্ত অধ্যায়সমূহ।',
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
                'description' => 'স্বরসন্ধি ও ব্যঞ্জনসন্ধির নিয়ম এবং নিপাতনে সিদ্ধ সন্ধি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        $samas = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'samas'],
            [
                'parent_id' => $grammar->id,
                'name' => 'সমাস নির্ণয় ও প্রকারভেদ',
                'description' => 'দ্বন্দ, কর্মধারয়, তৎপুরুষ, বহুব্রীহি, দ্বিগু ও অব্যয়ীভাব সমাস।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        $literature = Topic::updateOrCreate(
            ['subject_id' => $bangla->id, 'slug' => 'bangla-literature'],
            [
                'name' => 'বাংলা সাহিত্য',
                'description' => 'প্রাচীন যুগ (চর্যাপদ), মধ্যযুগ (মঙ্গলকাব্য, বৈষ্ণব পদাবলী) এবং আধুনিক যুগ।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // 2. English
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
                'name' => 'English Grammar & Usage',
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
                'name' => 'Right Form of Verbs & Subject-Verb Agreement',
                'description' => 'Essential grammar rules frequently tested in BCS and Bank exams.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        // 3. Mathematics
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
                'name' => 'পাটিগণিত',
                'description' => 'শতকরা, লাভ-ক্ষতি, সুদকষা, অনুপাত ও ঐকিক নিয়ম।',
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
                'description' => 'শতকরার দ্রুত হিসাব এবং লাভ ও ক্ষতির গাণিতিক সমস্যা।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        // 4. General Knowledge
        $gk = Subject::updateOrCreate(
            ['slug' => 'general-knowledge'],
            [
                'name' => 'সাধারণ জ্ঞান (বাংলাদেশ ও আন্তর্জাতিক)',
                'description' => 'বাংলাদেশের সংবিধান, মুক্তিযুদ্ধ, ভূ-রাজনীতি, আন্তর্জাতিক সংস্থা ও সাম্প্রতিক বিষয়াবলী।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 4,
                'created_by' => $adminId,
            ]
        );

        $bdAffairs = Topic::updateOrCreate(
            ['subject_id' => $gk->id, 'slug' => 'bangladesh-affairs'],
            [
                'name' => 'বাংলাদেশ বিষয়াবলী',
                'description' => 'মুক্তিযুদ্ধ, ভৌগোলিক অবস্থান, জাতীয় সংসদ ও সংবিধান।',
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

        // 5. ICT
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

        $networking = Topic::updateOrCreate(
            ['subject_id' => $ict->id, 'slug' => 'computer-networking'],
            [
                'name' => 'কম্পিউটার নেটওয়ার্ক ও ইন্টারনেট',
                'description' => 'LAN, WAN, IP Address, OSI Model এবং ইন্টারনেট প্রোটোকল।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        // 6. Technical Subjects
        $tech = Subject::updateOrCreate(
            ['slug' => 'technical-subjects'],
            [
                'name' => 'প্রকৌশল ও টেকনিক্যাল বিষয়াবলী',
                'description' => 'কম্পিউটার সায়েন্স, তড়িৎ ও যন্ত্রকৌশল সংশ্লিষ্ট পদের টেকনিক্যাল পরীক্ষা প্রস্তুতি।',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 6,
                'created_by' => $adminId,
            ]
        );

        // Seed Study Guides with Sections
        // Guide 1: Sandhi
        $guide1 = StudyGuide::updateOrCreate(
            ['slug' => 'mastering-sandhi-rules'],
            [
                'topic_id' => $sandhi->id,
                'title' => 'বিসিএস পরীক্ষার জন্য সন্ধির পূর্ণাঙ্গ নিয়ম ও শর্টকাট কৌশল',
                'summary' => 'বাংলা ব্যাকরণে সন্ধির প্রধান নিয়ম, খাঁটি বাংলা সন্ধি এবং নিপাতনে সিদ্ধ সন্ধির নির্ভুল তালিকা।',
                'content' => "সন্ধি শব্দের অর্থ মিলন। পাশাপাশি অবস্থিত দুটি ধ্বনির মিলনকে সন্ধি বলে। দ্রুত উচ্চারণের ফলে সন্নিহিত দুটি ধ্বনির একটির প্রভাবে অন্যটি পরিবর্তিত হলে কিংবা উভয়ের মিলনে নতুন ধ্বনির সৃষ্টি হলে তাকে সন্ধি বলা হয়।\n\nবাংলা ব্যাকরণে সন্ধি দুই প্রকার: স্বরসন্ধি ও ব্যঞ্জনসন্ধি। কিন্তু সংস্কৃত ব্যাকরণে সন্ধি তিন প্রকার: স্বরসন্ধি, ব্যঞ্জনসন্ধি ও বিসর্গ সন্ধি। চাকরি পরীক্ষায় বেশিরভাগ প্রশ্ন সংস্কৃত নিয়ম এবং বিশেষ নিপাতনে সিদ্ধ সন্ধি থেকে এসে থাকে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ]
        );

        $guide1->sections()->delete();
        $guide1->sections()->createMany([
            [
                'title' => '১. মৌলিক ধারণা ও স্বরসন্ধির নিয়ম',
                'content' => "অ-কার কিংবা আ-কারের পর অ-কার কিংবা আ-কার থাকলে উভয়ে মিলে আ-কার হয়। আ-কার পূর্ববর্তী ব্যঞ্জনের সাথে যুক্ত হয়।\n\nউদাহরণ:\n• নর + অধম = নরাধম\n• হিম + আলয় = হিমালয়\n• বিদ্যা + আলয় = বিদ্যালয়\n• মহা + আশয় = মহাশয়",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. চাকরি পরীক্ষায় সর্বাধিক আসা নিপাতনে সিদ্ধ সন্ধি',
                'content' => "যেসব সন্ধি কোনো সাধারণ নিয়মের অধীনে পড়ে না, সেগুলোকে 'নিপাতনে সিদ্ধ সন্ধি' বলা হয়। পরীক্ষায় এই শব্দগুলো বারবার আসে:\n\n১. কুল + অটা = কুলটা (কুল + আটা নয়)\n২. গো + অক্ষ = গবাক্ষ\n৩. প্র + ঊঢ় = প্রৌঢ়\n৪. অন্য + অন্য = অন্যান্য\n৫. বন + পতি = বনস্পতি\n৬. বৃহৎ + পতি = বৃহস্পতি\n৭. তৎ + কর = তস্কর\n৮. আ + চর্য = আশ্চর্য\n৯. পতৎ + অঞ্জলি = পতঞ্জলি\n১০. ষট্ + দশ = ষোড়শ",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '৩. দ্রুত মনে রাখার সূত্র ও সারসংক্ষেপ',
                'content' => "বিসর্গ সন্ধির একটি চমৎকার শর্টকাট সূত্র:\nশব্দের মাঝে ও-কার (ো), রেফ (র্), তালব্য-শ (শ), মূর্ধন্য-ষ (ষ), বা দন্ত্য-স (স) থাকলে এদের পরিবর্তে বিসর্গ (ঃ) বসিয়ে প্রথম অংশটি আলাদা করা যায়।\nযেমন: মনস্তাপ = মনঃ + তাপ, তিরোধান = তিরঃ + ধান, শিরচ্ছেদ = শিরঃ + ছেদ।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 3,
            ],
            [
                'title' => '৪. অনুশীলন ও বিগত সালের প্রশ্ন',
                'content' => "বিগত বিসিএস পরীক্ষার প্রশ্নসমূহ সমাধান করুন:\nক) 'গায়ক' শব্দের সঠিক সন্ধি বিচ্ছেদ কোনটি? [উত্তর: গৈ + অক]\nখ) 'পদ্ধতি' শব্দের সন্ধি বিচ্ছেদ কোনটি? [উত্তর: পদ্ + হতি]\nগ) 'রবীন্দ্র' শব্দের সন্ধি বিচ্ছেদ কোনটি? [উত্তর: রবি + ইন্দ্র]",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 4,
            ],
        ]);

        // Guide 2: Math Percentage & Profit Loss
        $guide2 = StudyGuide::updateOrCreate(
            ['slug' => 'percentage-and-profit-loss-hacks'],
            [
                'topic_id' => $percentage->id,
                'title' => 'শতকরা ও লাভ-ক্ষতির অংক নিমেষে সমাধানের শর্টকাট টেকনিক',
                'summary' => 'পরীক্ষার হলে ৩০ সেকেন্ডের মধ্যে লাভ-ক্ষতি ও শতকরা সমস্যার সমাধান বের করার উপায়।',
                'content' => "বিসিএস প্রিলিমিনারি ও ব্যাংক নিয়োগ পরীক্ষায় গণিত অংশে শতকরা ও লাভ-ক্ষতি থেকে নিশ্চিত ২-৩টি প্রশ্ন আসে। গতানুগতিক সমীকরণ পদ্ধতিতে করলে সময় নষ্ট হয়, তাই শর্টকাট ফর্মুলা আয়ত্ত করা অত্যন্ত জরুরি।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ]
        );

        $guide2->sections()->delete();
        $guide2->sections()->createMany([
            [
                'title' => '১. মৌলিক সূত্রাবলী (Basic Formulas)',
                'content' => "• লাভ = বিক্রয়মূল্য - ক্রয়মূল্য\n• ক্ষতি = ক্রয়মূল্য - বিক্রয়মূল্য\n• শতকরা লাভ = (মোট লাভ / ক্রয়মূল্য) × ১০০%\n• শতকরা ক্ষতি = (মোট ক্ষতি / ক্রয়মূল্য) × ১০০%\n\nমনে রাখবেন: শতকরা লাভ বা ক্ষতি সর্বদা ক্রয়মূল্যের (Cost Price) ওপর হিসাব করা হয়।",
                'section_type' => SectionType::FORMULA,
                'sort_order' => 1,
            ],
            [
                'title' => '২. কার্যকর শর্টকাট সূত্র: পরপর দুবার বৃদ্ধি বা হ্রাস',
                'content' => "কোনো জিনিসের দাম বা আয় প্রথমে a% বৃদ্ধি এবং পরে b% বৃদ্ধি বা হ্রাস পেলে মোট পরিবর্তন:\nNet Change = a + b + (a × b / 100)%\n\nলক্ষ্য করুন: বৃদ্ধি পেলে (+), আর হ্রাস পেলে (-) চিহ্ন ব্যবহার করতে হবে।\n\nউদাহরণ:\nচিনির মূল্য ২০% বৃদ্ধি পেল কিন্তু ব্যবহার ২০% কমানো হলো। খরচের শতকরা কত পরিবর্তন হবে?\nসমাধান:\n= ২০ - ২০ + (২০ × -২০ / ১০০)\n= ০ - (৪০০ / ১০০) = -৪%\nঅর্থাৎ, খরচ ৪% হ্রাস পাবে।",
                'section_type' => SectionType::EXAMPLE,
                'sort_order' => 2,
            ],
            [
                'title' => '৩. বিগত বিসিএস পরীক্ষার বাস্তব উদাহরণ',
                'content' => "প্রশ্ন: একটি ছাগল ১০০ টাকায় কিনে ১০% লাভে বিক্রি করা হলো। পরে ক্রেতা সেটি ৫% ক্ষতিতে বিক্রি করলে সর্বশেষ বিক্রয়মূল্য কত?\nসমাধান:\nপ্রথম বিক্রয়মূল্য = ১০০ × ১.১০ = ১১০ টাকা।\nদ্বিতীয় বিক্রয়মূল্য = ১১০ × ০.৯৫ = ১০৪.৫০ টাকা।",
                'section_type' => SectionType::PRACTICE,
                'sort_order' => 3,
            ],
        ]);

        // Guide 3: Constitution of Bangladesh
        $guide3 = StudyGuide::updateOrCreate(
            ['slug' => 'bangladesh-constitution-important-articles'],
            [
                'topic_id' => $constitution->id,
                'title' => 'বাংলাদেশের সংবিধান: গুরুত্বপূর্ণ অনুচ্ছেদ ও বিগত সালের পর্যালোচনা',
                'summary' => 'সংবিধানের ৪টি মূলনীতি, মৌলিক অধিকারের ১৮টি অনুচ্ছেদ এবং বিসিএসের জন্য গুরুত্বপূর্ণ অনুচ্ছেদ তালিকা।',
                'content' => "গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান ১৯৭২ সালের ৪ নভেম্বর গণপরিষদে গৃহীত হয় এবং একই বছরের ১৬ ডিসেম্বর (বিজয় দিবস) থেকে কার্যকর হয়। এতে মোট ১৫৩টি অনুচ্ছেদ, ১১টি ভাগ ও ৭টি তফসিল রয়েছে।",
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ]
        );

        $guide3->sections()->delete();
        $guide3->sections()->createMany([
            [
                'title' => '১. মৌলিক চার রাষ্ট্রনীতি ও অনুচ্ছেদসমূহ',
                'content' => "সংবিধানের ৮ নং অনুচ্ছেদে ৪টি মূল রাষ্ট্রনীতির উল্লেখ রয়েছে:\n১. জাতীয়তাবাদ (অনুচ্ছেদ ৯)\n২. সমাজতন্ত্র ও শোষণমুক্তি (অনুচ্ছেদ ১০)\n৩. গণতন্ত্র ও মানবাধিকার (অনুচ্ছেদ ১১)\n৪. ধর্মনিরপেক্ষতা ও ধর্মীয় স্বাধীনতা (অনুচ্ছেদ ১২)",
                'section_type' => SectionType::EXPLANATION,
                'sort_order' => 1,
            ],
            [
                'title' => '২. পরীক্ষায় বারবার আসা মৌলিক অধিকারের অনুচ্ছেদ',
                'content' => "• অনুচ্ছেদ ২৭: আইনের দৃষ্টিতে সমতা\n• অনুচ্ছেদ ২৮: ধর্ম প্রভৃতি কারণে বৈষম্য নিষিদ্ধ\n• অনুচ্ছেদ ২৯: সরকারি নিয়োগ লাভে সুযোগের সমতা\n• অনুচ্ছেদ ৩১: আইনের আশ্রয় লাভের অধিকার\n• অনুচ্ছেদ ৩২: জীবন ও ব্যক্তি-স্বাধীনতার অধিকার রক্ষা\n• অনুচ্ছেদ ৩৬: চলাফেরার স্বাধীনতা\n• অনুচ্ছেদ ৩৭: সমাবেশের স্বাধীনতা\n• অনুচ্ছেদ ৩৮: সংগঠনের স্বাধীনতা\n• অনুচ্ছেদ ৩৯: চিন্তা, বিবেক ও বাক-স্বাধীনতা\n• অনুচ্ছেদ ৪৪: মৌলিক অধিকার বলবৎকরণ (হাইকোর্টে রিট)",
                'section_type' => SectionType::SUMMARY,
                'sort_order' => 2,
            ],
        ]);
    }
}
