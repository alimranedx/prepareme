<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\SectionType;
use App\Models\StudyGuide;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComprehensiveStudyGuidesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        $guidesData = [
            // --- 1. ধ্বনি, বর্ণ ও উচ্চারণ রীতি ---
            [
                'topic_slug' => 'bangla-phonetics-and-orthography',
                'slug' => 'bangla-phonetics-and-sound-system-guide',
                'title' => 'ধ্বনি, বর্ণ ও উচ্চারণ স্থান: বিসিএস প্রিলিমিনারি পূর্ণাঙ্গ নোট',
                'summary' => 'কণ্ঠ্য, তালব্য, মূর্ধন্য, দন্ত্য ও ওষ্ঠ্য ধ্বনি চেনার সহজ কৌশল এবং ণ-ত্ব ও ষ-ত্ব বিধানের নিয়মাবলী।',
                'content' => "বাংলা ব্যাকরণের অন্যতম মৌলিক ও গুরুত্বপূর্ণ অংশ হলো ধ্বনিতত্ত্ব। মানুষের বাক্প্রত্যঙ্গের সাহায্যে উচ্চারিত অর্থবোধক আওয়াজকে ধ্বনি বলে। আর ধ্বনির লিখিত রূপকে বলা হয় বর্ণ।\n\nবাংলা বর্ণমালায় মোট বর্ণ ৫০টি (স্বরবর্ণ ১১টি, ব্যঞ্জনবর্ণ ৩৯টি)। চাকরি পরীক্ষায় প্রতি বছর উচ্চারণ স্থান, মাত্রা সংখ্যা এবং ণ-ত্ব ও ষ-ত্ব বিধান থেকে নিশ্চিত প্রশ্ন এসে থাকে।",
                'sections' => [
                    [
                        'title' => '১. উচ্চারণ স্থান অনুযায়ী ব্যঞ্জনধ্বনির শ্রেণিবিভাগ',
                        'content' => "• কণ্ঠ্য ধ্বনি (ক-বর্গ): ক, খ, গ, ঘ, ঙ\n• তালব্য ধ্বনি (চ-বর্গ): চ, ছ, জ, ঝ, ঞ, শ, য\n• মূর্ধন্য ধ্বনি (ট-বর্গ): ট, ঠ, ড, ঢ, ণ, র, ড়, ঢ়, ষ\n• দন্ত্য ধ্বনি (ত-বর্গ): ত, থ, দ, ধ, ন, ল, স\n• ওষ্ঠ্য ধ্বনি (প-বর্গ): প, ফ, ব, ভ, ম",
                        'section_type' => SectionType::EXPLANATION,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. বর্ণমালার মাত্রা সংক্রান্ত গুরুত্বপূর্ণ তথ্য',
                        'content' => "• পূর্ণমাত্রার বর্ণ: ৩২টি (স্বরবর্ণ ৬টি, ব্যঞ্জনবর্ণ ২৬টি)\n• অর্ধমাত্রার বর্ণ: ৮টি (স্বরবর্ণ ১টি 'ঋ', ব্যঞ্জনবর্ণ ৭টি: খ, গ, ণ, থ, ধ, প, শ)\n• মাত্রাহীন বর্ণ: ১০টি (স্বরবর্ণ ৪টি: এ, ঐ, ও, ঔ; ব্যঞ্জনবর্ণ ৬টি: ঙ, ঞ, ৎ, ং, ঃ, ঁ)",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 2,
                    ],
                    [
                        'title' => '৩. ণ-ত্ব ও ষ-ত্ব বিধানের গোল্ডেন রুলস',
                        'content' => "• ঋ, র, ষ এর পর তৎসম শব্দে মূর্ধন্য-ণ হয়। যেমন: ঋণ, কারণ, বর্ণ, পাষাণ।\n• ট-বর্গীয় ধ্বনির আগে তৎসম শব্দে যুক্তবর্ণে সর্বদা মূর্ধন্য-ণ হয়। যেমন: ঘণ্টা, লুণ্ঠন, কাণ্ড।\n• খাঁটি বাংলা ও বিদেশি শব্দে কখনো মূর্ধন্য-ণ বা ষ হয় না (দন্ত্য-ন ও দন্ত্য-স হয়)। যেমন: কোরআন, কর্নার, স্টেশন, পোস্ট।",
                        'section_type' => SectionType::EXAMPLE,
                        'sort_order' => 3,
                    ],
                    [
                        'title' => '৪. বিগত বিসিএস পরীক্ষার প্রশ্নোত্তর',
                        'content' => "• বাংলা বর্ণমালায় মাত্রাহীন বর্ণ কয়টি? [উত্তর: ১০টি (৩৮তম ও ৪০তম বিসিএস)]\n• 'ঘণ্টা' শব্দে 'ণ' কোন নিয়মে ব্যবহৃত হয়েছে? [উত্তর: ট-বর্গীয় ধ্বনির সাথে যুক্ত হওয়ায়]\n• কোন বানানটি শুদ্ধ? [উত্তর: পিপীলিকা, মুহুর্মুহু]",
                        'section_type' => SectionType::PRACTICE,
                        'sort_order' => 4,
                    ],
                ],
            ],

            // --- 2. উপসর্গ, অনুসর্গ ও প্রত্যয় ---
            [
                'topic_slug' => 'prefixes-and-suffixes',
                'slug' => 'bangla-prefixes-suffixes-and-roots',
                'title' => 'উপসর্গ ও প্রত্যয়: ৫ সেকেন্ডে শব্দ গঠন সনাক্তকরণের ট্রিক',
                'summary' => 'খাঁটি বাংলা, সংস্কৃত ও বিদেশি উপসর্গ চেনার শর্টকাট এবং কৃৎ ও তদ্ধিত প্রত্যয়ের নিয়ম।',
                'content' => "উপসর্গের অর্থবাচকতা নেই, কিন্তু অর্থদ্যোতকতা আছে। অর্থাৎ উপসর্গের নিজস্ব কোনো পূর্ণাঙ্গ অর্থ নেই, তবে অন্য শব্দের পূর্বে বসে নতুন অর্থবোধক শব্দ তৈরি করার চমৎকার ক্ষমতা রয়েছে।",
                'sections' => [
                    [
                        'title' => '১. বাংলা ও সংস্কৃত উপসর্গের সংখ্যা ও কমন উপসর্গ',
                        'content' => "• খাঁটি বাংলা উপসর্গ: ২১টি (অ, অঘা, অজ, অনা, আ, আড়, আন, আব, ইতি, উন, কদ, কু, নি, পাতি, বি, ভর, রাম, স, সা, সু, হা)\n• সংস্কৃত (তৎসম) উপসর্গ: ২০টি (প্র, পরা, অপ, সম, নি, অনু, অব, নির্, দুর্, বি, অধি, সু, উৎ, পরি, প্রতি, অতি, অপি, অভি, উপ, আ)\n\nশর্টকাট ট্রিক: 'আ, সু, বি, নি' — এই ৪টি উপসর্গ বাংলা ও সংস্কৃত উভয় জায়গাতেই রয়েছে!",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. প্রত্যয় নির্ণয় (কৃৎ বনাম তদ্ধিত)',
                        'content' => "• ধাতুর (ক্রিয়াপদের মূল) সাথে যে প্রত্যয় যুক্ত হয় তাকে 'কৃৎ প্রত্যয়' বলে। সাধিত শব্দটিকে বলে 'কৃদন্ত পদ'। (যেমন: চল্ + অন্ত = চলন্ত)\n• নাম শব্দের সাথে যে প্রত্যয় যুক্ত হয় তাকে 'তদ্ধিত প্রত্যয়' বলে। সাধিত শব্দটিকে বলে 'তদ্ধিতান্ত পদ'। (যেমন: ঢাকা + আই = ঢাকাই)",
                        'section_type' => SectionType::EXPLANATION,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 3. সাহিত্যিকদের উপাধি ও ছদ্মনাম ---
            [
                'topic_slug' => 'literary-titles-and-pseudonyms',
                'slug' => 'famous-bengali-authors-pseudonyms-and-titles',
                'title' => 'বাংলা সাহিত্যিকদের উপাধি, ছদ্মনাম ও প্রথম গ্রন্থ (পূর্ণাঙ্গ তালিকা)',
                'summary' => 'বিসিএস ও সরকারি চাকরির প্রিলিমিনারিতে প্রতি বছর ১-২ নম্বর নিশ্চিত আসার মতো উপাধি ও ছদ্মনাম।',
                'content' => "বাংলা সাহিত্যের খ্যাতনামা কবি ও সাহিত্যিকদের উপাধি ও ছদ্মনাম চাকরি পরীক্ষার একটি অবধারিত প্রশ্ন। এই নোটে সকল গুরুত্বপূর্ণ সাহিত্যিকদের তথ্য একনজরে সন্নিবেশিত করা হলো।",
                'sections' => [
                    [
                        'title' => '১. সর্বাধিক আসা সাহিত্যিকদের উপাধি তালিকা',
                        'content' => "• ভারতচন্দ্র = রায়গুণাকর\n• ঈশ্বরচন্দ্র বিদ্যাসাগর = বিদ্যাসাগর, দয়ার সাগর\n• বঙ্কিমচন্দ্র চট্টোপাধ্যায় = সাহিত্য সম্রাট\n• বিহারীলাল চক্রবর্তী = ভোরের পাখি (রবীন্দ্রনাথ প্রদত্ত)\n• রবীন্দ্রনাথ ঠাকুর = বিশ্বকবি, কবিগুরু, নাইট\n• কাজী নজরুল ইসলাম = বিদ্রোহী কবি, জাতীয় কবি\n• জীবনানন্দ দাশ = রূপসী বাংলার কবি, তিমির হননের কবি, নির্জনতম কবি\n• জসীমউদ্দীন = পল্লীকবি\n• শামসুর রাহমান = নাগরিক কবি\n• ফররুখ আহমদ = মুসলিম রেনেসাঁর কবি\n• সুকান্ত ভট্টাচার্য = কিশোর কবি",
                        'section_type' => SectionType::SUMMARY,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. বিখ্যাত সাহিত্যিকদের ছদ্মনাম',
                        'content' => "• রবীন্দ্রনাথ ঠাকুর = ভানুসিংহ ঠাকুর\n• প্রমথ চৌধুরী = বীরবল\n• বলাইচাঁদ মুখোপাধ্যায় = বনফুল\n• প্যারিচাঁদ মিত্র = টেকচাঁদ ঠাকুর\n• বিনয়কৃষ্ণ মুখোপাধ্যায় = যাযাবর\n• মীর মশাররফ হোসেন = গাজী মিয়াঁ\n• সমরেশ বসু = কালকূট, ভ্রমর\n• সুনীল গঙ্গোপাধ্যায় = নীল লোহিত, সনাতন পাঠক",
                        'section_type' => SectionType::EXAMPLE,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 4. English: Parts of Speech ---
            [
                'topic_slug' => 'parts-of-speech-identification',
                'slug' => 'parts-of-speech-identification-tricks',
                'title' => 'Parts of Speech: How to Identify Underlined Words in 10 Seconds',
                'summary' => 'Suffix techniques and position-based clues to correctly identify Nouns, Adjectives, Adverbs, and Participles.',
                'content' => "Examiners in BCS and IBA Bank exams test your ability to determine the function of a word in a specific sentence rather than its generic dictionary definition. The golden rule: A word's part of speech is determined by what it DOES in the sentence.",
                'sections' => [
                    [
                        'title' => '1. Position-based Identification Blueprint',
                        'content' => "• Determiner + Noun (e.g. The 'mother' in her arose. -> 'mother' is an abstract noun here)\n• Determiner + Adjective + Noun (e.g. A 'fast' runner. -> 'fast' is an adjective)\n• Verb + Adverb (e.g. He runs 'fast'. -> 'fast' is an adverb)\n• Preposition + Noun / Pronoun / Gerund (e.g. Fond of 'reading' -> Gerund)",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '2. Gerund vs Present Participle Confusion Solved',
                        'content' => "Both take Verb + ing, but their grammatical roles are opposite:\n• Gerund = Verb + Noun (Answers 'What?'). It can be replaced by 'It' or 'Something'.\n  Example: 'Walking' is good for health. -> (Something is good for health = Gerund)\n• Participle = Verb + Adjective (Answers 'How?' or modifies a noun).\n  Example: A 'barking' dog seldom bites. -> (Barking modifies dog = Participle)",
                        'section_type' => SectionType::EXPLANATION,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 5. English: Synonyms & Antonyms ---
            [
                'topic_slug' => 'synonyms-and-antonyms',
                'slug' => 'high-frequency-gre-vocabulary-bank-bcs',
                'title' => 'Top 100 High-Yield Vocabulary (Synonyms & Antonyms) for Bank AD & BCS',
                'summary' => 'Root words, contextual meanings, and mnemonic associations for high-scoring vocabulary prep.',
                'content' => "Vocabulary sections make the decisive difference between selected candidates and the rest in Bangladesh Bank AD and Combined Bank Officer exams. Cramming random word lists fails; studying word roots and contextual antonyms succeeds.",
                'sections' => [
                    [
                        'title' => '1. Recurring Bank Exam Words with Synonyms & Antonyms',
                        'content' => "• EPHEMERAL (ক্ষণস্থায়ী)\n  Synonyms: Transient, Fleeting, Evaneascent\n  Antonyms: Eternal, Perpetual, Permanent\n\n• CANDID (অকপট / স্পষ্টভাষী)\n  Synonyms: Frank, Outspoken, Forthright\n  Antonyms: Deceitful, Evasive, Guileful\n\n• PRAGMATIC (বাস্তবধর্মী)\n  Synonyms: Practical, Realistic, Utilitarian\n  Antonyms: Idealistic, Quixotic, Impractical\n\n• ZEALOT (ধর্মান্ধ বা অতি-উৎসাহী ব্যক্তি)\n  Synonyms: Fanatic, Extremist, Militant\n  Antonyms: Moderate, Liberal",
                        'section_type' => SectionType::EXAMPLE,
                        'sort_order' => 1,
                    ],
                ],
            ],

            // --- 6. Math: Indices & Logarithms ---
            [
                'topic_slug' => 'indices-and-logarithms',
                'slug' => 'indices-and-logarithms-shortcuts',
                'title' => 'সূচক ও লগারিদম: বিসিএস ও ব্যাংকের ৩ নম্বর নিশ্চিত করার সূত্র ও শর্টকাট',
                'summary' => 'লগের ভিত্তি রূপান্তর, ঋণাত্মক সূচক এবং বিগত প্রিলিমিনারি পরীক্ষার নির্ভুল সমাধান।',
                'content' => "বিসিএস প্রিলিমিনারিতে বীজগণিত অংশের ১৫ নম্বরের মধ্যে সূচক ও লগারিদম থেকে প্রতি বছর ৩ নম্বর সরাসরি থাকে। মৌলিক সূত্রগুলো মুখস্থ থাকলে প্রতিটি প্রশ্ন ২০ সেকেন্ডের মধ্যে সমাধান করা সম্ভব।",
                'sections' => [
                    [
                        'title' => '১. সূচকের অপরিহার্য সূত্রাবলি',
                        'content' => "• aᵐ × aⁿ = aᵐ⁺ⁿ\n• aᵐ / aⁿ = aᵐ⁻ⁿ\n• (aᵐ)ⁿ = aᵐⁿ\n• a⁰ = 1 (যেখানে a ≠ 0)\n• a⁻ⁿ = 1 / aⁿ\n• n√a = a^(1/n)",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. লগারিদমের গোল্ডেন রুলস',
                        'content' => "• logₐ (MN) = logₐ M + logₐ N\n• logₐ (M/N) = logₐ M - logₐ N\n• logₐ (Mᵏ) = k × logₐ M\n• logₐ a = 1 এবং logₐ 1 = 0\n• ভিত্তি পরিবর্তন: logₐ b = (log_c b) / (log_c a)\n• রূপান্তর ট্রিক: logₐ x = y হলে, aʸ = x",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 7. Math: Series & Progression ---
            [
                'topic_slug' => 'arithmetic-and-geometric-series',
                'slug' => 'series-and-progression-shortcuts',
                'title' => 'সমান্তর ও গুণোত্তর ধারা: সমষ্টি ও n-তম পদ নির্ণয়ের অব্যর্থ কৌশল',
                'summary' => 'সমান্তর ধারার সমষ্টি সূত্র, অসীমতক সমষ্টি এবং চাকরি পরীক্ষায় আসা পুনরাবৃত্ত প্রশ্ন।',
                'content' => "ধারা সংক্রান্ত প্রশ্নে পরীক্ষার্থীরা প্রায়ই সাধারণ অন্তর এবং সাধারণ অনুপাতের মধ্যে বিভ্রান্ত হয়ে ভুল সূত্র প্রয়োগ করে থাকেন। এই গাইডে ধারার যাবতীয় প্যাটার্ন অত্যন্ত সহজভাবে উপস্থাপন করা হলো।",
                'sections' => [
                    [
                        'title' => '১. সমান্তর ধারা (Arithmetic Series)',
                        'content' => "সাধারণ অন্তর d = দ্বিতীয় পদ - প্রথম পদ\n• n-তম পদ = a + (n - 1)d\n• প্রথম n সংখ্যক পদের সমষ্টি Sₙ = (n/2) × [2a + (n - 1)d]\n\nপ্রথম n সংখ্যক স্বাভাবিক সংখ্যার সমষ্টি:\n• 1 + 2 + 3 + ... + n = [n(n + 1)] / 2\n• বিজোড় সংখ্যার সমষ্টি: 1 + 3 + 5 + ... (n পদ) = n²",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 1,
                    ],
                ],
            ],

            // --- 8. GK: Constitution of Bangladesh ---
            [
                'topic_slug' => 'constitution-of-bangladesh',
                'slug' => 'constitution-of-bangladesh-essential-articles',
                'title' => 'গণপ্রজাতন্ত্রী বাংলাদেশের সংবিধান: বিসিএস পরীক্ষার ৫ নম্বর নিশ্চিত করার হ্যান্ডনোট',
                'summary' => 'সংবিধানের ৪টি মূলনীতি, মৌলিক অধিকারের গুরুত্বপূর্ণ অনুচ্ছেদ এবং ১৭টি সংশোধনীর সংক্ষেপ।',
                'content' => "বিসিএস প্রিলিমিনারি পরীক্ষায় বাংলাদেশ বিষয়াবলী অংশের ৩০ নম্বরের মধ্যে সংবিধান থেকে অন্তত ৪ থেকে ৬ নম্বর নিশ্চিত আসে। সংবিধানের মোট ১৫৩টি অনুচ্ছেদ মুখস্থ করার প্রয়োজন নেই; নির্ধারিত ৩৫-৪০টি গুরুত্বপূর্ণ অনুচ্ছেদ জানলেই যথেষ্ট।",
                'sections' => [
                    [
                        'title' => '১. সংবিধানের ঐতিহাসিক পটভূমি ও কাঠামো',
                        'content' => "• খসড়া সংবিধান প্রণয়ন কমিটির সভাপতি: ড. কামাল হোসেন (সদস্য ৩৪ জন, একমাত্র নারী সদস্য বেগম রাজিয়া বানু)\n• সংবিধান গণপরিষদে গৃহীত হয়: ৪ নভেম্বর ১৯৭২ (সংবিধান দিবস)\n• সংবিধান কার্যকর হয়: ১৬ ডিসেম্বর ১৯৭২\n• মোট অনুচ্ছেদ: ১৫৩টি | মোট ভাগ: ১১টি | মোট তফসিল: ৭টি",
                        'section_type' => SectionType::EXPLANATION,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. চাকরি পরীক্ষায় বারবার আসা গুরুত্বপূর্ণ অনুচ্ছেদসমূহ',
                        'content' => "• অনুচ্ছেদ ৭: সংবিধানের প্রাধান্য (জনগণই সকল ক্ষমতার মালিক)\n• অনুচ্ছেদ ৭(ক): সংবিধান বাতিল বা স্থগিতকরণ ইত্যাদি রাষ্ট্রদ্রোহ অপরাধ\n• অনুচ্ছেদ ৯: জাতীয়তাবাদ\n• অনুচ্ছেদ ১৭: অবৈতনিক ও বাধ্যতামূলক প্রাথমিক শিক্ষা\n• অনুচ্ছেদ ১৮(ক): পরিবেশ ও জীববৈচিত্র্য সংরক্ষণ\n• অনুচ্ছেদ ২১: নাগরিক ও সরকারি কর্মচারীদের কর্তব্য\n• অনুচ্ছেদ ২৭: আইনের দৃষ্টিতে সমতা\n• অনুচ্ছেদ ২৮: ধর্ম, বর্ণ, লিঙ্গ ইত্যাদির বৈষম্য বিলোপ\n• অনুচ্ছেদ ২৯: সরকারি নিয়োগলাভে সুযোগের সমতা\n• অনুচ্ছেদ ৩৯: চিন্তা ও বিবেকের স্বাধীনতা এবং বাক্-স্বাধীনতা\n• অনুচ্ছেদ ৪৮: রাষ্ট্রপতি নির্বাচন\n• অনুচ্ছেদ ৭০: ফ্লোর ক্রসিং (দলের বিরুদ্ধে ভোটদানে পদ শূন্য)",
                        'section_type' => SectionType::SUMMARY,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 9. GK: Liberation War 1971 ---
            [
                'topic_slug' => 'liberation-war-1971',
                'slug' => 'liberation-war-1971-comprehensive-prep',
                'title' => '১৯৭১ সালের মুক্তিযুদ্ধ ও মুজিবনগর সরকার: প্রিলিমিনারি পূর্ণাঙ্গ তথ্যভাণ্ডার',
                'summary' => 'অপারেশন সার্চলাইট, ১১টি সেক্টর ও সেক্টর কমান্ডার, ৭ বীরশ্রেষ্ঠ এবং মিত্রবাহিনীর যৌথ অভিযান।',
                'content' => "বাংলাদেশের মহান মুক্তিযুদ্ধ যে কোনো সরকারি চাকরির প্রিলিমিনারি ও ভাইভা পরীক্ষার সবচেয়ে গুরুত্বপূর্ণ বিষয়। প্রতিটি সেক্টরের ভৌগোলিক অবস্থান ও কমান্ডারদের নাম মুখস্থ থাকা আবশ্যক।",
                'sections' => [
                    [
                        'title' => '১. মুজিবনগর সরকার সংক্রান্ত তথ্য',
                        'content' => "• গঠিত হয়: ১০ এপ্রিল ১৯৭১ (ঘোষণাপত্র পাঠ করেন ব্যারিস্টার এম. আমীর-উল ইসলাম)\n• শপথ গ্রহণ করে: ১৭ এপ্রিল ১৯৭১ (মেহেরপুরের বৈদ্যনাথতলার ভবেরপাড়া গ্রামে, বর্তমান মুজিবনগর)\n• রাষ্ট্রপতি: বঙ্গবন্ধু শেখ মুজিবুর রহমান (অনুপস্থিতিতে উপ-রাষ্ট্রপতি ও অস্থায়ী রাষ্ট্রপতি সৈয়দ নজরুল ইসলাম)\n• প্রধানমন্ত্রী: তাজউদ্দীন আহমদ\n• অর্থমন্ত্রী: এম মনসুর আলী\n• স্বরাষ্ট্র ও ত্রাণমন্ত্রী: এ. এইচ. এম. কামারুজ্জামান\n• প্রধান সেনাপতি: এম. এ. জি. ওসমানী",
                        'section_type' => SectionType::EXPLANATION,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. ১১টি সেক্টর ও প্রধান সেক্টর কমান্ডারগণ',
                        'content' => "• সেক্টর ১: চট্টগ্রাম, পার্বত্য চট্টগ্রাম ও নোয়াখালীর অংশ (মেজর জিয়াউর রহমান, পরবর্তীতে রফিক)\n• সেক্টর ২: ঢাকা, কুমিল্লা, ফরিদপুর (মেজর খালেদ মোশাররফ, এটিএম হায়দার)\n• সেক্টর ১০: কোনো স্থায়ী কমান্ডার ছিল না (নৌ-কমান্ডোদের অধীনে সারা দেশের নৌপথ)",
                        'section_type' => SectionType::SUMMARY,
                        'sort_order' => 2,
                    ],
                ],
            ],

            // --- 10. ICT: Computer Hardware & Networking ---
            [
                'topic_slug' => 'computer-hardware-and-memory',
                'slug' => 'computer-hardware-memory-hierarchy-guide',
                'title' => 'কম্পিউটার হার্ডওয়্যার, বাস ও মেমোরি হায়ারার্কি: আইসিটি অংশের হ্যান্ডবুক',
                'summary' => 'CPU আর্কিটেকচার, ক্যাশ মেমোরি, RAM vs ROM এবং মেমোরির দ্রুততার অনুক্রম।',
                'content' => "বিসিএস প্রিলিমিনারি পরীক্ষায় তথ্য ও যোগাযোগ প্রযুক্তি (ICT) অংশের ১৫ নম্বরের মধ্যে ৫-৬ নম্বর কম্পিউটার হার্ডওয়্যার ও মেমোরি সংক্রান্ত প্রশ্ন থেকে আসে।",
                'sections' => [
                    [
                        'title' => '১. মেমোরি স্পিড হায়ারার্কি (দ্রুততম থেকে ধীরতম)',
                        'content' => "রেজিস্টার (Register) > ক্যাশ মেমোরি (Cache Memory: L1, L2, L3) > প্রাইমারি মেমোরি (RAM) > সেকেন্ডারি মেমোরি (SSD / Hard Disk) > অপটিক্যাল ড্রাইভ।\n\n• সবচেয়ে দ্রুত মেমোরি: প্রসেসরের অভ্যন্তরে থাকা রেজিস্টার।\n• ক্যাশ মেমোরি অবস্থান করে: CPU এবং RAM এর মধ্যবর্তী স্থানে বা প্রসেসরে।",
                        'section_type' => SectionType::FORMULA,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => '২. স্টোরেজ ইউনিটের হিসাব',
                        'content' => "• ১ বাইট (Byte) = ৮ বিট (Bit)\n• ১ নিবল (Nibble) = ৪ বিট\n• ১ কিলোবাইট (KB) = ১০২৪ বাইট (২¹⁰ বাইট)\n• ১ মেগাবাইট (MB) = ১০২৪ কিলোবাইট\n• ১ গিগাবাইট (GB) = ১০২৪ মেগাবাইট\n• ১ টেরাবাইট (TB) = ১০২৪ গিগাবাইট",
                        'section_type' => SectionType::EXAMPLE,
                        'sort_order' => 2,
                    ],
                ],
            ],
        ];

        foreach ($guidesData as $data) {
            $topic = Topic::where('slug', $data['topic_slug'])->first();
            if (!$topic) continue;

            $guide = StudyGuide::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'topic_id' => $topic->id,
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'content' => $data['content'],
                    'status' => ContentStatus::PUBLISHED,
                    'published_at' => now(),
                    'created_by' => $adminId,
                ]
            );

            $guide->sections()->delete();
            $guide->sections()->createMany($data['sections']);
        }

        // For any remaining topics without study guides, automatically create a structured study note
        // so NO topic card on the site is ever blank!
        $allTopics = Topic::doesntHave('studyGuides')->whereNull('parent_id')->get();
        foreach ($allTopics as $top) {
            $guide = StudyGuide::updateOrCreate(
                ['slug' => 'essential-study-notes-' . $top->slug],
                [
                    'topic_id' => $top->id,
                    'title' => "{$top->name}: প্রিলিমিনারি মূল বিষয়বস্তু ও প্রস্তুতি কৌশল",
                    'summary' => "বিসিএস ও ব্যাংক নিয়োগ পরীক্ষার জন্য {$top->name} অধ্যায়ের মৌলিক তথ্য, মূল নিয়ম ও সাজেশন।",
                    'content' => "এই অধ্যায়টি সরকারি ও ব্যাংক চাকরি পরীক্ষায় অত্যন্ত গুরুত্বপূর্ণ। প্রশ্নপত্রে এই টপিক থেকে নিয়মিত এমসিকিউ এসে থাকে। নিচে প্রদত্ত মূল নিয়মাবলি ও বিগত পরীক্ষার প্রশ্নসমূহ মনোযোগ সহকারে পড়ুন।",
                    'status' => ContentStatus::PUBLISHED,
                    'published_at' => now(),
                    'created_by' => $adminId,
                ]
            );

            $guide->sections()->delete();
            $guide->sections()->createMany([
                [
                    'title' => '১. মৌলিক ধারণা ও পাঠ্যক্রম',
                    'content' => "এই অধ্যায়ের মূল আলোচ্য বিষয়সমূহ:\n• {$top->description}\n• পরীক্ষার জন্য সবচেয়ে গুরুত্বপূর্ণ অংশগুলো নিয়মিত রিভিশন দিন এবং বিগত সালের প্রশ্নব্যাংক অনুশীলন করুন।",
                    'section_type' => SectionType::EXPLANATION,
                    'sort_order' => 1,
                ],
                [
                    'title' => '২. দ্রুত রিভিশন টিপস ও সতর্কতা',
                    'content' => "• চাকরি পরীক্ষায় নেগেটিভ মার্কিং এড়াতে নিশ্চিত না হয়ে কোনো উত্তরে দাগানো থেকে বিরত থাকুন।\n• এই অধ্যায়ের বিগত ৫ বছরের প্রশ্নাবলী প্রশ্নব্যাংক ট্যাব থেকে একনজরে দেখে নিন।",
                    'section_type' => SectionType::FORMULA,
                    'sort_order' => 2,
                ],
            ]);
        }
    }
}
