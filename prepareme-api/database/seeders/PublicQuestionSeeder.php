<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Models\PublicQuestion;
use App\Models\StudyGuide;
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
        $tech = Subject::where('slug', 'technical-subjects')->first();

        // Topics
        $sandhi = Topic::where('slug', 'sandhi')->first();
        $samas = Topic::where('slug', 'samas')->first();
        $charya = Topic::where('slug', 'ancient-and-medieval-literature')->first();
        $tagore = Topic::where('slug', 'tagore-and-nazrul')->first();
        $verbs = Topic::where('slug', 'right-form-of-verbs')->first();
        $prepositions = Topic::where('slug', 'appropriate-prepositions-and-idioms')->first();
        $shakespeare = Topic::where('slug', 'shakespeare-and-romantic-poets')->first();
        $percentage = Topic::where('slug', 'percentage-and-profit-loss')->first();
        $interest = Topic::where('slug', 'simple-and-compound-interest')->first();
        $constitution = Topic::where('slug', 'constitution-of-bangladesh')->first();
        $war = Topic::where('slug', 'liberation-war-1971')->first();
        $memTopic = Topic::where('slug', 'computer-hardware-and-memory')->first();
        $networking = Topic::where('slug', 'computer-networking')->first();
        $cse = Topic::where('slug', 'computer-science-fundamentals')->first();
        $eee = Topic::where('slug', 'basic-electrical-and-electronics')->first();

        // Guides
        $guideSandhi = StudyGuide::where('slug', 'mastering-sandhi-rules')->first();
        $guideSamas = StudyGuide::where('slug', 'samas-shortcut-rules')->first();
        $guideCharya = StudyGuide::where('slug', 'charyapada-ancient-literature')->first();
        $guideTagore = StudyGuide::where('slug', 'rabindranath-and-kazi-nazrul-islam-mastery')->first();
        $guideSVA = StudyGuide::where('slug', 'subject-verb-agreement-golden-rules')->first();
        $guidePrep = StudyGuide::where('slug', 'high-frequency-prepositions-and-idioms')->first();
        $guideShake = StudyGuide::where('slug', 'william-shakespeare-plays-and-quotes')->first();
        $guideMath1 = StudyGuide::where('slug', 'percentage-and-profit-loss-hacks')->first();
        $guideInterest = StudyGuide::where('slug', 'simple-and-compound-interest-shortcut')->first();
        $guideConst = StudyGuide::where('slug', 'bangladesh-constitution-important-articles')->first();
        $guideWar = StudyGuide::where('slug', 'liberation-war-1971-mujibnagar-government')->first();
        $guideICT = StudyGuide::where('slug', 'computer-memory-and-storage-systems')->first();
        $guideDBMS = StudyGuide::where('slug', 'database-management-system-and-sql')->first();
        $guideLogic = StudyGuide::where('slug', 'digital-logic-gates-and-boolean-algebra')->first();

        $questions = [
            // ========================================================
            // 1. BANGLA
            // ========================================================
            [
                'subject_id' => $bangla->id,
                'topic_id' => $sandhi?->id,
                'study_guide_id' => $guideSandhi?->id,
                'question' => "নিচের কোনটি নিপাতনে সিদ্ধ সন্ধির উদাহরণ?",
                'answer' => "বনস্পতি",
                'explanation' => "যেসব সন্ধি কোনো ব্যাকরণগত সাধারণ নিয়মের অধীনে পড়ে না, সেগুলোকে নিপাতনে সিদ্ধ সন্ধি বলে। যেমন: বন + পতি = বনস্পতি, তৎ + কর = তস্কর, আ + চর্য = আশ্চর্য।",
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
                'study_guide_id' => $guideSandhi?->id,
                'question' => "'গায়ক' শব্দের সঠিক সন্ধি বিচ্ছেদ কোনটি?",
                'answer' => "গৈ + অক",
                'explanation' => "ঐ-কারের পর অ-কার থাকলে উভয় মিলে 'আয়' হয়। তাই গৈ + অক = গায়ক, নৈ + অক = নায়ক। (৩৬তম বিসিএস)",
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
            [
                'subject_id' => $bangla->id,
                'topic_id' => $samas?->id,
                'study_guide_id' => $guideSamas?->id,
                'question' => "'উপকূল' কোন সমাসের উদাহরণ?",
                'answer' => "অব্যয়ীভাব সমাস",
                'explanation' => "পূর্বপদে অব্যয়যোগে নিষ্পন্ন সমাসে যদি অব্যয়ের অর্থই প্রধানরূপে প্রতীয়মান হয়, তবে তাকে অব্যয়ীভাব সমাস বলে। কূলের সমীপে = উপকূল। (৩৭তম বিসিএস)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'তৎপুরুষ সমাস',
                    'B' => 'বহুব্রীহি সমাস',
                    'C' => 'অব্যয়ীভাব সমাস',
                    'D' => 'দ্বিগু সমাস',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $bangla->id,
                'topic_id' => $charya?->id,
                'study_guide_id' => $guideCharya?->id,
                'question' => "চর্যাপদের পুথি হরপ্রসাদ শাস্ত্রী কত সালে কোথা থেকে আবিষ্কার করেন?",
                'answer' => "১৯০৭ সালে নেপালের রাজদরবারের রয়েল লাইব্রেরি থেকে",
                'explanation' => "১৯০৭ খ্রিস্টাব্দে মহামহোপাধ্যায় হরপ্রসাদ শাস্ত্রী নেপালের রাজদরবারের রয়েল লাইব্রেরি থেকে চর্যাপদের পুথি আবিষ্কার করেন। ১৯১৬ সালে বঙ্গীয় সাহিত্য পরিষদ থেকে এটি প্রকাশিত হয়।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '১৯০২ সালে তিব্বত থেকে',
                    'B' => '১৯০৭ সালে নেপালের রাজদরবার থেকে',
                    'C' => '১৯১১ সালে ভুটান থেকে',
                    'D' => '১৯১৬ সালে কলকাতা বিশ্ববিদ্যালয় থেকে',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $bangla->id,
                'topic_id' => $tagore?->id,
                'study_guide_id' => $guideTagore?->id,
                'question' => "রবীন্দ্রনাথ ঠাকুর তাঁর কোন নাটকটি কাজী নজরুল ইসলামকে উৎসর্গ করেছিলেন?",
                'answer' => "বসন্ত",
                'explanation' => "রবীন্দ্রনাথ ঠাকুর তাঁর 'বসন্ত' (১৯২৩) গীতিনাট্যটি কাজী নজরুল ইসলামকে উৎসর্গ করেছিলেন। জবাবে নজরুল জেলখানা থেকে 'আজ সৃষ্টি-সুখের উল্লাসে' কবিতাটি রচনা করেন। (৩৯তম বিসিএস)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'কালের যাত্রা',
                    'B' => 'তাসের দেশ',
                    'C' => 'বসন্ত',
                    'D' => 'রক্তকরবী',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],

            // ========================================================
            // 2. ENGLISH
            // ========================================================
            [
                'subject_id' => $english->id,
                'topic_id' => $verbs?->id,
                'study_guide_id' => $guideSVA?->id,
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
                'study_guide_id' => $guideSVA?->id,
                'question' => "The quality of these mangoes ___ not good.",
                'answer' => "is",
                'explanation' => "The head noun of the sentence is 'quality' (singular), not 'mangoes'. Therefore, it requires the singular verb 'is'. (38th BCS & Bangladesh Bank AD)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'are',
                    'B' => 'were',
                    'C' => 'is',
                    'D' => 'have been',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $english->id,
                'topic_id' => $prepositions?->id,
                'study_guide_id' => $guidePrep?->id,
                'question' => "He died ___ overeating yesterday.",
                'answer' => "from",
                'explanation' => "Rule: Die of disease/illness; Die from overeating/wound/cause; Die by poison/violence; Die for a noble cause. Therefore, 'die from overeating' is correct. (Bank Combined 2021)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'of',
                    'B' => 'by',
                    'C' => 'from',
                    'D' => 'for',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $english->id,
                'topic_id' => $shakespeare?->id,
                'study_guide_id' => $guideShake?->id,
                'question' => "'Frailty, thy name is woman' — In which play does this famous quote appear?",
                'answer' => "Hamlet",
                'explanation' => "This iconic line is spoken by Prince Hamlet in Act 1, Scene 2 of William Shakespeare's tragedy 'Hamlet', referring to his mother Gertrude's hasty remarriage. (40th BCS)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'Othello',
                    'B' => 'Hamlet',
                    'C' => 'Macbeth',
                    'D' => 'King Lear',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],

            // ========================================================
            // 3. MATHEMATICS
            // ========================================================
            [
                'subject_id' => $math->id,
                'topic_id' => $percentage?->id,
                'study_guide_id' => $guideMath1?->id,
                'question' => "একটি পণ্যের দাম ২৫% বৃদ্ধি পেল। পূর্বের দামে ফিরে যেতে হলে বর্তমান দাম শতকরা কত কমাতে হবে?",
                'answer' => "২০%",
                'explanation' => "ধরি পূর্বের দাম ১০০ টাকা। ২৫% বৃদ্ধিতে বর্তমান দাম ১২৫ টাকা।\n১২৫ টাকায় কমাতে হবে ২৫ টাকা।\n১০০ টাকায় কমাতে হবে (২৫ × ১০০) / ১২৫ = ২০%। (৩৫তম বিসিএস)",
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
                'study_guide_id' => $guideMath1?->id,
                'question' => "টাকায় ৩টি করে লেবু কিনে টাকায় ২টি করে বিক্রি করলে শতকরা কত লাভ হবে?",
                'answer' => "৫০%",
                'explanation' => "১টি লেবুর ক্রয়মূল্য ১/৩ টাকা, বিক্রয়মূল্য ১/২ টাকা।\nলাভ = (১/২ - ১/৩) = ১/৬ টাকা।\nশতকরা লাভ = ((১/৬) / (১/৩)) × ১০০% = ৫০%। (৪০তম বিসিএস)",
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
            [
                'subject_id' => $math->id,
                'topic_id' => $interest?->id,
                'study_guide_id' => $guideInterest?->id,
                'question' => "বার্ষিক ৫% হারে ১০০০ টাকার ২ বছরের চক্রবৃদ্ধি ও সরল মুনাফার পার্থক্য কত টাকা?",
                'answer' => "২.৫০ টাকা",
                'explanation' => "২ বছরের চক্রবৃদ্ধি ও সরল মুনাফার পার্থক্যের সূত্র:\nDifference = P × (r/100)² = ১০০০ × (৫/১০০)² = ১০০০ × (২৫ / ১০০০০) = ২.৫ টাকা। (বাংলাদেশ ব্যাংক এডি)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => '৫.০০ টাকা',
                    'B' => '২.৫০ টাকা',
                    'C' => '১.২৫ টাকা',
                    'D' => '১০.০০ টাকা',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::HARD,
                'status' => ContentStatus::PUBLISHED,
            ],

            // ========================================================
            // 4. GENERAL KNOWLEDGE
            // ========================================================
            [
                'subject_id' => $gk->id,
                'topic_id' => $constitution?->id,
                'study_guide_id' => $guideConst?->id,
                'question' => "গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধানের কোন অনুচ্ছেদে 'আইনের দৃষ্টিতে সমতা' নিশ্চিত করা হয়েছে?",
                'answer' => "অনুচ্ছেদ ২৭",
                'explanation' => "সংবিধানের ২৭ নং অনুচ্ছেদ অনুযায়ী: 'সকল নাগরিক আইনের দৃষ্টিতে সমান এবং আইনের সমান আশ্রয় লাভের অধিকারী।' (৪১তম বিসিএস)",
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
                'study_guide_id' => $guideConst?->id,
                'question' => "বাংলাদেশের সংবিধান গণপরিষদে কত তারিখে গৃহীত হয়?",
                'answer' => "৪ নভেম্বর ১৯৭২",
                'explanation' => "বাংলাদেশের সংবিধান ১৯৭২ সালের ৪ নভেম্বর গণপরিষদে গৃহীত হয় এবং ওই দিনটিকে 'সংবিধান দিবস' হিসেবে পালন করা হয়। কার্যকর হয় ১৯৭২ সালের ১৬ ডিসেম্বর।",
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
            [
                'subject_id' => $gk->id,
                'topic_id' => $war?->id,
                'study_guide_id' => $guideWar?->id,
                'question' => "১৯৭১ সালের ১৭ এপ্রিল মুজিবনগর সরকারের শপথ গ্রহণ অনুষ্ঠান কোথায় অনুষ্ঠিত হয়েছিল?",
                'answer' => "মেহেরপুরের ভবেরপাড়া (বৈদ্যনাথতলা)",
                'explanation' => "কুষ্টিয়া জেলার (বর্তমান মেহেরপুর জেলা) মেহেরপুর মহকুমার ভবেরপাড়া গ্রামের বৈদ্যনাথতলায় (আম্রকানন) মুজিবনগর সরকারের ঐতিহাসিক শপথ গ্রহণ অনুষ্ঠিত হয়। (৪২তম বিশেষ বিসিএস)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'চুয়াডাঙ্গার দর্শনা',
                    'B' => 'মেহেরপুরের ভবেরপাড়া (বৈদ্যনাথতলা)',
                    'C' => 'কুষ্টিয়ার শিলাইদহ',
                    'D' => 'কলকাতার থিয়েটার রোড',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::EASY,
                'status' => ContentStatus::PUBLISHED,
            ],

            // ========================================================
            // 5. ICT
            // ========================================================
            [
                'subject_id' => $ict->id,
                'topic_id' => $memTopic?->id,
                'study_guide_id' => $guideICT?->id,
                'question' => "কম্পিউটারের কোন মেমোরিটি সর্বাধিক দ্রুতগতিসম্পন্ন (Fastest)?",
                'answer' => "Register",
                'explanation' => "মেমোরি গতির ক্রম: Register > Cache Memory > RAM > SSD > HDD। রেজিস্টার সিপিইউ-এর অভ্যন্তরে সরাসরি যুক্ত থাকে।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'RAM',
                    'B' => 'ROM',
                    'C' => 'Register',
                    'D' => 'Cache Memory',
                ],
                'correct_option' => 'C',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $ict->id,
                'topic_id' => $networking?->id,
                'question' => "OSI রেফারেন্স মডেলের মোট লেয়ার (Layer) সংখ্যা কতটি?",
                'answer' => "৭টি",
                'explanation' => "OSI (Open Systems Interconnection) মডেলে মোট ৭টি লেয়ার রয়েছে (Physical, Data Link, Network, Transport, Session, Presentation, Application)।",
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
                'question' => "IPv4 অ্যাড্রেস কত বিটের হয়ে থাকে?",
                'answer' => "৩২ বিট",
                'explanation' => "IPv4 (Internet Protocol version 4) অ্যাড্রেস ৩২ বিটের (৪ বাইট) হয়ে থাকে, যা ডট-ডেসিমেল পদ্ধতিতে লেখা হয়। অপরপক্ষে IPv6 অ্যাড্রেস ১২৮ বিটের।",
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

            // ========================================================
            // 6. TECHNICAL SUBJECTS
            // ========================================================
            [
                'subject_id' => $tech->id,
                'topic_id' => $cse?->id,
                'study_guide_id' => $guideDBMS?->id,
                'question' => "ডাটাবেসে ACID প্রোপার্টিজের 'A' অক্ষরটি দিয়ে নিচের কোনটি বোঝানো হয়?",
                'answer' => "Atomicity",
                'explanation' => "ACID এর পূর্ণরূপ: Atomicity (অল অর নাথিং), Consistency (সঙ্গতি), Isolation (বিচ্ছিন্নতা), Durability (স্থায়িত্ব)। (বাংলাদেশ ব্যাংক আইটি অফিসার ২০২১)",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'Availability',
                    'B' => 'Atomicity',
                    'C' => 'Accuracy',
                    'D' => 'Authenticity',
                ],
                'correct_option' => 'B',
                'difficulty' => DifficultyLevel::MEDIUM,
                'status' => ContentStatus::PUBLISHED,
            ],
            [
                'subject_id' => $tech->id,
                'topic_id' => $eee?->id,
                'study_guide_id' => $guideLogic?->id,
                'question' => "নিচের কোন দুটি গেটকে 'সার্বজনীন গেট' (Universal Gates) বলা হয়?",
                'answer' => "NAND ও NOR",
                'explanation' => "NAND এবং NOR গেটকে সার্বজনীন গেট বলা হয় কারণ এই দুটি গেট ব্যবহার করে যেকোনো মৌলিক গেট (AND, OR, NOT) তৈরি করা সম্ভব।",
                'question_type' => QuestionType::MCQ,
                'options' => [
                    'A' => 'AND ও OR',
                    'B' => 'NAND ও NOR',
                    'C' => 'XOR ও XNOR',
                    'D' => 'NOT ও AND',
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
