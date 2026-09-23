<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Enums\SectionType;
use App\Models\Chapter;
use App\Models\Exam;
use App\Models\PublicQuestion;
use App\Models\QuestionSource;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnglishGrammarSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        $english = Subject::where('slug', 'english')->firstOrFail();

        // 1. Chapter: English Language & Grammar
        $chapter = Chapter::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-grammar-and-usage'],
            [
                'name' => 'English Language & Grammar',
                'description' => 'Parts of Speech, Nouns, Pronouns, Verbs, Tenses, Right Form of Verbs, Prepositions, Voice, Narration, Clauses, Vocabulary & Idioms.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 1,
                'created_by' => $adminId,
            ]
        );

        // Sources & Exams
        $bcs = Exam::where('slug', 'bcs-preliminary')->first();
        $bank = Exam::where('slug', 'bank-job-recruitment')->first();
        $primary = Exam::where('slug', 'primary-teacher-recruitment')->first();
        $ntrca = Exam::where('slug', 'ntrca-teacher-registration')->first();

        $src46 = QuestionSource::firstOrCreate(
            ['slug' => '46th-bcs-preliminary'],
            ['name' => '৪৬তম বিসিএস প্রিলিমিনারি পরীক্ষা (2024)', 'exam_id' => $bcs?->id, 'year' => 2024, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src45 = QuestionSource::firstOrCreate(
            ['slug' => '45th-bcs-preliminary'],
            ['name' => '৪৫তম বিসিএস প্রিলিমিনারি পরীক্ষা (2023)', 'exam_id' => $bcs?->id, 'year' => 2023, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src44 = QuestionSource::firstOrCreate(
            ['slug' => '44th-bcs-preliminary'],
            ['name' => '৪৪তম বিসিএস প্রিলিমিনারি পরীক্ষা (2022)', 'exam_id' => $bcs?->id, 'year' => 2022, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src43 = QuestionSource::firstOrCreate(
            ['slug' => '43rd-bcs-preliminary'],
            ['name' => '৪৩তম বিসিএস প্রিলিমিনারি পরীক্ষা (2021)', 'exam_id' => $bcs?->id, 'year' => 2021, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src40 = QuestionSource::firstOrCreate(
            ['slug' => '40th-bcs-preliminary'],
            ['name' => '৪০তম বিসিএস প্রিলিমিনারি পরীক্ষা (2019)', 'exam_id' => $bcs?->id, 'year' => 2019, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $srcBank = QuestionSource::firstOrCreate(
            ['slug' => 'bangladesh-bank-ad-2023'],
            ['name' => 'বাংলাদেশ ব্যাংক সহকারী পরিচালক (AD) ২০২৩', 'exam_id' => $bank?->id, 'year' => 2023, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $srcPrimary = QuestionSource::firstOrCreate(
            ['slug' => 'primary-teacher-2023'],
            ['name' => 'প্রাথমিক সহকারী শিক্ষক নিয়োগ ২০২৩', 'exam_id' => $primary?->id, 'year' => 2023, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );

        $topicsData = [
            // 1. Parts of Speech Overview & Identification
            [
                'name' => 'Parts of Speech: Identification & Conversion',
                'slug' => 'parts-of-speech-overview-and-identification',
                'description' => 'Sentence-level identification of parts of speech, word position analysis, suffix cues, and conversion from one part to another.',
                'is_high_yield' => true,
                'sort_order' => 1,
                'subtopics' => [
                    'Definition & 8 Parts of Speech Overview',
                    'Identifying Parts of Speech by Sentence Position',
                    'Suffix Clues for Noun, Verb, Adjective & Adverb',
                    'Same Word Used as Different Parts of Speech (e.g. Water, Round, Fast, Better)',
                    'Word Formation & Inter-conversion'
                ],
                'guide' => [
                    'title' => 'Parts of Speech: Identification Master Guide & Shortcut Rules',
                    'summary' => 'Sentence positioning, suffix recognition, and solving common BCS traps where words change grammatical class based on context.',
                    'sections' => [
                        [
                            'title' => 'Fundamental Concept & 8-Part Architecture',
                            'type' => SectionType::EXPLANATION,
                            'content' => "In English, words are categorized into eight parts of speech according to the work they do in a sentence: Noun, Pronoun, Adjective, Verb, Adverb, Preposition, Conjunction, and Interjection.\n\nA single word can function as multiple parts of speech depending strictly on its syntactic position and function:\n• 'Water the plants' (Verb)\n• 'Drink clean water' (Noun)\n• 'Water pipe' (Adjective / Noun Adjunct)\n• 'Look above' (Adverb) vs 'The heavens above' (Preposition / Adjective).",
                        ],
                        [
                            'title' => 'Identification Formula & Suffix Markers',
                            'type' => SectionType::FORMULA,
                            'content' => "| Part of Speech | Common Suffixes | Sentence Position Rule |\n|---|---|---|\n| Noun | -tion, -sion, -ment, -ness, -ity, -hood, -ship, -dom | Subject or Object position (Article + ___ + Preposition/Verb) |\n| Adjective | -ful, -less, -ous, -able, -ible, -ive, -al, -ic | Directly before Noun (Article + ___ + Noun) or after Linking Verb |\n| Adverb | -ly, -ward, -wise (Exceptions: friendly, lovely = Adj) | Modifying Verb, Adjective, or another Adverb |\n| Verb | -en, -ify, -ize, -ate (shorten, simplify, realize) | Action or state following the Subject |",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'What is the noun form of \"brief\"?' — Answer: 'Brevity'. [38th BCS]\n2. 'The warning of the authority falls on deaf ears.' Here 'warning' is a: — Answer: Noun (preceded by 'the' and followed by 'of'). [37th BCS]\n3. 'He kept the fast for a week.' Here 'fast' is a: — Answer: Noun (preceded by article 'the'). [28th BCS]\n4. 'Which word is an adjective?' (a) contempt (b) contemptuous (c) contemptibly — Answer: (b) contemptuous.",
                        ],
                        [
                            'title' => 'Competitive Exam Traps & Tips',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ The Det + Adj + Noun Formula: If a single word follows an article (A/An/The) and precedes a preposition or verb, it MUST be a Noun.\n★ Words ending in -ly that are ADJECTIVES: cowardly, friendly, lovely, brotherly, lonely, motherly, heavenly, costly.\n★ 'Hard' and 'Fast' are both Adjectives and Adverbs (There is NO word called 'fastly').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "In the sentence 'The warning of the authority falls on deaf ears', what part of speech is 'warning'?",
                        'options' => ['Adjective', 'Noun', 'Verb', 'Adverb'],
                        'correct_option' => 'খ',
                        'explanation' => "Determiner (The) + Word + Preposition (of) কাঠামোতে মাঝের শব্দটি সবসময় Noun হিসেবে কাজ করে। তাই এখানে 'warning' একটি Verbal Noun বা Noun।",
                        'source' => $src45,
                    ],
                    [
                        'question' => "What is the noun of the word 'brief'?",
                        'options' => ['Briefing', 'Brevity', 'Briefly', 'Briefness'],
                        'correct_option' => 'খ',
                        'explanation' => "'Brief' (সংক্ষিপ্ত) শব্দটির সঠিক Noun রূপ হচ্ছে 'Brevity' (সংক্ষিপ্ততা)। এটি ৩৮তম বিসিএসে আসা অত্যন্ত পরিচিত প্রশ্ন।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 2. Noun & Classifications
            [
                'name' => 'Noun: Types, Countability, Gender & Number',
                'slug' => 'noun-and-classifications',
                'description' => 'Proper, Common, Collective, Abstract, Material nouns, Countable vs Uncountable rules, and irregular plurals.',
                'is_high_yield' => true,
                'sort_order' => 2,
                'subtopics' => [
                    'Proper, Common, Collective & Material Nouns',
                    'Abstract Noun Formation & Recognition',
                    'Countable vs Uncountable Nouns (Furniture, Information, Advice, Scenery)',
                    'Noun Number: Irregular Plurals (Datum/Data, Criterion/Criteria, Oasis/Oases)',
                    'Noun Gender & Possessive Case rules'
                ],
                'guide' => [
                    'title' => 'Noun Masterclass: Classification, Uncountable Nouns & Irregular Plurals',
                    'summary' => 'Comprehensive rules for classifying nouns, handling tricky non-count nouns, and mastering irregular foreign plurals for BCS and Banks.',
                    'sections' => [
                        [
                            'title' => 'Core Classifications & Structural Logic',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Nouns are broadly classified into Concrete (Proper, Common, Collective, Material) and Abstract:\n• Collective Nouns: Jury, Committee, Fleet, Herd, Crowd, Family, Class.\n• Abstract Nouns: Honesty, Childhood, Freedom, Wisdom, Bravery, Courage.\n• High-Yield Uncountable Nouns (Cannot take a/an, cannot be made plural with -s): Furniture, Information, Advice, Scenery, Baggage, Luggage, Equipment, Machinery, News, Bread, Soap, Poetry.",
                        ],
                        [
                            'title' => 'Foreign & Irregular Plurals Chart',
                            'type' => SectionType::FORMULA,
                            'content' => "| Singular | Plural | Note / Origin |\n|---|---|---|\n| Datum | Data | Latin (-um to -a) |\n| Medium | Media | Latin (-um to -a) |\n| Phenomenon | Phenomena | Greek (-on to -a) |\n| Criterion | Criteria | Greek (-on to -a) |\n| Oasis | Oases | Greek (-is to -es) |\n| Crisis | Crises | Greek (-is to -es) |\n| Hypothesis | Hypotheses | Greek (-is to -es) |\n| Radius | Radii | Latin (-us to -i) |\n| Focus | Foci / Focuses | Latin (-us to -i) |",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'What kind of noun is \"Cattle\"?' — Answer: Collective Noun. [40th BCS]\n2. 'The sceneries of Cox\'s Bazar are charming.' (Identify error) — Answer: 'Scenery' is uncountable; plural 'sceneries' is incorrect. It must be 'The scenery of Cox\'s Bazar is charming'.\n3. 'What is the plural of \"Criterion\"?' — Answer: 'Criteria'. [Bank AD]\n4. 'Which of the following is an Abstract Noun?' (a) Boy (b) Childhood (c) Water (d) Class — Answer: (b) Childhood.",
                        ],
                        [
                            'title' => 'Exam Traps & Shortcut Tips',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ To count uncountable nouns, use counting phrases: 'a piece of furniture', 'two pieces of information', 'a loaf of bread', 'a bar of soap'.\n★ Collective nouns acting as a single unified body take a singular verb ('The committee has reached its decision'). When members act individually/divided, it takes a plural verb ('The committee are divided in their opinions').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "What kind of noun is 'Cattle'?",
                        'options' => ['Proper noun', 'Common noun', 'Collective noun', 'Material noun'],
                        'correct_option' => 'গ',
                        'explanation' => "'Cattle' (গবাদিপশু) একটি Collective Noun। মনে রাখবেন, এটি দেখতে Singular মনে হলেও সবসময় Plural অর্থ প্রকাশ করে এবং এর পরে Plural Verb বসে।",
                        'source' => $src40,
                    ],
                    [
                        'question' => "Which of the following nouns is always uncountable?",
                        'options' => ['Book', 'Scenery', 'Poem', 'Problem'],
                        'correct_option' => 'খ',
                        'explanation' => "'Scenery' একটি Uncountable Noun। এর কোনো বহুবচন (sceneries) হয় না এবং এর পূর্বে সরাসরি a/an বসে না।",
                        'source' => $srcBank,
                    ],
                ],
            ],

            // 3. Pronoun & Antecedent Agreement
            [
                'name' => 'Pronoun: Types & Antecedent Agreement',
                'slug' => 'pronoun-and-antecedent-agreement',
                'description' => 'Relative, Demonstrative, Indefinite, Reflexive pronouns, Order of personal pronouns (231 vs 123), and antecedent agreement.',
                'is_high_yield' => true,
                'sort_order' => 3,
                'subtopics' => [
                    'Personal Pronouns & Order: 231 (Good) vs 123 (Fault/Confession)',
                    'Relative Pronouns: Who vs Whom, Which vs That, Whose',
                    'Indefinite Pronouns: Each, Either, Neither, Everyone, Anybody',
                    'Reflexive & Emphatic Pronouns (Myself, Himself, Themselves)',
                    'Reciprocal Pronouns: Each other (2 persons) vs One another (3+ persons)',
                    'Pronoun-Antecedent Agreement with Compound Subjects'
                ],
                'guide' => [
                    'title' => 'Pronoun Rules & Antecedent Agreement Guide',
                    'summary' => 'Master the order of personal pronouns, Who vs Whom traps, and subject-antecedent consistency in competitive exams.',
                    'sections' => [
                        [
                            'title' => 'Core Rules & Pronoun Order',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Pronouns replace nouns to avoid awkward repetition.\n\n1. Normal sentence order of personal pronouns is Second Person + Third Person + First Person (2-3-1):\n• 'You, he and I are present.'\n2. When confessing guilt, fault, or in plural form, the order is 1-2-3:\n• 'I, you and he are guilty.'\n• 'We, you and they must work together.'",
                        ],
                        [
                            'title' => 'Who vs Whom Quick Deciding Formula',
                            'type' => SectionType::FORMULA,
                            'content' => "Formula to test Who vs Whom:\n• Replace with 'He/She/They' ➔ Use WHO (Subjective)\n• Replace with 'Him/Her/Them' ➔ Use WHOM (Objective)\n• Following a Preposition ➔ Always use WHOM ('To whom it may concern', 'The man with whom I spoke').",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who did you talk to?' in formal grammar: 'To whom did you talk?'. [35th BCS]\n2. 'Let you and ___ be friends.' (a) I (b) me (c) my (d) mine — Answer: (b) me (Objective pronoun after 'Let'). [36th BCS]\n3. 'Between you and ___ there is no dispute.' (a) I (b) me — Answer: (b) me (Preposition 'between' takes objective case).",
                        ],
                        [
                            'title' => 'Common Traps & Rules',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Reflexive pronouns (myself, himself) CANNOT be the subject of a sentence ('John and myself went there' is INCORRECT; say 'John and I went there').\n★ 'Each other' is used for exactly two entities; 'One another' is used for more than two entities.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Choose the correct pronoun: 'Let you and ___ settle the matter.'",
                        'options' => ['I', 'me', 'myself', 'mine'],
                        'correct_option' => 'খ',
                        'explanation' => "'Let' এর পরে সবসময় Objective pronoun (me, him, her, them, us) বসে। সুতরাং সঠিক বাক্য: 'Let you and me settle the matter.'",
                        'source' => $src46,
                    ],
                ],
            ],

            // 4. Adjective & Degrees of Comparison
            [
                'name' => 'Adjective: Order, Participles & Comparison',
                'slug' => 'adjective-and-degrees-of-comparison',
                'description' => 'Classification of adjectives, OSASCOMP multiple adjective order, participle adjectives, comparative and superlative rules.',
                'is_high_yield' => true,
                'sort_order' => 4,
                'subtopics' => [
                    'Types: Descriptive, Quantitative, Numeral, Demonstrative',
                    'Order of Multiple Adjectives: OSASCOMP Rule',
                    'Participle Adjectives: -ing (causer) vs -ed (receiver/feeling)',
                    'Degrees of Comparison: Regular & Irregular (Good/Better/Best, Far/Further/Furthest)',
                    'Latin Comparatives followed by \'To\': Senior, Junior, Prior, Superior, Inferior',
                    'Double Comparatives: The more... the more...'
                ],
                'guide' => [
                    'title' => 'Adjectives, OSASCOMP Order & Comparative Structures',
                    'summary' => 'Systematic guide to ordering multiple adjectives, distinguishing participle adjectives, and mastering Latin comparatives.',
                    'sections' => [
                        [
                            'title' => 'OSASCOMP Multiple Adjective Sequence',
                            'type' => SectionType::FORMULA,
                            'content' => "When using multiple adjectives before a noun, follow the OSASCOMP order:\n1. Opinion (beautiful, lovely, smart)\n2. Size (large, tiny, tall)\n3. Age (ancient, young, new)\n4. Shape (round, square, oval)\n5. Color (black, white, red)\n6. Origin (Bangladeshi, French, Chinese)\n7. Material (wooden, silk, leather)\n8. Purpose (running shoes, sleeping bag)",
                        ],
                        [
                            'title' => 'Latin Comparatives & Exceptions',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Adjectives ending in '-ior' (Latin comparatives) NEVER take 'than'; they always take 'to':\n• Senior to, Junior to, Superior to, Inferior to, Prior to, Posterior to.\n• Also: 'Preferable to' ('Death is preferable to dishonour').",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'He is senior ___ me by two years.' — Answer: 'to'. [38th BCS]\n2. 'The rolling stone gathers no moss.' Here 'rolling' is a: — Answer: Participle (functioning as Adjective). [36th BCS]\n3. 'The higher you climb, ___ you feel.' — Answer: 'the colder'. (Double comparative: The + comparative..., The + comparative...).",
                        ],
                        [
                            'title' => 'Exam Shortcut Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Participle Adjectives: Use -ing for the thing creating the feeling ('The book is interesting'), and -ed for the person experiencing it ('I am interested in the book').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Complete the sentence: 'He is senior ___ me in service.'",
                        'options' => ['than', 'from', 'to', 'with'],
                        'correct_option' => 'গ',
                        'explanation' => "Latin Comparative (Senior, Junior, Superior, Inferior, Prior) এর পরে তুলনামূলকভাবে 'than' না বসে 'to' বসে।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 5. Verb & Classifications
            [
                'name' => 'Verb: Finite, Non-Finite, Transitive, Linking & Causative',
                'slug' => 'verb-and-classifications',
                'description' => 'Finite vs Non-Finite, Transitive vs Intransitive, Linking verbs, Modal auxiliaries, and Causative structures (make, have, let, help, get).',
                'is_high_yield' => true,
                'sort_order' => 5,
                'subtopics' => [
                    'Finite Verbs vs Non-Finite Verbs',
                    'Transitive (needs object) vs Intransitive (no object)',
                    'Linking Verbs (be, seem, appear, taste, smell, look, feel)',
                    'Modal Auxiliaries & Semi-Modals (dare, need, used to)',
                    'Causative Verbs: Make, Have, Let, Help, Get rules',
                    'Cognate Object & Factitive Verbs'
                ],
                'guide' => [
                    'title' => 'Verb Masterclass: Transitive, Linking & Causative Rules',
                    'summary' => 'Comprehensive rules governing English verbs, causative verb structures, and solving linking verb vs adverb dilemmas.',
                    'sections' => [
                        [
                            'title' => 'Causative Verbs Formulation',
                            'type' => SectionType::FORMULA,
                            'content' => "Causative verbs indicate that someone causes someone else to do something:\n1. MAKE / LET / HAVE (Active) + Person + Base Form (V1):\n   • 'I made him clean the room.'\n   • 'The teacher let them leave early.'\n2. GET (Active) + Person + To-Infinitive (To + V1):\n   • 'I got him to clean the room.'\n3. HAVE / GET (Passive) + Thing + Past Participle (V3):\n   • 'I had my car repaired.'\n   • 'She got her tooth extracted.'",
                        ],
                        [
                            'title' => 'Linking Verbs & Adjective Complements',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Linking verbs connect the subject to an adjective complement (NOT an adverb):\n• 'The food tastes delicious' (NOT deliciously).\n• 'She feels bad' (NOT badly).\n• 'The flower smells sweet' (NOT sweetly).\nCommon Linking Verbs: be, become, seem, appear, look, taste, smell, sound, feel, remain, stay.",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'I had my haircut yesterday.' Structure: Causative Have + Object + V3 (cut). [38th BCS]\n2. 'Honey tastes ___.' (a) sweet (b) sweetly — Answer: (a) sweet (Linking verb takes adjective).\n3. 'A rolling stone gathers no moss.' Here 'gathers' is a: — Answer: Transitive verb (Object = moss).",
                        ],
                        [
                            'title' => 'High-Yield Trap',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'HELP' can take either a bare infinitive or to-infinitive: 'She helped me solve the puzzle' OR 'She helped me to solve the puzzle'. Both are correct.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Choose the correct causative structure: 'I got the mechanic ___ my motorcycle.'",
                        'options' => ['repair', 'to repair', 'repaired', 'repairing'],
                        'correct_option' => 'খ',
                        'explanation' => "Causative 'GET' এর পরে যদি ব্যক্তিবাচক অবজেক্ট থাকে (active sense), তাহলে 'To + V1' বসে। তাই 'to repair' সঠিক।",
                        'source' => $srcBank,
                    ],
                ],
            ],

            // 6. Adverb & Position of Adverbs
            [
                'name' => 'Adverb: Types, Formation, Inversion & Order (MPT)',
                'slug' => 'adverb-and-positions',
                'description' => 'Adverbs of manner, place, time, frequency, degree, the MPT sequence, negative adverb inversion, and split infinitives.',
                'is_high_yield' => false,
                'sort_order' => 6,
                'subtopics' => [
                    'Classification: Manner, Place, Time, Frequency, Degree',
                    'The MPT Rule (Manner + Place + Time)',
                    'Negative Adverb Inversion: Hardly, Scarcely, Seldom, Never',
                    'Adverbs of Frequency Placement (before main verb, after be-verb)',
                    'Enough Placement: Adjective + Enough vs Enough + Noun'
                ],
                'guide' => [
                    'title' => 'Adverb Rules, Sequence & Negative Inversion',
                    'summary' => 'Master the MPT sequence, negative inversion structures (Hardly/Scarcely), and correct placement of Enough.',
                    'sections' => [
                        [
                            'title' => 'The MPT Sequence & Negative Inversion',
                            'type' => SectionType::FORMULA,
                            'content' => "1. Order of Adverbs at sentence end: Manner ➔ Place ➔ Time (MPT)\n   • 'She sang beautifully (Manner) at the party (Place) last night (Time).'\n2. Negative Inversion: When a sentence begins with a negative or restrictive adverb, inversion (Auxiliary + Subject) is required:\n   • 'Seldom have I seen such bravery.'\n   • 'Hardly had he arrived when the train left.'\n   • 'Never before has she been so proud.'",
                        ],
                        [
                            'title' => 'The \'Enough\' Rule',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Adjective/Adverb + ENOUGH: 'He is strong enough to lift it.' (Post-positive)\n• ENOUGH + Noun: 'We have enough money for the trip.' (Pre-positive)",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Hardly had I reached the station ___ the train left.' — Answer: 'when'. [37th BCS]\n2. 'Seldom ___ such a strange creature.' (a) I saw (b) did I see — Answer: (b) did I see (Inversion rule).",
                        ],
                        [
                            'title' => 'Shortcut Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: No sooner takes 'than'; Hardly and Scarcely take 'when'. In all three cases, the first clause is in Past Perfect with subject-auxiliary inversion ('No sooner had he seen...').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Complete the sentence: 'Hardly had the teacher entered the classroom ___ the students stood up.'",
                        'options' => ['than', 'when', 'then', 'before'],
                        'correct_option' => 'খ',
                        'explanation' => "'Hardly had... when' এবং 'Scarcely had... when' জোড়ায় ব্যবহৃত হয়। অন্যদিকে 'No sooner had...' এর পরে 'than' বসে।",
                        'source' => $src43,
                    ],
                ],
            ],

            // 7. Preposition & Appropriate Prepositions
            [
                'name' => 'Preposition: Rules & BCS/Bank Appropriate Prepositions',
                'slug' => 'preposition-and-appropriate-usage',
                'description' => 'Prepositions of time, place, movement, compound prepositions, and master list of 100+ high-frequency appropriate prepositions.',
                'is_high_yield' => true,
                'sort_order' => 7,
                'subtopics' => [
                    'Time: In, On, At, Since, For, By, During',
                    'Place & Movement: In, At, To, Into, Onto, Through, Across',
                    'Die of (disease), Die from (cause/overeating), Die for (noble cause), Die by (violence/suicide)',
                    'Differ with (person), Differ from (thing)',
                    'Master Appropriate Prepositions: Abide by, Accused of, Absorbed in, Congratulate on, Insist on, Prefer to'
                ],
                'guide' => [
                    'title' => 'Prepositions & Appropriate Prepositions Master Digest',
                    'summary' => 'Comprehensive preposition formulas, time/place spatial distinctions, and authentic past BCS and Bank appropriate preposition index.',
                    'sections' => [
                        [
                            'title' => 'Critical Appropriate Prepositions Index',
                            'type' => SectionType::FORMULA,
                            'content' => "| Word + Preposition | Meaning | Exam Example |\n|---|---|---|\n| Abide by | মেনে চলা | We must abide by the rules. |\n| Accused of | অভিযুক্ত | He was accused of theft. |\n| Absorbed in | মগ্ন থাকা | He is absorbed in reading. |\n| Congratulate on | অভিনন্দন জানানো | I congratulate you on your success. |\n| Insist on | জেদ ধরা | He insisted on my going there. |\n| Die of / from / for / by | মারা যাওয়া | Died of cholera / Died from overeating / Died for country / Died by poison. |\n| Senior / Junior / Prefer to | অগ্রাধিকার | I prefer tea to coffee. |\n| Cope with (NOT cope up with) | সামলে নেওয়া | She cannot cope with the pressure. |",
                        ],
                        [
                            'title' => 'Time: At, On, In Rules',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• AT: Specific clock times, festivals, points of time (at 5 PM, at midnight, at night, at dawn, at Christmas).\n• ON: Specific days and dates (on Monday, on 26th March, on my birthday).\n• IN: Months, seasons, years, centuries, long periods (in June, in winter, in 2026, in the morning).",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'He died ___ COVID-19.' — Answer: 'of' (disease). [43rd BCS]\n2. 'I prefer reading ___ writing.' — Answer: 'to'. [36th BCS]\n3. 'He insisted ___ my going.' — Answer: 'on'. [41st BCS]\n4. 'Do not look down ___ the poor.' — Answer: 'upon'.",
                        ],
                        [
                            'title' => 'Important Trap',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'Cope with' is the ONLY correct standard English phrase. 'Cope up with' is a very common grammatical blunder that frequently appears in Bank and BCS error-correction questions.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'He died ___ heart failure.'",
                        'options' => ['of', 'from', 'for', 'by'],
                        'correct_option' => 'ক',
                        'explanation' => "কোনো নির্দিষ্ট রোগ বা শারীরিক অসুস্থতায় (Heart failure, Cholera, Malaria) মারা গেলে 'Die of' বসে। অন্যদিকে অতিভোজন বা অতিরিক্ত পরিশ্রমে মারা গেলে 'Die from' বসে।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "Choose the correct preposition: 'I congratulate you ___ your brilliant result.'",
                        'options' => ['for', 'on', 'with', 'at'],
                        'correct_option' => 'খ',
                        'explanation' => "কাউকে কোনো অর্জনে অভিনন্দন জানাতে 'Congratulate on' ব্যবহৃত হয় (NOT congratulate for)।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 8. Conjunction & Connectors
            [
                'name' => 'Conjunction: Coordinating, Subordinating & Correlatives',
                'slug' => 'conjunction-and-connectors',
                'description' => 'Coordinating (FANBOYS), subordinating conjunctions, correlative pairs (not only...but also), and discourse connectors.',
                'is_high_yield' => false,
                'sort_order' => 8,
                'subtopics' => [
                    'Coordinating Conjunctions: FANBOYS (For, And, Nor, But, Or, Yet, So)',
                    'Subordinating Conjunctions: Because, Although, Since, Unless, While',
                    'Correlative Conjunctions: Either...or, Neither...nor, Not only...but also, Both...and',
                    'Conjunctive Adverbs: However, Therefore, Furthermore, Nevertheless',
                    'Unless vs Until (Condition vs Time)'
                ],
                'guide' => [
                    'title' => 'Conjunctions, Connectors & Correlative Parallelism',
                    'summary' => 'Systematic analysis of conjunction classes and maintaining strict parallel structure in correlative conjunction pairs.',
                    'sections' => [
                        [
                            'title' => 'Correlative Conjunction Parallelism',
                            'type' => SectionType::FORMULA,
                            'content' => "Correlative conjunctions MUST connect grammatically parallel structures:\n• 'He is not only intelligent (Adj) but also hardworking (Adj).'\n• INCORRECT: 'He not only lost his ticket but also his luggage.' (Verb vs Noun)\n• CORRECT: 'He lost not only his ticket but also his luggage.' (Noun vs Noun)",
                        ],
                        [
                            'title' => 'Unless vs Until Distinction',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• UNLESS expresses condition ('if not'): 'You will fail unless you work hard.'\n• UNTIL expresses time ('up to the time when'): 'Wait here until I return.'\n• Note: Neither 'unless' nor 'until' can take another negative word ('not') in their clause.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Wait here ___ I return.' — Answer: 'until'. [38th BCS]\n2. 'Scarcely had we left the house ___ it began to rain.' — Answer: 'when'. [40th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Neither... nor / Either... or follows proximity: the verb agrees with the closer subject ('Neither the teacher nor the students were present').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'Wait here ___ I come back.'",
                        'options' => ['unless', 'until', 'since', 'as'],
                        'correct_option' => 'খ',
                        'explanation' => "সময়ের সীমাবদ্ধতা নির্দেশ করতে 'Until' (যে পর্যন্ত না) বসে। অন্যদিকে শর্ত নির্দেশ করতে 'Unless' (যদি না) বসে।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 9. Interjection & Exclamatory Expressions
            [
                'name' => 'Interjection: Emotional Expressions & Exclamations',
                'slug' => 'interjection-and-exclamatory',
                'description' => 'Classification of interjections by emotion, exclamation mark usage, and exclamatory sentence transformation.',
                'is_high_yield' => false,
                'sort_order' => 9,
                'subtopics' => [
                    'Interjections of Joy (Hurrah!), Grief (Alas!), Approval (Bravo!)',
                    'Interjections of Surprise (What! Ha!), Attention (Hark! Look!)',
                    'Exclamatory to Assertive Transformation Rules'
                ],
                'guide' => [
                    'title' => 'Interjections & Exclamatory Sentences Study Guide',
                    'summary' => 'Understanding emotional discourse markers and structural rules of exclamatory sentences in competitive exams.',
                    'sections' => [
                        [
                            'title' => 'Emotion Classifications',
                            'type' => SectionType::EXPLANATION,
                            'content' => "An interjection is a word added to a sentence to express sudden emotional states:\n• Joy: Hurrah! We have won the match.\n• Grief/Pain: Alas! He is undone. Ouch! That hurts.\n• Approval/Praise: Bravo! Well done.\n• Surprise: What! You couldn't finish it?\n• Attention: Listen! Someone is knocking. Hush! The baby is sleeping.",
                        ],
                        [
                            'title' => 'Transformation: Exclamatory to Assertive',
                            'type' => SectionType::FORMULA,
                            'content' => "1. 'What a / How' ➔ 'A very / very':\n   • 'What a beautiful bird it is!' ➔ 'It is a very beautiful bird.'\n   • 'How sweet the song is!' ➔ 'The song is very sweet.'\n2. 'Hurrah!' ➔ 'It is a matter of joy that...'\n3. 'Alas!' ➔ 'It is a matter of sorrow that...'",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'What a fool you are!' into assertive: 'You are a great fool.' [13th BCS]\n2. 'Alas! He is dead' into assertive: 'It is a matter of sorrow that he is dead.'",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ With nouns, use 'great' or 'terrible' instead of 'very': 'What a fool!' ➔ 'You are a great fool' (NOT 'a very fool').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Transform into assertive: 'What a beautiful scenery it is!'",
                        'options' => ['It is very beautiful scenery.', 'It is a very beautiful scenery.', 'It was very beautiful scenery.', 'Is it a beautiful scenery?'],
                        'correct_option' => 'ক',
                        'explanation' => "'Scenery' একটি uncountable noun, তাই এর পূর্বে 'a' বসবে না। সঠিক রূপ: 'It is very beautiful scenery.'",
                        'source' => $src40,
                    ],
                ],
            ],

            // 10. Articles & Determiners
            [
                'name' => 'Articles & Determiners: A, An, The & Quantifiers',
                'slug' => 'articles-and-determiners',
                'description' => 'Vowel sound vs letter rules, the definite article, zero article, and quantifiers (few/little/much/many).',
                'is_high_yield' => true,
                'sort_order' => 10,
                'subtopics' => [
                    'Indefinite Articles (A / An): Sound rules (A European, An honest man, A one-eyed man)',
                    'Definite Article (The): Unique objects, musical instruments, rivers, mountain ranges, superlatives',
                    'Omission of Articles (Zero Article): Languages, sports, meals, abstract concepts',
                    'Quantifiers: Few / A few / The few vs Little / A little / The little',
                    'Much vs Many, Each vs Every'
                ],
                'guide' => [
                    'title' => 'Articles & Quantifiers: Sound Principles & BCS Traps',
                    'summary' => 'Comprehensive vowel sound guide for A/An, exhaustive usage of The, Zero Article rules, and Quantifier distinctions.',
                    'sections' => [
                        [
                            'title' => 'The Sound Principle for A vs An',
                            'type' => SectionType::EXPLANATION,
                            'content' => "The choice between 'a' and 'an' is determined SOLELY by sound, never by spelling:\n• Before vowel SOUNDS ➔ AN (an apple, an honest man, an hour, an heir, an MP, an MBBS, an FRCS).\n• Before consonant SOUNDS ➔ A (a boy, a university [sound 'yu'], a European [sound 'yu'], a one-taka note [sound 'wa'], a union).",
                        ],
                        [
                            'title' => 'Quantifiers Matrix: Countable vs Uncountable',
                            'type' => SectionType::FORMULA,
                            'content' => "| Concept | Countable Nouns | Uncountable Nouns | Meaning |\n|---|---|---|---|\n| Negative (Almost none) | Few ('I have few friends') | Little ('There is little water') | নেই বললেই চলে |\n| Positive (Some amount) | A few ('I have a few friends') | A little ('There is a little water') | কিছু পরিমাণ আছে |\n| Specific portion | The few | The little | যে সামান্য অংশটুকু আছে |",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'He is ___ university student.' — Answer: 'a'. [37th BCS]\n2. 'Give me ___ one-taka note.' — Answer: 'a'. [35th BCS]\n3. 'He has ___ money.' (almost none) — Answer: 'little'. [41st BCS]\n4. 'Which sentence is correct?' — 'He gave me some advice.' (Advice is uncountable, takes no article).",
                        ],
                        [
                            'title' => 'High-Yield Trap',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Zero Article: Do NOT use articles before names of languages ('English is spoken worldwide', but 'The English language is rich'; 'The English' refers to the English people).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'He gave me ___ one-taka note.'",
                        'options' => ['a', 'an', 'the', 'no article'],
                        'correct_option' => 'ক',
                        'explanation' => "'O' দিয়ে শুরু হওয়া শব্দের উচ্চারণ যদি 'ওয়া' (wa) এর মতো হয়, তবে তার পূর্বে 'an' না বসে 'a' বসে। যেমন: a one-eyed man, a one-taka note।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "Choose the correct quantifier: 'There is ___ milk in the glass; it is almost empty.'",
                        'options' => ['a few', 'a little', 'few', 'little'],
                        'correct_option' => 'ঘ',
                        'explanation' => "Milk একটি Uncountable Noun এবং গ্লাসটি প্রায় খালি (নেতিবাচক অর্থ) হওয়ায় 'little' (নেই বললেই চলে) সঠিক উত্তর।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 11. Tense & Sequence of Tenses
            [
                'name' => 'Tense: 12 Structures, Time Adverbials & Sequence of Tenses',
                'slug' => 'tense-and-sequence-of-tenses',
                'description' => 'The 12 tenses, signature time expressions for each tense, before/after past perfect rules, and sequence of tenses principles.',
                'is_high_yield' => true,
                'sort_order' => 11,
                'subtopics' => [
                    '12 Tense Formations & Modal Aspect',
                    'Time Adverbials (Just now, already, recently ➔ Present Perfect; Yesterday, ago ➔ Past Simple)',
                    'Past Perfect with Before and After: Past Perfect BEFORE Past Simple; Past Simple AFTER Past Perfect',
                    'Sequence of Tenses Rules (Principal clause in Past ➔ Subordinate clause in Past)',
                    'Universal Truth & Habitual Fact Exceptions'
                ],
                'guide' => [
                    'title' => 'Tense Mastery: Formulas, Time Markers & Sequence Rules',
                    'summary' => 'Comprehensive formulas for all 12 tenses, instant identification using time markers, and the classic before/after past perfect rules.',
                    'sections' => [
                        [
                            'title' => 'Past Perfect with Before and After',
                            'type' => SectionType::FORMULA,
                            'content' => "Formula:\n1. Past Perfect + BEFORE + Past Simple:\n   • 'The patient had died before the doctor came.'\n2. Past Simple + AFTER + Past Perfect:\n   • 'The doctor came after the patient had died.'",
                        ],
                        [
                            'title' => 'Signature Time Markers for Tenses',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Present Indefinite: Always, regularly, usually, generally, daily, everyday, often.\n• Present Continuous: Now, at present, at this moment, currently.\n• Present Perfect: Just, just now, already, recently, lately, yet, ever.\n• Past Indefinite: Yesterday, ago, long since, last night/year, in 1971.\n• Future Indefinite: Tomorrow, next week/month, in the future, shortly.",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'The train ___ before we reached the station.' — Answer: 'had left'. [39th BCS]\n2. 'I have not seen him since he ___ the town.' — Answer: 'left' (Present Perfect + since + Past Simple). [38th BCS]\n3. 'He said that the earth ___ round the sun.' — Answer: 'moves' (Universal truth remains Present Indefinite).",
                        ],
                        [
                            'title' => 'The \'Since\' Rule Trap',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Since Rule 1: Present Indefinite/Perfect + SINCE + Past Indefinite ('It is ten years since he died').\n★ Since Rule 2: Past Indefinite + SINCE + Past Perfect ('It was ten years since he had died').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'The patient had died before the doctor ___.'",
                        'options' => ['came', 'had come', 'comes', 'was coming'],
                        'correct_option' => 'ক',
                        'explanation' => "Past Perfect + Before + Past Indefinite নিয়ম অনুযায়ী Before এর পরের ক্লজটি Past Indefinite tense (came) হবে।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 12. Right Form of Verbs
            [
                'name' => 'Right Form of Verbs: Master Rules & Competitive Traps',
                'slug' => 'right-form-of-verbs',
                'description' => 'As well as, with, together with, either/neither, subjunctive, unreal past (as if/wish), and gerund/infinitive governing verbs.',
                'is_high_yield' => true,
                'sort_order' => 12,
                'subtopics' => [
                    'As well as / Along with / Together with / In addition to (Verb agrees with FIRST subject)',
                    'Either...or / Neither...nor / Not only...but also (Verb agrees with CLOSER subject)',
                    'It is high time / It is time / Wish / Fancy (Followed by V2)',
                    'As if / As though (Past unreal: was becomes \'were\')',
                    'Would rather / Had better (Followed by bare infinitive V1)',
                    'Mind, Worth, Cannot help, Look forward to, With a view to (Followed by V-ing)'
                ],
                'guide' => [
                    'title' => 'Right Form of Verbs: Master Compendium & Traps',
                    'summary' => 'Comprehensive rules governing verb forms, prepositional -ing phrases, subjunctive structures, and high-frequency exam rules.',
                    'sections' => [
                        [
                            'title' => 'Prepositional Phrases Followed by V-ing',
                            'type' => SectionType::FORMULA,
                            'content' => "Although 'to' usually takes a base verb (V1), the following specific phrases ALWAYS take Verb + ing:\n• Look forward to + V-ing\n• With a view to + V-ing\n• Accustomed to + V-ing\n• Be used to + V-ing\n• Confess to + V-ing\n• Object to + V-ing\n• Would you mind + V-ing\n• Cannot help / Could not help + V-ing\n\nExample: 'He went to the library with a view to reading (NOT read) books.'",
                        ],
                        [
                            'title' => 'Unreal Past: It is high time & As if',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. 'It is high time' / 'It is time' + Subject + Past Indefinite (V2):\n   • 'It is high time we changed our bad habits.' (NOT change)\n2. 'As if' / 'As though' with 'be' verb always takes 'WERE':\n   • 'He speaks as if he were (NOT was) a king.'",
                        ],
                        [
                            'title' => 'Past BCS & Bank Exam Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'It is high time we ___ our corrupt practices.' — Answer: 'stopped'. [44th BCS]\n2. 'I look forward to ___ from you.' — Answer: 'hearing'. [41st BCS]\n3. 'The mayor, with all his councillors, ___ present.' — Answer: 'was' (governed by singular 'mayor'). [36th BCS]",
                        ],
                        [
                            'title' => 'Exam Shortcut Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'Had better' and 'Would rather' act like modal auxiliaries and take bare infinitive V1 WITHOUT 'to': 'You had better go (NOT to go) now.'",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'It is high time we ___ our bad habits.'",
                        'options' => ['change', 'changed', 'have changed', 'had changed'],
                        'correct_option' => 'খ',
                        'explanation' => "'It is high time' বা 'It is time' এর পর Subject থাকলে পরবর্তী Verb-টি Past Indefinite (V2) হয়। সুতরাং 'changed' সঠিক।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "Complete the sentence: 'I am looking forward to ___ you.'",
                        'options' => ['see', 'seeing', 'have seen', 'saw'],
                        'correct_option' => 'খ',
                        'explanation' => "'Look forward to' ফ্রেজটির পর পরবর্তী Verb-এর সাথে 'ing' যুক্ত হয়। সুতরাং 'seeing' সঠিক।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 13. Subject-Verb Agreement
            [
                'name' => 'Subject-Verb Agreement: Proximity, Quantifiers & Exceptions',
                'slug' => 'subject-verb-agreement',
                'description' => 'The number of vs A number of, One of the + plural noun, Collective nouns, distance/money measurements, and parenthetical expressions.',
                'is_high_yield' => true,
                'sort_order' => 13,
                'subtopics' => [
                    'The number of (Singular verb) vs A number of (Plural verb)',
                    'One of the + Plural Noun + Singular Verb (\'One of the boys is...\')',
                    'Relative Pronoun Agreement: \'One of the boys WHO ARE...\'',
                    'Singular-looking Plurals (News, Physics, Politics, Economics ➔ Singular Verb)',
                    'Units of Distance, Money, Time & Measurement (Ten miles IS a long distance)'
                ],
                'guide' => [
                    'title' => 'Subject-Verb Agreement: Comprehensive Rules & Trap Analysis',
                    'summary' => 'Systematic analysis of subject-verb matching, tackling complex intervening modifiers, and resolving quantifier agreement issues.',
                    'sections' => [
                        [
                            'title' => 'Key High-Frequency Formulas',
                            'type' => SectionType::FORMULA,
                            'content' => "1. 'A number of' + Plural Noun + PLURAL Verb:\n   • 'A number of students are absent today.'\n2. 'The number of' + Plural Noun + SINGULAR Verb:\n   • 'The number of students in the class is forty.'\n3. 'One of the' + Plural Noun + SINGULAR Verb:\n   • 'One of my friends is an engineer.'\n4. BUT 'One of the' + Plural Noun + WHO + PLURAL Verb:\n   • 'He is one of the players who have performed brilliantly.'",
                        ],
                        [
                            'title' => 'Units of Measurement Rule',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Expressions of time, money, distance, and weight take a singular verb when considered as a single unit:\n• 'Fifty thousand dollars is a large sum.'\n• 'Twenty miles is a long way to walk.'\n• 'Three years is a long time to wait.'",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'One of the four boys ___ selected.' — Answer: 'was'. [40th BCS]\n2. 'Neither of the accounts ___ correct.' — Answer: 'is'. [38th BCS]\n3. 'Fifty miles ___ a long distance.' — Answer: 'is'. [33rd BCS]",
                        ],
                        [
                            'title' => 'Important Trap',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Words like 'Police', 'People', 'Cattle', 'Poultry', 'Gentry' look singular but ALWAYS take a PLURAL verb: 'The police have arrested the culprit.'",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Fill in the blank: 'One of my best friends ___ a doctor.'",
                        'options' => ['are', 'is', 'were', 'have been'],
                        'correct_option' => 'খ',
                        'explanation' => "'One of + Plural Noun' এর ক্ষেত্রে মূল Subject হলো 'One' (Singular)। তাই এর সাথে Singular Verb 'is' বসবে।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 14. Voice Change: Active to Passive
            [
                'name' => 'Voice Change: Active to Passive Transformation',
                'slug' => 'voice-change-active-to-passive',
                'description' => 'Tense-based passive transformations, interrogative passives (Who/Whom), imperative sentences, quasi-passive verbs, and non-by prepositions.',
                'is_high_yield' => true,
                'sort_order' => 14,
                'subtopics' => [
                    'Standard Tense-based Auxiliary Transformations (Be + V3)',
                    'Interrogative Passives: Who ➔ By whom; Whom ➔ Who',
                    'Imperative Sentences: Let + Object + be + V3',
                    'Double Object Passives & Factitive Objects',
                    'Quasi-Passive Verbs: \'Honey tastes sweet\' ➔ \'Honey is sweet when it is tasted\'',
                    'Passives with Prepositions other than \'By\': Known to, Surprised at, Pleased with, Filled with'
                ],
                'guide' => [
                    'title' => 'Voice Change: Complete Rules & Non-Standard Passives',
                    'summary' => 'Systematic conversion matrix from active to passive voice, interrogative/imperative voice shortcuts, and prepositions beyond \'by\'.',
                    'sections' => [
                        [
                            'title' => 'Imperative & Interrogative Passive Formulas',
                            'type' => SectionType::FORMULA,
                            'content' => "1. Imperative (Order): 'Do it.' ➔ 'Let it be done.'\n2. Imperative (Negative): 'Do not do it.' ➔ 'Let not it be done.' / 'Let it not be done.'\n3. Imperative (Advice/Moral): 'Love the poor.' ➔ 'The poor should be loved.'\n4. Who ➔ By whom:\n   • 'Who wrote Hamlet?' ➔ 'By whom was Hamlet written?'\n5. Whom ➔ Who:\n   • 'Whom did you see?' ➔ 'Who was seen by you?'",
                        ],
                        [
                            'title' => 'Passives without \'By\' (Fixed Prepositions)',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Known TO ('He is known to me')\n• Surprised AT ('I was surprised at his conduct')\n• Pleased WITH ('She was pleased with the gift')\n• Filled WITH ('The glass was filled with water')\n• Contained IN ('Ten litres of water are contained in this jar')",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who wrote the play Hamlet?' in passive: 'By whom was the play Hamlet written?' [44th BCS]\n2. 'Panic seized the writer.' in passive: 'The writer was seized with panic.' [37th BCS]\n3. 'Do not hate the poor.' in passive: 'Let not the poor be hated.' [35th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Intransitive verbs do NOT have a passive form unless they become prepositional verbs (e.g. 'He laughed at the poor man' ➔ 'The poor man was laughed at by him').",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Change the voice: 'Who wrote Hamlet?'",
                        'options' => ['By whom was Hamlet written?', 'Who was written Hamlet by?', 'Whom was Hamlet written by?', 'By who Hamlet was written?'],
                        'correct_option' => 'ক',
                        'explanation' => "Active বাক্যে 'Who' থাকলে Passive-এ 'By whom' দিয়ে শুরু হয়, এরপর Auxiliary verb (was), Subject (Hamlet) এবং V3 (written) বসে।",
                        'source' => $src46,
                    ],
                ],
            ],

            // 15. Narration: Direct & Indirect Speech
            [
                'name' => 'Narration: Direct & Indirect Speech Rules',
                'slug' => 'narration-direct-and-indirect',
                'description' => 'Tense backshift, pronoun shifts, time/place adverb shifts, reporting Assertive, Interrogative, Imperative, Optative, and Exclamatory sentences.',
                'is_high_yield' => false,
                'sort_order' => 15,
                'subtopics' => [
                    'Tense Shift Rules (Present Simple ➔ Past Simple; Past Simple ➔ Past Perfect)',
                    'Adverbial & Demonstrative Shifts (Now ➔ Then, Today ➔ That day, Tomorrow ➔ Next day)',
                    'Assertive Sentences (Said that / Told that)',
                    'Interrogative Sentences (Asked if/whether vs Wh-words; Auxiliary inversion removed)',
                    'Imperative Sentences (Ordered, Requested, Advised + To-infinitive)',
                    'Optative (Wished / Prayed that) & Exclamatory (Exclaimed with joy/sorrow that)'
                ],
                'guide' => [
                    'title' => 'Narration / Indirect Speech Master Guide',
                    'summary' => 'Comprehensive rules for indirect speech transformations across sentence classes, reporting verbs, and tense backshifts.',
                    'sections' => [
                        [
                            'title' => 'Tense & Word Shift Matrix',
                            'type' => SectionType::FORMULA,
                            'content' => "| Direct Speech | Indirect Speech |\n|---|---|\n| Present Simple (do/does) | Past Simple (did) |\n| Present Continuous (is doing) | Past Continuous (was doing) |\n| Present Perfect (have done) | Past Perfect (had done) |\n| Past Simple (did) | Past Perfect (had done) |\n| Will / Shall | Would / Should |\n| Can / May | Could / Might |\n| Today / Tomorrow / Yesterday | That day / The next day / The previous day |\n| Now / Here / This / These | Then / There / That / Those |",
                        ],
                        [
                            'title' => 'Interrogative Sentence Narration',
                            'type' => SectionType::EXPLANATION,
                            'content' => "In indirect questions, the question structure reverts to an assertive structure (Subject + Verb, NO question mark):\n• Direct: He said to me, 'Are you coming?'\n• Indirect: He asked me IF I WAS COMING (NOT 'if was I coming').\n• Direct: She said, 'Where do you live?'\n• Indirect: She asked where I lived.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'He said, \"I have done my work.\"' ➔ 'He said that he had done his work.'\n2. 'The teacher said, \"The earth moves round the sun.\"' ➔ 'The teacher said that the earth moves round the sun.' (Universal truth does NOT backshift).",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'Said to me' in indirect speech becomes 'told me' (without 'to'). Saying 'He told to me' is a major grammatical error.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Change into indirect speech: 'He said to me, \"Where are you going?\"'",
                        'options' => ['He asked me where was I going.', 'He asked me where I was going.', 'He told me where I went.', 'He asked me that where I was going.'],
                        'correct_option' => 'খ',
                        'explanation' => "Interrogative sentence-কে Indirect করার সময় reporting verb 'asked' হয়, Wh-word অপরিবর্তিত থাকে এবং বাক্যটি Assertive (Subject + Verb) রূপ ধারণ করে।",
                        'source' => $src43,
                    ],
                ],
            ],

            // 16. Sentence Structure & Transformation
            [
                'name' => 'Sentence Transformation: Simple, Complex & Compound',
                'slug' => 'sentence-structure-and-transformation',
                'description' => 'Structural classification (Simple, Complex, Compound), transforming between clauses and phrases, and affirmative/negative/degree transformations.',
                'is_high_yield' => true,
                'sort_order' => 16,
                'subtopics' => [
                    'Definitions: Simple (1 finite verb), Complex (1 independent + 1+ dependent), Compound (2+ independent with coordinating conjunction)',
                    'Transforming Simple to Complex to Compound',
                    'Too...to (Simple) ➔ So...that...cannot (Complex) ➔ Very...and so (Compound)',
                    'In spite of / Despite (Simple) ➔ Though / Although (Complex) ➔ But (Compound)',
                    'By + V-ing (Simple) ➔ If you (Complex) ➔ And (Compound)'
                ],
                'guide' => [
                    'title' => 'Sentence Transformation: Simple, Complex & Compound Rules',
                    'summary' => 'Systematic conversion patterns, clause-phrase mapping, and mastering degree and polarity transformations.',
                    'sections' => [
                        [
                            'title' => 'Transformation Conversion Matrix',
                            'type' => SectionType::FORMULA,
                            'content' => "| Simple Structure | Complex Structure | Compound Structure |\n|---|---|---|\n| In spite of / Despite + noun/ing | Though / Although | ... but ... |\n| Because of / On account of | Since / As / Because | ... and so / and therefore ... |\n| Too ... to + V1 | So ... that + cannot/could not | Very ... and cannot/could not |\n| By + V-ing | If you ... | Do ... and you will ... |\n| Without + V-ing | Unless you ... / If you do not ... | Do ... or you will ... |\n| At the time of / In spring | When it is / When it was | It was ... and then ... |",
                        ],
                        [
                            'title' => 'Degrees of Comparison Transformation',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Superlative: 'He is the best boy in the class.'\n• Comparative: 'He is better than any other boy in the class.'\n• Positive: 'No other boy in the class is as good as he.'",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Though he is poor, he is honest.' into simple: 'In spite of his poverty, he is honest.' [38th BCS]\n2. 'He is too weak to walk.' into complex: 'He is so weak that he cannot walk.' [36th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'Despite' is NEVER followed by 'of' ('Despite his wealth', NOT 'Despite of his wealth'). 'In spite of' ALWAYS takes 'of'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Transform into simple sentence: 'Though he worked hard, he failed.'",
                        'options' => ['In spite of working hard, he failed.', 'Despite of working hard, he failed.', 'He worked hard but failed.', 'Because of working hard, he failed.'],
                        'correct_option' => 'ক',
                        'explanation' => "Though/Although যুক্ত Complex বাক্যকে Simple করার নিয়ম হলো: In spite of + Verb-ing (বা Despite + noun phrase)। 'Despite of' ভুল প্রয়োগ।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 17. Phrases & Clauses
            [
                'name' => 'Phrases & Clauses: Identification Techniques',
                'slug' => 'phrases-and-clauses-identification',
                'description' => 'Noun clause, Adjective clause, Adverbial clause, and identifying Noun, Adjective, Prepositional, and Adverbial phrases.',
                'is_high_yield' => true,
                'sort_order' => 17,
                'subtopics' => [
                    'Phrase vs Clause fundamental difference (Finite verb present in Clause; absent in Phrase)',
                    'Noun Clause: Subject, Object of Verb, Object of Preposition, Predicate Noun',
                    'Adjective (Relative) Clause: Modifying a preceding noun antecedent',
                    'Adverbial Clause: Time, Place, Reason, Condition, Concession, Manner',
                    'Phrase Types: Noun Phrase, Adjective Phrase, Adverbial Phrase, Prepositional Phrase'
                ],
                'guide' => [
                    'title' => 'Phrases & Clauses Identification Masterclass',
                    'summary' => 'Definitive methods to test clause functions (The \'IT\' substitution test for Noun Clauses) and identifying relative and adverbial clauses.',
                    'sections' => [
                        [
                            'title' => 'The \'IT\' Substitution Test for Noun Clauses',
                            'type' => SectionType::FORMULA,
                            'content' => "To identify a Noun Clause, replace the entire subordinate clause with the pronoun 'IT' or 'THAT THING':\n• 'I know that he is honest.' ➔ 'I know IT.' (Makes complete sense ➔ NOUN CLAUSE).\n• 'What he said was true.' ➔ 'IT was true.' (Makes complete sense ➔ NOUN CLAUSE).",
                        ],
                        [
                            'title' => 'Adjective Clause vs Adverbial Clause',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. Adjective Clause: Follows immediately after a NOUN antecedent and qualifies it:\n   • 'I know the man who came here yesterday.' ('who came here' qualifies 'man').\n2. Adverbial Clause: Answers When? Where? Why? How? Under what condition?:\n   • 'I will go where he lives.' (Answers Where ➔ Adverbial clause of place).\n   • 'He left after the sun had set.' (Answers When ➔ Adverbial clause of time).",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'I know that he is honest.' The underlined part is a: — Answer: Noun Clause. [40th BCS]\n2. 'Strike while the iron is hot.' The underlined clause is an: — Answer: Adverbial Clause of time. [37th BCS]\n3. 'This is the book which I lost.' The underlined clause is an: — Answer: Adjective Clause. [36th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ If a preposition precedes the clause ('Listen to what I say'), it is a Noun Clause acting as the object of that preposition.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "In the sentence 'I know that he will pass', what kind of clause is 'that he will pass'?",
                        'options' => ['Adjective clause', 'Noun clause', 'Adverbial clause', 'Coordinate clause'],
                        'correct_option' => 'খ',
                        'explanation' => "সমগ্র ক্লজটিকে 'it' দিয়ে প্রতিস্থাপন করা যায় ('I know it') এবং এটি 'know' ট্রানজিটিভ ভার্বের অবজেক্ট হিসেবে কাজ করছে। সুতরাং এটি একটি Noun Clause।",
                        'source' => $src46,
                    ],
                ],
            ],

            // 18. Conditionals & Subjunctive Mood
            [
                'name' => 'Conditionals & Subjunctive: Zero to 3rd & Unreal Moods',
                'slug' => 'conditionals-and-subjunctive-mood',
                'description' => 'Zero, 1st, 2nd, 3rd conditionals, inverted conditionals (Had I known), and subjunctive base-verb structures.',
                'is_high_yield' => true,
                'sort_order' => 18,
                'subtopics' => [
                    'Zero Conditional: If + Present, Present (Scientific/Universal facts)',
                    'First Conditional: If + Present, Future (Will/Can/May + V1)',
                    'Second Conditional: If + Past Simple, Would/Could/Might + V1 (If I were you...)',
                    'Third Conditional: If + Past Perfect, Would have + V3',
                    'Inverted Conditionals: Had I seen him, I would have told him',
                    'Subjunctive Verbs (demand, insist, suggest, recommend, require) + that + Subject + V1 (bare form)'
                ],
                'guide' => [
                    'title' => 'Conditionals & Subjunctive Mood Complete Guide',
                    'summary' => 'Comprehensive conditional formulas, inverted conditionals without \'if\', and mandative subjunctive structures.',
                    'sections' => [
                        [
                            'title' => 'The 4 Conditional Formulas',
                            'type' => SectionType::FORMULA,
                            'content' => "| Conditional | If-Clause | Main Clause | Example |\n|---|---|---|---|\n| Zero | Present Simple | Present Simple | If you heat ice, it melts. |\n| First (Real) | Present Simple | Will / Can + V1 | If it rains, we will stay home. |\n| Second (Unreal Present) | Past Simple | Would / Could + V1 | If I had money, I would buy a car. |\n| Third (Unreal Past) | Past Perfect | Would have + V3 | If I had studied, I would have passed. |",
                        ],
                        [
                            'title' => 'Mandative Subjunctive Rules',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Verbs of demand, recommendation, or urgency (demand, insist, recommend, suggest, require, urge, ask) followed by 'that' take the BARE VERB (V1) for all subjects (no -s, no past tense, no modal):\n• 'The doctor recommended that he STOP (NOT stops) smoking.'\n• 'I insist that she BE (NOT is/was) present.'",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Had I known her, I ___ her to the party.' — Answer: 'would have invited'. [44th BCS]\n2. 'If I were you, I ___ the job.' — Answer: 'would take'. [38th BCS]\n3. 'The committee suggested that the proposal ___ accepted.' — Answer: 'be'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ In Second Conditional, the 'be' verb is ALWAYS 'were' regardless of whether the subject is I, he, she, or it: 'If I were a king', 'If she were here'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Complete the sentence: 'If he had invited me, I ___ the party.'",
                        'options' => ['would attend', 'will attend', 'would have attended', 'had attended'],
                        'correct_option' => 'গ',
                        'explanation' => "Third Conditional-এর নিয়ম অনুযায়ী If-clause-এ Past Perfect (had invited) থাকলে Main clause-এ 'would have + V3' (would have attended) বসে।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 19. Non-Finite Verbs: Gerund, Participle & Infinitive
            [
                'name' => 'Non-Finite Verbs: Gerund, Participle & Infinitive',
                'slug' => 'non-finite-verbs-gerund-participle',
                'description' => 'Gerund vs Participle distinction, Verbal Noun, Perfect Participle, Bare Infinitives, and Split Infinitives.',
                'is_high_yield' => true,
                'sort_order' => 19,
                'subtopics' => [
                    'Gerund (Verb + ing functioning as NOUN)',
                    'Participle (Verb + ing / V3 functioning as ADJECTIVE)',
                    'The Definitive Test to Distinguish Gerund from Participle',
                    'Verbal Noun: The + V-ing + of (\'The reading of history is useful\')',
                    'Bare Infinitive (verbs taking infinitive without \'to\': see, hear, let, make, bid)',
                    'Dangling Participles and their corrections'
                ],
                'guide' => [
                    'title' => 'Gerund, Participle & Infinitive Distinction Guide',
                    'summary' => 'Never confuse a Gerund with a Participle again: structural tests, verbal noun criteria, and bare infinitive rules.',
                    'sections' => [
                        [
                            'title' => 'Gerund vs Participle: The 3-Step Deciding Test',
                            'type' => SectionType::FORMULA,
                            'content' => "1. Replace with 'IT':\n   • 'Swimming is good exercise.' ➔ 'IT is good exercise.' ➔ GERUND (Noun function).\n2. Test of State/Continuous Action:\n   • 'Look at the swimming boy.' ➔ The boy IS swimming ➔ PARTICIPLE (Adjective function).\n3. Preposition Test:\n   • Noun/Preposition + V-ing ➔ GERUND ('He is fond of reading').",
                        ],
                        [
                            'title' => 'Verbal Noun Formulation',
                            'type' => SectionType::EXPLANATION,
                            'content' => "A Verbal Noun is a V-ing preceded by 'The' and followed by 'Of':\n• 'The reading of newspapers is a good habit.'\n• If either 'the' or 'of' is missing, it is a Gerund: 'Reading newspapers is a good habit.'",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'A rolling stone gathers no moss.' Here 'rolling' is a: — Answer: Participle. [36th BCS]\n2. 'I like walking in the morning.' Here 'walking' is a: — Answer: Gerund. [38th BCS]\n3. 'The reading of books is a delight.' Here 'reading' is a: — Answer: Verbal Noun. [41st BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ 'Walking stick' = A stick FOR walking (Gerund). 'Walking boy' = A boy who IS walking (Participle).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "In the sentence 'Walking is good for health', what is 'Walking'?",
                        'options' => ['Participle', 'Gerund', 'Verbal Noun', 'Adjective'],
                        'correct_option' => 'খ',
                        'explanation' => "'Walking' এখানে বাক্যের Subject হিসেবে Noun-এর কাজ করছে (Verb + Noun = Gerund)। সুতরাং এটি একটি Gerund।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 20. Modifiers, Inversion & Parallelism
            [
                'name' => 'Modifiers, Inversion & Parallel Structure',
                'slug' => 'modifiers-inversion-parallelism',
                'description' => 'Pre-modifiers, post-modifiers, dangling modifiers, subject-auxiliary inversion, and parallel structure in series.',
                'is_high_yield' => false,
                'sort_order' => 20,
                'subtopics' => [
                    'Pre-modifiers (determiners, adjectives, noun adjuncts, participles)',
                    'Post-modifiers (prepositional phrases, relative clauses, appositives, infinitives)',
                    'Dangling / Misplaced Modifiers and error correction',
                    'Parallel Structure in lists, comparisons, and correlative pairs'
                ],
                'guide' => [
                    'title' => 'Modifiers, Inversion & Parallelism Rules',
                    'summary' => 'How to avoid dangling modifiers and maintain strict grammatical parallelism in sentence structure.',
                    'sections' => [
                        [
                            'title' => 'Dangling Modifiers Fix',
                            'type' => SectionType::FORMULA,
                            'content' => "A dangling modifier occurs when the introductory participle phrase does not logically match the subject that immediately follows it:\n• INCORRECT: 'Walking in the garden, a snake bit him.' (The snake was NOT walking in the garden!)\n• CORRECT: 'While he was walking in the garden, a snake bit him.' OR 'Walking in the garden, he was bitten by a snake.'",
                        ],
                        [
                            'title' => 'Parallel Structure Rule',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Elements in a list or comparison must share the exact same grammatical form:\n• INCORRECT: 'She likes swimming, to jog, and reading.'\n• CORRECT: 'She likes swimming, jogging, and reading.' (All Gerunds)\n• INCORRECT: 'He is admired for his honesty, his intelligence, and because he is kind.'\n• CORRECT: 'He is admired for his honesty, his intelligence, and his kindness.' (All Noun phrases)",
                        ],
                        [
                            'title' => 'Past Exam Examples',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Not only did he fail, but he also lost his scholarship.' (Parallel inverted structure). [Bank AD]\n2. 'Having finished the assignment, the TV was turned on.' (Dangling modifier error).",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Always ask 'WHO is performing the introductory action?' That person must be the subject of the main clause immediately after the comma.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Identify the sentence free from dangling modifier:",
                        'options' => [
                            'Walking through the forest, the birds were singing.',
                            'Walking through the forest, he heard the birds singing.',
                            'Walking through the forest, the trees were green.',
                            'Walking through the forest, it began to rain.'
                        ],
                        'correct_option' => 'খ',
                        'explanation' => "'Walking through the forest' কাজটি কোনো ব্যক্তি (he) করছে, পাখি বা গাছপালা নয়। তাই কমা-র পরে 'he' থাকা বাক্যটিই ব্যাকরণগতভাবে নির্ভুল।",
                        'source' => $srcBank,
                    ],
                ],
            ],

            // 21. Vocabulary: Synonyms & Antonyms
            [
                'name' => 'Vocabulary: High-Frequency Synonyms & Antonyms',
                'slug' => 'vocabulary-synonyms-and-antonyms',
                'description' => 'Root words, Latin & Greek prefixes, high-frequency competitive exam words, contextual synonyms, and antonym pairs.',
                'is_high_yield' => true,
                'sort_order' => 21,
                'subtopics' => [
                    'Root Word Techniques (Bene-, Mal-, Chron-, Phil-, Path-)',
                    'High-Frequency BCS Words: Panacea, Obsolete, Ephemeral, Candid, Meticulous, Benevolent',
                    'Antonym Pairs with prefix traps (In-, Im-, Un-, Dis-, Non-)',
                    'Word Analogies for Bank Recruitment Examinations'
                ],
                'guide' => [
                    'title' => 'Competitive Vocabulary: Roots, Synonyms & Antonyms',
                    'summary' => 'Master root word breakdown, 200+ high-frequency competitive exam vocabulary items, and verbal analogies.',
                    'sections' => [
                        [
                            'title' => 'High-Frequency BCS & Bank Vocabulary Table',
                            'type' => SectionType::FORMULA,
                            'content' => "| Word | Meaning | Synonym | Antonym |\n|---|---|---|---|\n| Panacea | সর্বরোগের মহৌষধ | Cure-all, Elixir | Poison, Disease |\n| Ephemeral | ক্ষণস্থায়ী | Transient, Fleeting, Short-lived | Permanent, Eternal |\n| Candid | অকপট, স্পষ্টবাদী | Frank, Honest, Forthright | Deceitful, Secretive |\n| Meticulous | অতি সতর্ক ও খুঁতখুঁতে | Scrupulous, Careful, Thorough | Careless, Sloppy |\n| Obsolete | অপ্রচলিত | Outdated, Archaic, Extinct | Modern, Current |\n| Benevolent | পরোপকারী, দয়ালু | Altruistic, Generous, Kind | Malevolent, Cruel |\n| Pragmatic | বাস্তববাদী | Practical, Realistic | Idealistic, Visionary |",
                        ],
                        [
                            'title' => 'Root Word Morphology',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• BENE (Good): Benefactor, Benevolent, Beneficial, Beneficiary.\n• MAL (Bad): Malevolent, Malicious, Malfunction, Malnutrition.\n• CHRON (Time): Chronology, Synchronize, Chronic, Anachronism.\n• PHIL (Love): Philanthropist (lover of humanity), Bibliophile (book lover), Philosophy.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'What is the synonym of \"Panacea\"?' — Answer: 'Cure-all'. [41st BCS]\n2. 'What is the antonym of \"Ephemeral\"?' — Answer: 'Permanent'. [38th BCS]\n3. 'What is the meaning of \"Meticulous\"?' — Answer: 'Over-careful about details'. [40th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ When solving Antonyms, verify the part of speech: an adjective requires an adjective antonym, a verb requires a verb antonym.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "What is the synonym of 'Panacea'?",
                        'options' => ['Incurable disease', 'Cure-all', 'Poisonous substance', 'Praise'],
                        'correct_option' => 'খ',
                        'explanation' => "'Panacea' অর্থ সর্বরোগের মহৌষধ বা সমাধান (A remedy for all difficulties or diseases)। এর উপযুক্ত সমার্থক শব্দ 'Cure-all'।",
                        'source' => $src45,
                    ],
                    [
                        'question' => "What is the antonym of 'Ephemeral'?",
                        'options' => ['Transient', 'Short-lived', 'Permanent', 'Fleeting'],
                        'correct_option' => 'গ',
                        'explanation' => "'Ephemeral' অর্থ ক্ষণস্থায়ী (lasting for a very short time)। এর বিপরীত শব্দ 'Permanent' (চিরস্থায়ী)।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 22. Idioms, Phrases & Group Verbs
            [
                'name' => 'Idioms, Phrases & Group Verbs',
                'slug' => 'idioms-phrases-group-verbs',
                'description' => 'A to Z high-yield idioms (Achilles heel, Dark horse, Maiden speech), proverb meanings, and phrasal verbs.',
                'is_high_yield' => true,
                'sort_order' => 22,
                'subtopics' => [
                    'Top 100 BCS & Bank Idioms (At a loss, Apple of discord, By and large, Once in a blue moon)',
                    'Phrasal Verbs with BREAK (break out, break down, break up)',
                    'Phrasal Verbs with CALL (call off, call on, call in, call for)',
                    'Phrasal Verbs with LOOK (look after, look into, look down upon, look for)',
                    'Phrasal Verbs with PUT (put off, put up with, put out, put on)'
                ],
                'guide' => [
                    'title' => 'Idioms, Phrases & Phrasal Verbs Digest',
                    'summary' => 'Comprehensive directory of competitive exam idioms and mastering confusing phrasal verbs.',
                    'sections' => [
                        [
                            'title' => 'High-Frequency Idioms Index',
                            'type' => SectionType::FORMULA,
                            'content' => "| Idiom | Literal Meaning | Exam Context |\n|---|---|---|\n| Achilles' heel | A vulnerable point | দূর্বল জায়গা |\n| Dark horse | An unexpected winner | অপ্রত্যাশিত বিজয়ী |\n| Maiden speech | First speech | প্রথম ভাষণ |\n| White elephant | Costly but useless possession | ব্যয়বহুল কিন্তু অকেজো জিনিস |\n| By leaps and bounds | Very rapidly | দ্রুতগতিতে |\n| Once in a blue moon | Very rarely | কদাচিৎ, খুবই বিরল |\n| Cock and bull story | Absurd, unbelievable story | গাঁজাখুরি গল্প |\n| To smell a rat | To suspect foul play | সন্দেহ করা |",
                        ],
                        [
                            'title' => 'Essential Phrasal Verbs Matrix',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Call off ➔ Cancel ('The strike was called off').\n• Call on ➔ Visit a person ('I called on him yesterday').\n• Call in ➔ Summon ('Call in a doctor').\n• Put out ➔ Extinguish a fire ('Put out the light').\n• Put off ➔ Postpone ('Do not put off till tomorrow').\n• Put up with ➔ Tolerate ('I cannot put up with this insult').\n• Look into ➔ Investigate ('The police are looking into the matter').",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'What is the meaning of \"White Elephant\"?' — Answer: 'A very costly or troublesome possession'. [38th BCS]\n2. 'The phrase \"Achilles\' heel\" means:' — Answer: 'A weak point'. [41st BCS]\n3. 'The meeting was called off.' 'Called off' means: — Answer: 'Cancelled'. [43rd BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: 'Put out' means to extinguish a fire; 'Put off' means to delay or postpone an event.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "What is the meaning of the idiom 'A white elephant'?",
                        'options' => ['An African elephant', 'A very costly but useless possession', 'A rare animal', 'A great achievement'],
                        'correct_option' => 'খ',
                        'explanation' => "'A white elephant' বাগধারাটির অর্থ অত্যন্ত ব্যয়বহুল অথচ কোনো কাজে আসে না এমন অধিকার বা সম্পত্তি।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "The phrase 'Call off' means:",
                        'options' => ['Cancel', 'Postpone', 'Continue', 'Start'],
                        'correct_option' => 'ক',
                        'explanation' => "'Call off' একটি Phrasal Verb যার অর্থ বাতিল করা বা প্রত্যাহার করা (To cancel or abandon an event/strike)।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 23. One Word Substitution
            [
                'name' => 'One Word Substitution: High-Frequency Competitive Vocabulary',
                'slug' => 'one-word-substitution',
                'description' => 'Persons, professions, forms of government, types of killings (-cide), phobias, and sciences in one-word equivalents.',
                'is_high_yield' => true,
                'sort_order' => 23,
                'subtopics' => [
                    'Personal Attributes: Philanthropist, Misogynist, Optimist, Pessimist, Polyglot',
                    'Killings (-cide): Patricide, Matricide, Regicide, Homicide, Fratricide',
                    'Governments (-cracy/-archy): Autocracy, Bureaucracy, Democracy, Oligarchy, Anarchy',
                    'Places & Habitats: Aviary, Apiary, Arsenal, Mortuary, Aquarium',
                    'Phobias & Manias: Claustrophobia, Hydrophobia, Bibliophile'
                ],
                'guide' => [
                    'title' => 'One Word Substitution Compendium',
                    'summary' => 'Systematic categorisation of one-word expressions for competitive exams: killings, forms of government, professions, and places.',
                    'sections' => [
                        [
                            'title' => 'Root Suffix Categorisation',
                            'type' => SectionType::FORMULA,
                            'content' => "1. -CIDE (Killing/Murder):\n   • Regicide = Murder of a King\n   • Patricide = Murder of Father\n   • Matricide = Murder of Mother\n   • Fratricide = Murder of Brother\n   • Homicide = Murder of a Human Being\n2. -CRACY / -ARCHY (Government):\n   • Autocracy = Rule by one person with unlimited power\n   • Bureaucracy = Government by officials\n   • Oligarchy = Rule by a small group of people\n   • Plutocracy = Government by the wealthy\n   • Anarchy = Absence of government/lawlessness",
                        ],
                        [
                            'title' => 'High-Frequency People & Personalities',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Philanthropist = One who loves and helps mankind.\n• Misogynist = One who hates women.\n• Polyglot = One who knows many languages.\n• Numismatist = One who collects coins.\n• Omnipresent = Present everywhere at the same time.\n• Omniscient = Knowing everything.\n• Omnipotent = All-powerful.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'A person who hates women is called a:' — Answer: 'Misogynist'. [40th BCS]\n2. 'A place where bees are kept is called:' — Answer: 'Apiary'. [37th BCS]\n3. 'A place where birds are kept is called:' — Answer: 'Aviary'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember the distinction: Apiary is for bees (Apiculture); Aviary is for birds (Aviation).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "A person who loves humanity and works for its welfare is called a:",
                        'options' => ['Misanthrope', 'Philanthropist', 'Philosopher', 'Polyglot'],
                        'correct_option' => 'খ',
                        'explanation' => "'Philanthropist' (মানবপ্রেমিক) যিনি মানুষের মঙ্গলের জন্য কাজ ও দান করেন। এর বিপরীত হলো 'Misanthrope' (মানববিদ্বেষী)।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 24. Spelling Rules & Confusing Words
            [
                'name' => 'Spelling Rules, Homophones & Confusing Words',
                'slug' => 'spelling-rules-confusing-words',
                'description' => 'Commonly misspelled competitive exam words, homophones (principal vs principle), and foreign Latin/French terms in English.',
                'is_high_yield' => true,
                'sort_order' => 24,
                'subtopics' => [
                    'Top 50 Misspelled Words: Bureaucracy, Committee, Questionnaire, Millennium, Embarrass, Lieutenant',
                    'Homophones: Principal vs Principle, Stationary vs Stationery, Complement vs Compliment',
                    'Silent Letters & Double Consonant Rules',
                    'Foreign Expressions: Ad hoc, De facto, Status quo, Bonafide, Alma mater, Per se'
                ],
                'guide' => [
                    'title' => 'Spelling Rules, Homophones & Foreign Terms',
                    'summary' => 'Mnemonics for troublesome spellings, resolving homophone confusion, and understanding foreign expressions used in English.',
                    'sections' => [
                        [
                            'title' => 'Top 20 Most Tested Spellings in BCS & Banks',
                            'type' => SectionType::FORMULA,
                            'content' => "1. Committee (Double m, double t, double e)\n2. Questionnaire (Double n, ending with -aire)\n3. Millennium (Double l, double n)\n4. Embarrass (Double r, double s)\n5. Harass (Single r, double s)\n6. Bureaucracy (B-U-R-E-A-U-C-R-A-C-Y)\n7. Lieutenant (L-I-E-U-T-E-N-A-N-T ➔ Lie-u-ten-ant)\n8. Accommodate (Double c, double m)\n9. Occurred (Double c, double r)\n10. Maintenance (M-A-I-N-T-E-N-A-N-C-E, NOT maintainance)",
                        ],
                        [
                            'title' => 'Homophones Decoded',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Principal (Noun: Head of institution / Capital sum; Adj: Main) vs Principle (Noun: Fundamental rule/moral truth).\n• Stationery (with 'er' like paper/pen: Writing materials) vs Stationary (with 'ar' like car: Not moving, fixed).\n• Complement (completes something) vs Compliment (praise/admiration).\n• Council (an advisory body) vs Counsel (advice or lawyer).",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Choose the correctly spelled word:' (a) Commitee (b) Committee (c) Comittee — Answer: (b) Committee. [38th BCS]\n2. 'Choose the correct spelling:' — Answer: 'Lieutenant'. [35th BCS]\n3. 'The term \"De facto\" means:' — Answer: 'In reality / In fact'. [40th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: 'StationEry' has 'e' for Envelope; 'StationAry' has 'a' for At rest.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Which of the following is the correct spelling?",
                        'options' => ['Bureaucracy', 'Bureacracy', 'Beurocracy', 'Bureaucrasy'],
                        'correct_option' => 'ক',
                        'explanation' => "'Bureaucracy' (আমলাতন্ত্র) এর সঠিক বানান B-U-R-E-A-U-C-R-A-C-Y। এটি প্রতিযোগিতামূলক পরীক্ষার সবচেয়ে নিয়মিত বানানগুলোর একটি।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "Choose the correct spelling:",
                        'options' => ['Questionaire', 'Questionnaire', 'Questionair', 'Questionnare'],
                        'correct_option' => 'খ',
                        'explanation' => "'Questionnaire' (প্রশ্নমালা) শব্দটিতে double 'n' এবং শেষে 'aire' থাকে। সঠিক বানান: Q-U-E-S-T-I-O-N-N-A-I-R-E।",
                        'source' => $src45,
                    ],
                ],
            ],
        ];

        // Seed Topics, Guides and Questions
        foreach ($topicsData as $tData) {
            $topic = Topic::updateOrCreate(
                [
                    'subject_id' => $english->id,
                    'slug' => $tData['slug'],
                ],
                [
                    'chapter_id' => $chapter->id,
                    'parent_id' => null,
                    'name' => $tData['name'],
                    'description' => $tData['description'],
                    'is_high_yield' => $tData['is_high_yield'],
                    'sort_order' => $tData['sort_order'],
                    'status' => ContentStatus::PUBLISHED,
                    'created_by' => $adminId,
                ]
            );

            // Subtopics
            if (!empty($tData['subtopics'])) {
                $subOrder = 1;
                foreach ($tData['subtopics'] as $subName) {
                    Topic::updateOrCreate(
                        [
                            'subject_id' => $english->id,
                            'parent_id' => $topic->id,
                            'name' => $subName,
                        ],
                        [
                            'chapter_id' => $chapter->id,
                            'slug' => \Illuminate\Support\Str::slug($tData['slug'] . '-' . $subName),
                            'description' => "Detailed curriculum discussion on {$subName} under {$tData['name']}.",
                            'is_high_yield' => false,
                            'sort_order' => $subOrder++,
                            'status' => ContentStatus::PUBLISHED,
                            'created_by' => $adminId,
                        ]
                    );
                }
            }

            // Study Guide
            if (!empty($tData['guide'])) {
                $gData = $tData['guide'];
                $guide = StudyGuide::updateOrCreate(
                    [
                        'slug' => 'guide-' . $tData['slug'],
                    ],
                    [
                        'topic_id' => $topic->id,
                        'title' => $gData['title'],
                        'summary' => $gData['summary'],
                        'content' => $gData['summary'],
                        'status' => ContentStatus::PUBLISHED,
                        'published_at' => now(),
                        'created_by' => $adminId,
                    ]
                );

                if (!empty($gData['sections'])) {
                    $secOrder = 1;
                    $guide->sections()->delete();
                    foreach ($gData['sections'] as $sec) {
                        $guide->sections()->create([
                            'title' => $sec['title'],
                            'content' => $sec['content'],
                            'section_type' => $sec['type'],
                            'sort_order' => $secOrder++,
                        ]);
                    }
                }
            }

            // Questions
            if (!empty($tData['questions'])) {
                foreach ($tData['questions'] as $qData) {
                    $options = [
                        'ক' => $qData['options'][0],
                        'খ' => $qData['options'][1],
                        'গ' => $qData['options'][2],
                        'ঘ' => $qData['options'][3],
                    ];

                    $question = PublicQuestion::updateOrCreate(
                        [
                            'question' => $qData['question'],
                            'subject_id' => $english->id,
                        ],
                        [
                            'chapter_id' => $chapter->id,
                            'topic_id' => $topic->id,
                            'source_id' => $qData['source']?->id,
                            'answer' => $options[$qData['correct_option']],
                            'options' => $options,
                            'correct_option' => $qData['correct_option'],
                            'explanation' => $qData['explanation'],
                            'difficulty' => DifficultyLevel::EASY,
                            'question_type' => QuestionType::MCQ,
                            'status' => ContentStatus::PUBLISHED,
                            'marks' => 1.00,
                            'negative_marks' => 0.50,
                            'created_by' => $adminId,
                        ]
                    );

                    $question->optionsList()->delete();
                    $ord = 1;
                    foreach ($options as $k => $txt) {
                        $question->optionsList()->create([
                            'option_key' => $k,
                            'option_text' => $txt,
                            'is_correct' => ($k === $qData['correct_option']),
                            'sort_order' => $ord++,
                        ]);
                    }

                    if ($qData['source']?->exam_id) {
                        $question->exams()->syncWithoutDetaching([$qData['source']->exam_id]);
                    }
                }
            }
        }
    }
}
