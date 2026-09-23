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

class EnglishLiteratureSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@prepareme.com')->first();
        $adminId = $admin?->id;

        $english = Subject::where('slug', 'english')->firstOrFail();

        // 2. Chapter: English Literature
        $chapter = Chapter::updateOrCreate(
            ['subject_id' => $english->id, 'slug' => 'english-literature'],
            [
                'name' => 'English Literature',
                'description' => 'Literary Periods, Old & Middle English, Elizabethan, Shakespeare, Romantic Poets, Victorian Novelists, Modern Literature, Literary Terms & Quotes.',
                'status' => ContentStatus::PUBLISHED,
                'sort_order' => 2,
                'created_by' => $adminId,
            ]
        );

        // Sources & Exams
        $bcs = Exam::where('slug', 'bcs-preliminary')->first();
        $bank = Exam::where('slug', 'bank-job-recruitment')->first();

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
        $src41 = QuestionSource::firstOrCreate(
            ['slug' => '41st-bcs-preliminary'],
            ['name' => '৪১তম বিসিএস প্রিলিমিনারি পরীক্ষা (2021)', 'exam_id' => $bcs?->id, 'year' => 2021, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src40 = QuestionSource::firstOrCreate(
            ['slug' => '40th-bcs-preliminary'],
            ['name' => '৪০তম বিসিএস প্রিলিমিনারি পরীক্ষা (2019)', 'exam_id' => $bcs?->id, 'year' => 2019, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src38 = QuestionSource::firstOrCreate(
            ['slug' => '38th-bcs-preliminary'],
            ['name' => '৩৮তম বিসিএস প্রিলিমিনারি পরীক্ষা (2017)', 'exam_id' => $bcs?->id, 'year' => 2017, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src37 = QuestionSource::firstOrCreate(
            ['slug' => '37th-bcs-preliminary'],
            ['name' => '৩৭তম বিসিএস প্রিলিমিনারি পরীক্ষা (2016)', 'exam_id' => $bcs?->id, 'year' => 2016, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src36 = QuestionSource::firstOrCreate(
            ['slug' => '36th-bcs-preliminary'],
            ['name' => '৩৬তম বিসিএস প্রিলিমিনারি পরীক্ষা (2016)', 'exam_id' => $bcs?->id, 'year' => 2016, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );
        $src35 = QuestionSource::firstOrCreate(
            ['slug' => '35th-bcs-preliminary'],
            ['name' => '৩৫তম বিসিএস প্রিলিমিনারি পরীক্ষা (2015)', 'exam_id' => $bcs?->id, 'year' => 2015, 'status' => ContentStatus::PUBLISHED, 'created_by' => $adminId]
        );

        $topicsData = [
            // 25. Periods of English Literature
            [
                'name' => 'Periods of English Literature: Chronological Timeline',
                'slug' => 'periods-of-english-literature',
                'description' => 'Chronological breakdown from Old English (450) to Post-Modernism, sub-ages, historical markers, and major milestones.',
                'is_high_yield' => true,
                'sort_order' => 1,
                'subtopics' => [
                    'Old English / Anglo-Saxon Period (450–1066)',
                    'Middle English Period (1066–1500)',
                    'The Renaissance Period (1500–1660: Elizabethan, Jacobean, Caroline, Commonwealth)',
                    'The Neoclassical Period (1660–1798: Restoration, Augustan, Sensibility)',
                    'The Romantic Period (1798–1837)',
                    'The Victorian Period (1837–1901)',
                    'The Modern Period (1901–1939: Edwardian, Georgian) & Post-Modern (1939–Present)'
                ],
                'guide' => [
                    'title' => 'Periods of English Literature: Complete Chronological Master Chart',
                    'summary' => 'Comprehensive chronological timeline of all literary periods, date boundaries, defining characteristics, and landmark literary events.',
                    'sections' => [
                        [
                            'title' => 'Chronological Timeline Table',
                            'type' => SectionType::FORMULA,
                            'content' => "| Period / Age | Duration | Landmark Works / Events |\n|---|---|---|\n| Old English (Anglo-Saxon) | 450–1066 | Beowulf (earliest epic) |\n| Middle English | 1066–1500 | Norman Conquest (1066), Chaucer's Canterbury Tales |\n| The Renaissance | 1500–1660 | Elizabethan (1558–1603), Jacobean (1603–1625) |\n| The Neoclassical | 1660–1798 | Restoration (1660–1700), Augustan (1700–1745) |\n| The Romantic Period | 1798–1837 | Lyrical Ballads (1798) to Queen Victoria's coronation |\n| The Victorian Period | 1837–1901 | Reign of Queen Victoria (1837–1901) |\n| The Modern Period | 1901–1939 | WWI, High Modernism (Eliot, Joyce, Woolf) |\n| The Post-Modern Period | 1939–Present | WWII onwards, Absurd drama, Post-colonialism |",
                        ],
                        [
                            'title' => 'Sub-Divisions of the Renaissance & Neoclassical',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. The Renaissance (1500–1660):\n   • Preparation for Renaissance: 1500–1558\n   • Elizabethan Age: 1558–1603 (Golden Age, Shakespeare, Spenser, Marlowe)\n   • Jacobean Age: 1603–1625 (Reign of James I, King James Bible 1611, Donne)\n   • Caroline Age: 1625–1649 (Reign of Charles I, Cavalier poets)\n   • Commonwealth Period: 1649–1660 (Puritan rule under Oliver Cromwell, Theatres closed 1642)\n2. The Neoclassical Period (1660–1798):\n   • Restoration: 1660–1700 (Monarchy restored, Dryden)\n   • Augustan Age / Age of Pope: 1700–1745 (Alexander Pope, Swift, Defoe)\n   • Age of Sensibility / Age of Johnson: 1745–1798 (Samuel Johnson)",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Which period is known as the \"Golden Age\" of English literature?' — Answer: 'The Elizabethan Period'. [38th BCS]\n2. 'The Romantic Period began with the publication of:' — Answer: 'Lyrical Ballads' in 1798. [40th BCS]\n3. 'Shakespeare belonged to which period?' — Answer: 'The Elizabethan / Renaissance Period'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Landmark Boundary Dates: 1066 (Norman Conquest), 1558 (Queen Elizabeth ascends throne), 1642 (Theatres closed), 1660 (Monarchy restored), 1798 (Lyrical Ballads published), 1837 (Queen Victoria ascends throne), 1901 (Queen Victoria dies).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Which period in English literature is called the 'Golden Age'?",
                        'options' => ['The Victorian Period', 'The Elizabethan Period', 'The Romantic Period', 'The Augustan Period'],
                        'correct_option' => 'খ',
                        'explanation' => "রানি ১ম এলিজাবেথের রাজত্বকাল (১৫৫৮-১৬০৩) ছিল ইংরেজি সাহিত্যের স্বর্ণযুগ (Golden Age)। এই যুগে শেক্সপিয়র, মার্লো, স্পেন্সার ও বেকন সাহিত্যকে অনন্য উচ্চতায় নিয়ে যান।",
                        'source' => $src38,
                    ],
                    [
                        'question' => "The Romantic Period in English literature officially began with the publication of which book?",
                        'options' => ['Paradise Lost (1667)', 'Lyrical Ballads (1798)', 'The Canterbury Tales (1400)', 'The Waste Land (1922)'],
                        'correct_option' => 'খ',
                        'explanation' => "১৭৯৮ সালে উইলিয়াম ওয়ার্ডসওয়ার্থ ও এস.টি. কোলরিজের যৌথ কাব্যগ্রন্থ 'Lyrical Ballads' প্রকাশের মাধ্যমে ইংরেজি সাহিত্যে রোমান্টিক যুগের সূচনা ঘটে।",
                        'source' => $src40,
                    ],
                ],
            ],

            // 26. Geoffrey Chaucer & Pre-Renaissance
            [
                'name' => 'Geoffrey Chaucer & Pre-Renaissance Literature',
                'slug' => 'chaucer-and-pre-renaissance',
                'description' => 'Father of English Poetry Geoffrey Chaucer, The Canterbury Tales, William Caxton\'s printing press, and Middle English landmarks.',
                'is_high_yield' => false,
                'sort_order' => 2,
                'subtopics' => [
                    'Beowulf: The Oldest Epic in English Literature',
                    'Geoffrey Chaucer: Father of English Poetry & Literature',
                    'The Canterbury Tales: Structure, Prologue & Pilgrims',
                    'John Wycliffe: Morning Star of the Reformation & First English Bible',
                    'William Caxton: Introduction of the Printing Press in England (1476)',
                    'Sir Thomas Malory: Le Morte d\'Arthur & Medieval Romance'
                ],
                'guide' => [
                    'title' => 'Geoffrey Chaucer & Early English Literature Guide',
                    'summary' => 'Comprehensive look at Beowulf, Chaucer\'s masterwork The Canterbury Tales, and the birth of modern vernacular English.',
                    'sections' => [
                        [
                            'title' => 'Chaucer\'s Contribution & Titles',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Geoffrey Chaucer (1340–1400) is universally revered as:\n• The 'Father of English Literature'\n• The 'Father of English Poetry'\n• The 'Morning Star of the Renaissance'\n• The first poet to be buried in Poets' Corner of Westminster Abbey.\n\nHis magnum opus, 'The Canterbury Tales', is written in Middle English (East Midland dialect) using the heroic couplet (rhymed iambic pentameter). It portrays 29 pilgrims travelling from the Tabard Inn in Southwark to the shrine of Saint Thomas Becket at Canterbury.",
                        ],
                        [
                            'title' => 'Pre-Renaissance Key Figures & Landmarks',
                            'type' => SectionType::FORMULA,
                            'content' => "1. Beowulf: Oldest epic in English literature (Anonymous author, Anglo-Saxon epic, hero Beowulf slays monster Grendel).\n2. John Wycliffe: 'Morning Star of the Reformation'; translated the Bible from Latin Vulgate into English (1382).\n3. William Caxton: Set up the first English printing press at Westminster in 1476.\n4. William Langland: Author of 'Piers Plowman'.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who is the \"Father of English Modern Poetry\"?' — Answer: 'Geoffrey Chaucer'. [36th BCS]\n2. 'Who translated the Bible into English for the first time?' — Answer: 'John Wycliffe'. [37th BCS]\n3. 'The Canterbury Tales is written by:' — Answer: 'Geoffrey Chaucer'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember the distinction: John Wycliffe is the 'Morning Star of the Reformation', while Geoffrey Chaucer is the 'Morning Star of the Renaissance'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Who is considered the 'Father of English Poetry'?",
                        'options' => ['William Shakespeare', 'Geoffrey Chaucer', 'John Milton', 'Edmund Spenser'],
                        'correct_option' => 'খ',
                        'explanation' => "জিওফ্রে চসার (Geoffrey Chaucer)-কে 'Father of English Poetry' এবং 'Father of English Literature' বলা হয়। তিনি বিখ্যাত 'The Canterbury Tales' রচনা করেন।",
                        'source' => $src46,
                    ],
                ],
            ],

            // 27. The Renaissance & Elizabethan Age
            [
                'name' => 'The Renaissance & Elizabethan Age: Marlowe, Bacon, Spenser',
                'slug' => 'renaissance-and-elizabethan-age',
                'description' => 'Christopher Marlowe (Father of English Tragedy), Francis Bacon (Father of English Essay), Edmund Spenser, and the University Wits.',
                'is_high_yield' => true,
                'sort_order' => 3,
                'subtopics' => [
                    'Renaissance Meaning: \'Rebirth\' of classical learning (originated in Italy in 14th century)',
                    'Christopher Marlowe: Father of English Tragedy, University Wits, Dr. Faustus, The Jew of Malta',
                    'Francis Bacon: Father of English Essay, Aphoristic style, \'Of Studies\', \'Of Truth\'',
                    'Edmund Spenser: The Poets\' Poet, The Faerie Queene, Spenserian Stanza',
                    'Sir Thomas More: Utopia (1516), Humanism',
                    'Ben Jonson: Comedy of Humours, Volpone, The Alchemist'
                ],
                'guide' => [
                    'title' => 'The Elizabethan Age: Marlowe, Bacon & Spenser Guide',
                    'summary' => 'In-depth study of the University Wits, Marlowe\'s tragic heroes, Bacon\'s pragmatic essays, and Spenserian poetic genius.',
                    'sections' => [
                        [
                            'title' => 'Christopher Marlowe & The University Wits',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Christopher Marlowe (1564–1593) is the 'Father of English Tragedy' and popularized 'Blank Verse' (unrhymed iambic pentameter / 'Marlowe's Mighty Line').\n• Masterpieces: 'Doctor Faustus' (sells his soul to Lucifer for 24 years of absolute knowledge/power), 'The Jew of Malta' (Barabas), 'Tamburlaine the Great'.\n• The University Wits: A group of late 16th-century Cambridge/Oxford educated playwrights (Christopher Marlowe, Robert Greene, Thomas Nashe, Thomas Lodge, George Peele, John Lyly, Thomas Kyd).",
                        ],
                        [
                            'title' => 'Francis Bacon: Quotes & Essays',
                            'type' => SectionType::FORMULA,
                            'content' => "Francis Bacon (1561–1626) is the 'Father of English Essay' and 'Father of Modern Empiricism':\n• Famous Quotations:\n  - 'Reading maketh a full man; conference a ready man; and writing an exact man.' (Of Studies)\n  - 'Some books are to be tasted, others to be swallowed, and some few to be chewed and digested.' (Of Studies)\n  - 'Opportunity makes a thief.'\n  - 'A mixture of a lie doth ever add pleasure.' (Of Truth)",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who wrote the play \"Doctor Faustus\"?' — Answer: 'Christopher Marlowe'. [44th BCS]\n2. '\"Reading maketh a full man...\" was written by:' — Answer: 'Francis Bacon'. [41st BCS]\n3. 'Who is called the \"Poets\' Poet\"?' — Answer: 'Edmund Spenser' (called so by Charles Lamb). [37th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: Thomas Kyd wrote 'The Spanish Tragedy', the first great English revenge tragedy which heavily influenced Shakespeare's 'Hamlet'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Who wrote the famous philosophical tragedy 'Doctor Faustus'?",
                        'options' => ['William Shakespeare', 'Christopher Marlowe', 'Ben Jonson', 'John Webster'],
                        'correct_option' => 'খ',
                        'explanation' => "ক্রিস্টোফার মার্লো (Christopher Marlowe) ১৫৯২ সালে 'The Tragical History of Doctor Faustus' রচনা করেন, যেখানে ডক্টর ফস্টাস ২৪ বছরের শক্তির বিনিময়ে শয়তানের কাছে নিজের আত্মা বিক্রি করেন।",
                        'source' => $src45,
                    ],
                    [
                        'question' => "'Reading maketh a full man, conference a ready man, and writing an exact man' — who said this?",
                        'options' => ['Francis Bacon', 'William Shakespeare', 'John Milton', 'Alexander Pope'],
                        'correct_option' => 'ক',
                        'explanation' => "উক্তিটি আধুনিক ইংরেজি প্রবন্ধের জনক ফ্রান্সিস বেকন (Francis Bacon)-এর বিখ্যাত প্রবন্ধ 'Of Studies' থেকে সংকলিত।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 28. William Shakespeare: Master of English Drama
            [
                'name' => 'William Shakespeare: Tragedies, Comedies & Sonnets',
                'slug' => 'william-shakespeare-master-drama',
                'description' => 'The National Poet of England: 37 plays, 154 sonnets, the 4 Great Tragedies, iconic quotations, and complex characters.',
                'is_high_yield' => true,
                'sort_order' => 4,
                'subtopics' => [
                    'Life & Career (1564–1616, Stratford-upon-Avon, The Globe Theatre)',
                    'The 4 Great Tragedies: Hamlet, Othello, King Lear, Macbeth',
                    'Major Romantic Comedies: As You Like It, Twelfth Night, The Merchant of Venice',
                    'Roman Plays: Julius Caesar, Antony and Cleopatra, Coriolanus',
                    'Late Romances / Tragicomedies: The Tempest, The Winter\'s Tale',
                    'Shakespearean Sonnet structure (3 quatrains + 1 rhyming couplet: abab cdcd efef gg)',
                    'Memorable Characters: Hamlet, Iago, Shylock, Lady Macbeth, Falstaff'
                ],
                'guide' => [
                    'title' => 'William Shakespeare: Master Encyclopedia for BCS & Competitive Exams',
                    'summary' => 'Comprehensive digest of Shakespeare\'s 37 plays, tragic flaws, memorable dialogues, soliloquies, and character analysis.',
                    'sections' => [
                        [
                            'title' => 'The 4 Great Tragedies & Tragic Flaws (Hamartia)',
                            'type' => SectionType::FORMULA,
                            'content' => "| Tragedy | Protagonist | Tragic Flaw (Hamartia) | Key Antagonist / Supporting |\n|---|---|---|---|\n| Hamlet | Prince Hamlet of Denmark | Indecision / Procrastination | King Claudius, Polonius, Ophelia, Gertrude |\n| Othello | The Moor of Venice | Sexual Jealousy / Credulity | Iago (arch villain), Desdemona, Cassio |\n| King Lear | King of Britain | Blind Vanity / Arrogance | Goneril, Regan (flattering daughters), Cordelia (truthful) |\n| Macbeth | Thane of Glamis/Cawdor | Vaulting Ambition | Lady Macbeth, Three Witches, Macduff, Banquo |",
                        ],
                        [
                            'title' => 'Top 10 Famous Shakespearean Quotations',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. 'To be, or not to be, that is the question.' — Hamlet (Act III, Sc 1)\n2. 'Frailty, thy name is woman!' — Hamlet (Act I, Sc 2)\n3. 'Neither a borrower nor a lender be.' — Polonius in Hamlet\n4. 'Cowards die many times before their deaths; The valiant never taste of death but once.' — Julius Caesar\n5. 'Fair is foul, and foul is fair.' — The Three Witches in Macbeth\n6. 'Life's but a walking shadow, a poor player...' — Macbeth\n7. 'All that glitters is not gold.' — The Merchant of Venice\n8. 'The quality of mercy is not strained...' — Portia in The Merchant of Venice\n9. 'All the world's a stage, and all the men and women merely players.' — Jacques in As You Like It\n10. 'Sweet are the uses of adversity.' — Duke Senior in As You Like It",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. '\"Frailty, thy name is woman!\" is a famous line from:' — Answer: 'Hamlet'. [44th BCS]\n2. 'Who is the villain in Shakespeare\'s \"Othello\"?' — Answer: 'Iago'. [40th BCS]\n3. '\"All the world\'s a stage...\" occurs in:' — Answer: 'As You Like It'. [38th BCS]\n4. 'Shakespeare\'s final play is generally considered to be:' — Answer: 'The Tempest'. [37th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ The 'Bard of Avon' wrote 37 plays and 154 sonnets. Sonnets 1–126 are addressed to a fair young man (Mr. W.H.), and 127–152 are addressed to the mysterious 'Dark Lady'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Which of the following is considered Shakespeare's longest play and greatest tragedy of indecision?",
                        'options' => ['Macbeth', 'King Lear', 'Hamlet', 'Othello'],
                        'correct_option' => 'গ',
                        'explanation' => "'Hamlet' শেক্সপিয়রের সর্ববৃহৎ নাটক। ডেনমার্কের যুবরাজ হ্যামলেটের প্রধান ট্র্যাজিক ত্রুটি (Hamartia) ছিল সিদ্ধান্তহীনতা বা কালক্ষেপণ (Procrastination)।",
                        'source' => $src46,
                    ],
                    [
                        'question' => "'Frailty, thy name is woman!' — this famous line occurs in which play?",
                        'options' => ['Othello', 'Hamlet', 'Macbeth', 'Romeo and Juliet'],
                        'correct_option' => 'খ',
                        'explanation' => "উক্তিটি শেক্সপিয়রের 'Hamlet' নাটকের ১ম অঙ্কের ২য় দৃশ্যে যুবরাজ হ্যামলেট তার মা গার্ট্রুডের দ্রুত পুনর্বিবাহ দেখে হতাশা প্রকাশ করে বলেছিলেন।",
                        'source' => $src44,
                    ],
                ],
            ],

            // 29. The Jacobean, Caroline & Commonwealth Age
            [
                'name' => 'The Jacobean & Commonwealth Age: Milton & Metaphysical Poets',
                'slug' => 'jacobean-caroline-commonwealth',
                'description' => 'John Milton (Paradise Lost), Metaphysical poets (John Donne), Cavalier poets, and the King James Authorized Bible (1611).',
                'is_high_yield' => true,
                'sort_order' => 5,
                'subtopics' => [
                    'King James Bible (Authorized Version, 1611)',
                    'John Milton: Epic Poet, Blind Poet, Paradise Lost, Paradise Regained, Areopagitica, Lycidas',
                    'Metaphysical Poets: John Donne (\'Poet of Love\'), George Herbert, Andrew Marvell',
                    'Metaphysical Conceit (far-fetched unexpected comparisons)',
                    'Cavalier Poets: Robert Herrick (\'To Daffodils\'), Richard Lovelace'
                ],
                'guide' => [
                    'title' => 'Milton & The Metaphysical Poets Study Guide',
                    'summary' => 'Paradise Lost thematic breakdown, Milton\'s defense of free press in Areopagitica, and John Donne\'s metaphysical conceits.',
                    'sections' => [
                        [
                            'title' => 'John Milton (1608–1674): Works & Significance',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Milton is the greatest epic poet of English literature:\n• 'Paradise Lost' (1667): Epic poem in 12 books written in blank verse. Subject: The fall of man (Adam and Eve's disobedience) and Satan's rebellion. Stated purpose: 'To justify the ways of God to men.'\n• 'Areopagitica' (1644): Famous prose tract passionately advocating freedom of speech and freedom of the press.\n• 'Lycidas' (1637): Pastoral elegy mourning the drowning of his friend Edward King.\n• 'On His Blindness': Famous sonnet ('They also serve who only stand and wait').",
                        ],
                        [
                            'title' => 'Metaphysical Poetry & John Donne',
                            'type' => SectionType::FORMULA,
                            'content' => "John Donne (1572–1631) is the leader of the Metaphysical Poets (term coined by Samuel Johnson):\n• Famous Works: 'The Good-Morrow', 'The Sun Rising', 'The Canonization', 'A Valediction: Forbidding Mourning' (compass conceit).\n• Famous Quotation: 'No man is an island, entire of itself.'\n• Robert Herrick's famous lyric: 'To Daffodils' ('Fair Daffodils, we weep to see / You haste away so soon').",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who wrote the epic \"Paradise Lost\"?' — Answer: 'John Milton'. [42nd BCS]\n2. 'Who is known as the \"Poet of Love\" in metaphysical poetry?' — Answer: 'John Donne'. [37th BCS]\n3. '\"To Daffodils\" is a poem written by:' — Answer: 'Robert Herrick'. [36th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: William Wordsworth wrote 'I Wandered Lonely as a Cloud' (popularly known as 'The Daffodils'), while Robert Herrick wrote 'To Daffodils'. Do not confuse the two!",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "What is the stated purpose of John Milton in writing 'Paradise Lost'?",
                        'options' => ['To praise Satan', 'To justify the ways of God to men', 'To explain human evolution', 'To entertain readers'],
                        'correct_option' => 'খ',
                        'explanation' => "মহাকবি জন মিল্টন তাঁর অমর মহাকাব্য 'Paradise Lost' (১৬৬৭)-এর প্রারম্ভেই ঘোষণা করেছিলেন তাঁর উদ্দেশ্য হলো: 'To justify the ways of God to men' (মানুষের কাছে ঈশ্বরের বিধানকে ন্যায়সঙ্গত প্রমাণ করা)।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 30. The Neoclassical Period
            [
                'name' => 'The Neoclassical Period: Pope, Swift & Defoe',
                'slug' => 'neoclassical-period-restoration-augustan',
                'description' => 'John Dryden (Father of Modern English Criticism), Alexander Pope (The Rape of the Lock), Jonathan Swift (Gulliver\'s Travels), and Daniel Defoe.',
                'is_high_yield' => false,
                'sort_order' => 6,
                'subtopics' => [
                    'Restoration Age (1660–1700): John Dryden (First official Poet Laureate)',
                    'Augustan Age / Age of Pope (1700–1745): Satire, Heroic Couplet, Mock-Epic',
                    'Alexander Pope: The Rape of the Lock, An Essay on Criticism, An Essay on Man',
                    'Jonathan Swift: Greatest satirist in English prose, Gulliver\'s Travels, A Modest Proposal',
                    'Daniel Defoe: Robinson Crusoe (Father of English Novel)',
                    'Samuel Johnson: First comprehensive Dictionary of the English Language (1755)'
                ],
                'guide' => [
                    'title' => 'The Neoclassical Period: Satire & Enlightenment Guide',
                    'summary' => 'Detailed study of 18th-century rationalism, Pope\'s heroic couplets, Swift\'s biting satires, and early English novels.',
                    'sections' => [
                        [
                            'title' => 'Alexander Pope: Quotations & Mock-Epic',
                            'type' => SectionType::FORMULA,
                            'content' => "Alexander Pope (1688–1744) perfected the Heroic Couplet:\n• 'The Rape of the Lock': Greatest mock-heroic epic in English (heroine Belinda's lock of hair is snipped by Lord Petre).\n• Famous Quotations from 'An Essay on Criticism':\n  - 'A little learning is a dangerous thing.'\n  - 'To err is human; to forgive, divine.'\n  - 'Fools rush in where angels fear to tread.'",
                        ],
                        [
                            'title' => 'Jonathan Swift & Daniel Defoe',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. Jonathan Swift (1667–1745): Greatest prose satirist:\n   • 'Gulliver's Travels' (1726): 4 voyages (Lilliput: tiny people 6 inches high; Brobdingnag: giants; Laputa: flying island; Houyhnhnms: rational horses & Yahoos: brutish humans).\n2. Daniel Defoe (1660–1731):\n   • 'Robinson Crusoe' (1719): Early masterpiece of the realistic English novel based on Alexander Selkirk's shipwreck.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. '\"A little learning is a dangerous thing\" is a quotation from:' — Answer: 'Alexander Pope'. [38th BCS]\n2. 'Who wrote \"Gulliver\'s Travels\"?' — Answer: 'Jonathan Swift'. [36th BCS]\n3. 'Who wrote the novel \"Robinson Crusoe\"?' — Answer: 'Daniel Defoe'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Samuel Johnson compiled 'A Dictionary of the English Language' (1755). James Boswell wrote the most celebrated biography in English: 'The Life of Samuel Johnson'.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Who wrote the satirical masterpiece 'Gulliver\\'s Travels'?",
                        'options' => ['Daniel Defoe', 'Jonathan Swift', 'Alexander Pope', 'Samuel Johnson'],
                        'correct_option' => 'খ',
                        'explanation' => "'Gulliver\\'s Travels' (১৭২৬) ইংরেজি সাহিত্যের সর্বশ্রেষ্ঠ ব্যঙ্গাত্মক গদ্য রচনা যা জোনাথন সুইফট (Jonathan Swift) রচনা করেন।",
                        'source' => $src43,
                    ],
                ],
            ],

            // 31. The Romantic Period
            [
                'name' => 'The Romantic Period: Wordsworth, Coleridge, Keats, Shelley, Byron',
                'slug' => 'romantic-period-poets-of-nature',
                'description' => '1798–1837: Wordsworth (Poet of Nature), Coleridge (Supernaturalism), Keats (Poet of Beauty), Shelley (Revolution), Byron, and Jane Austen.',
                'is_high_yield' => true,
                'sort_order' => 7,
                'subtopics' => [
                    'Publication of Lyrical Ballads (1798) and Preface to Lyrical Ballads (1800)',
                    'William Wordsworth: Poet of Nature, Poet Laureate, The Prelude, Tintern Abbey, Daffodils, The Solitary Reaper',
                    'Samuel Taylor Coleridge: Poet of Supernaturalism, The Rime of the Ancient Mariner, Kubla Khan, Biographia Literaria',
                    'John Keats: Poet of Beauty, Sensuousness, Negative Capability, Ode to a Nightingale, Ode on a Grecian Urn',
                    'Percy Bysshe Shelley: Revolutionary Poet, Ode to the West Wind, To a Skylark, Prometheus Unbound, Adonais',
                    'Lord Byron: Rebel Poet, Byronic Hero, Don Juan, Childe Harold\'s Pilgrimage',
                    'Jane Austen: Pride and Prejudice, Sense and Sensibility, Emma'
                ],
                'guide' => [
                    'title' => 'The Romantic Period: Nature, Beauty & Imagination Guide',
                    'summary' => 'Comprehensive study of Wordsworth, Coleridge, Keats, Shelley, Byron, landmark Romantic poems, and timeless lines.',
                    'sections' => [
                        [
                            'title' => 'The Major Romantic Poets & Their Titles',
                            'type' => SectionType::FORMULA,
                            'content' => "| Poet | Special Epithet / Title | Masterpieces | Famous Quote |\n|---|---|---|---|\n| William Wordsworth | Poet of Nature / Lake Poet | Daffodils, Tintern Abbey, The Solitary Reaper | 'Nature never did betray the heart that loved her.' |\n| S.T. Coleridge | Poet of Supernaturalism / Opium Eater | The Rime of the Ancient Mariner, Kubla Khan | 'Water, water, everywhere, nor any drop to drink.' |\n| John Keats | Poet of Beauty / Sensuousness | Ode to a Nightingale, Ode on a Grecian Urn | 'Beauty is truth, truth beauty.' / 'A thing of beauty is a joy forever.' |\n| P.B. Shelley | Revolutionary Poet / Poet of Hope | Ode to the West Wind, To a Skylark, Adonais | 'If Winter comes, can Spring be far behind?' |\n| Lord Byron | Rebel Poet | Don Juan, She Walks in Beauty | 'She walks in beauty, like the night...' |",
                        ],
                        [
                            'title' => 'Landmark Romantic Poems & Themes',
                            'type' => SectionType::EXPLANATION,
                            'content' => "1. 'The Rime of the Ancient Mariner' (Coleridge): An old mariner shoots an innocent Albatross with his crossbow, bringing a curse on his ship and crew.\n2. 'Daffodils' / 'I Wandered Lonely as a Cloud' (Wordsworth): Describes a host of golden daffodils beside the lake, experiencing 'emotion recollected in tranquillity'.\n3. 'Adonais' (Shelley): A pastoral elegy written on the death of his contemporary poet John Keats.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. '\"If Winter comes, can Spring be far behind?\" was written by:' — Answer: 'P.B. Shelley'. [44th BCS]\n2. '\"A thing of beauty is a joy forever\" is by:' — Answer: 'John Keats' (from Endymion). [41st BCS]\n3. '\"Water, water, everywhere, / Nor any drop to drink\" is from:' — Answer: 'The Rime of the Ancient Mariner' by Coleridge. [38th BCS]\n4. 'Who wrote \"The Solitary Reaper\"?' — Answer: 'William Wordsworth'. [37th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ The Lake Poets: William Wordsworth, Samuel Taylor Coleridge, and Robert Southey (all lived in the Lake District of England).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "'If Winter comes, can Spring be far behind?' — who wrote this famous line?",
                        'options' => ['William Wordsworth', 'P.B. Shelley', 'John Keats', 'Lord Byron'],
                        'correct_option' => 'খ',
                        'explanation' => "পার্সি বিশি শেলি (P.B. Shelley)-র অমর আশাবাদী কবিতা 'Ode to the West Wind' (১৮১৯)-এর শেষ পংক্তি এটি, যা আশাবাদ ও রূপান্তরের চিরন্তন বাণী।",
                        'source' => $src44,
                    ],
                    [
                        'question' => "'A thing of beauty is a joy forever' is a famous line written by which Romantic poet?",
                        'options' => ['John Keats', 'William Blake', 'Lord Byron', 'S.T. Coleridge'],
                        'correct_option' => 'ক',
                        'explanation' => "জন কিটস (John Keats)-এর বিখ্যাত কবিতা 'Endymion' (১৮১৮)-এর প্রথম পংক্তি 'A thing of beauty is a joy forever'।",
                        'source' => $src41,
                    ],
                ],
            ],

            // 32. The Victorian Period
            [
                'name' => 'The Victorian Period: Tennyson, Browning, Dickens, Hardy',
                'slug' => 'victorian-period-realism-novelists',
                'description' => '1837–1901: Tennyson (Poet Laureate), Robert Browning (Dramatic Monologue), Charles Dickens, Thomas Hardy, and the Brontë sisters.',
                'is_high_yield' => true,
                'sort_order' => 8,
                'subtopics' => [
                    'Alfred Lord Tennyson: Poet Laureate, In Memoriam, Ulysses, Locksley Hall, The Charge of the Light Brigade',
                    'Robert Browning: Master of Dramatic Monologue, My Last Duchess, Andrea del Sarto, The Last Ride Together',
                    'Charles Dickens: Greatest Victorian Novelist, David Copperfield, A Tale of Two Cities, Great Expectations, Oliver Twist',
                    'Thomas Hardy: Wessex Novels, Pessimistic Realism, Tess of the d\'Urbervilles, The Mayor of Casterbridge',
                    'The Brontë Sisters: Charlotte Brontë (Jane Eyre), Emily Brontë (Wuthering Heights)',
                    'George Eliot (Mary Ann Evans): Middlemarch, Silas Marner'
                ],
                'guide' => [
                    'title' => 'The Victorian Period: Industrialization, Realism & Novels',
                    'summary' => 'Comprehensive guide to Victorian compromise, Tennyson\'s lyrics, Browning\'s dramatic monologues, and Charles Dickens\'s serial novels.',
                    'sections' => [
                        [
                            'title' => 'Key Victorian Giants & Their Landmark Works',
                            'type' => SectionType::FORMULA,
                            'content' => "| Author / Poet | Genre | Masterpieces | Signature Concept / Quote |\n|---|---|---|---|\n| Alfred Lord Tennyson | Poetry | Ulysses, In Memoriam A.H.H. | 'To strive, to seek, to find, and not to yield.' |\n| Robert Browning | Dramatic Monologue | My Last Duchess, The Last Ride Together | Optimism: 'God's in His heaven—All's right with the world!' |\n| Charles Dickens | Realistic Novel | David Copperfield, A Tale of Two Cities | 'It was the best of times, it was the worst of times...' |\n| Thomas Hardy | Tragic / Regional Novel | Tess of the d'Urbervilles, Mayor of Casterbridge | Wessex setting, Cruel fate and circumstance |\n| Emily Brontë | Gothic Romance | Wuthering Heights | Heathcliff and Catherine |\n| Charlotte Brontë | Bildungsroman | Jane Eyre | Independent female heroine |",
                        ],
                        [
                            'title' => 'A Tale of Two Cities (Charles Dickens)',
                            'type' => SectionType::EXPLANATION,
                            'content' => "Set during the French Revolution in London and Paris:\n• Iconic Opening Line: 'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness...'\n• Central sacrifice: Sydney Carton takes the place of Charles Darnay at the guillotine out of love for Lucie Manette ('It is a far, far better thing that I do, than I have ever done').",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who wrote the novel \"A Tale of Two Cities\"?' — Answer: 'Charles Dickens'. [44th BCS]\n2. '\"To strive, to seek, to find, and not to yield\" is from:' — Answer: 'Ulysses' by Tennyson. [40th BCS]\n3. 'Who wrote \"Wuthering Heights\"?' — Answer: 'Emily Brontë'. [37th BCS]\n4. 'Who wrote the novel \"Tess of the d\'Urbervilles\"?' — Answer: 'Thomas Hardy'.",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Robert Browning was famous for the 'Dramatic Monologue' (a poem in which a single speaker addresses a silent listener at a critical moment).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "The famous historical novel 'A Tale of Two Cities' was written by which Victorian novelist?",
                        'options' => ['Thomas Hardy', 'Charles Dickens', 'William Makepeace Thackeray', 'George Eliot'],
                        'correct_option' => 'খ',
                        'explanation' => "চার্লস ডিকেন্স (Charles Dickens) ১৮৫৯ সালে ফরাসি বিপ্লবের পটভূমিতে লন্ডন ও প্যারিস—এই দুই শহরকে কেন্দ্র করে 'A Tale of Two Cities' উপন্যাসটি রচনা করেন।",
                        'source' => $src44,
                    ],
                    [
                        'question' => "'To strive, to seek, to find, and not to yield' — this inspiring line concludes which poem?",
                        'options' => ['Locksley Hall', 'In Memoriam', 'Ulysses', 'The Charge of the Light Brigade'],
                        'correct_option' => 'গ',
                        'explanation' => "উক্তিটি লর্ড আলফ্রেড টেনিসন (Alfred Lord Tennyson)-এর বিখ্যাত কবিতা 'Ulysses' (১৮৩৩)-এর অন্তিম ও সবচেয়ে অমর পংক্তি।",
                        'source' => $src40,
                    ],
                ],
            ],

            // 33. The Modern & Post-Modern Literature
            [
                'name' => 'Modern & Post-Modern Literature: Yeats, Eliot, Shaw, Orwell',
                'slug' => 'modern-and-postmodern-literature',
                'description' => '20th-century literature: W.B. Yeats, T.S. Eliot (The Waste Land), G.B. Shaw (Modern Drama), George Orwell, and Ernest Hemingway.',
                'is_high_yield' => true,
                'sort_order' => 9,
                'subtopics' => [
                    'W.B. Yeats: Irish Poet, Nobel Prize 1923, The Second Coming, Sailing to Byzantium',
                    'T.S. Eliot: High Modernist, Nobel Prize 1948, The Waste Land, The Love Song of J. Alfred Prufrock',
                    'George Bernard Shaw: Father of Modern Drama, Arms and the Man, Pygmalion, Man and Superman',
                    'George Orwell: Dystopian Fiction, 1984, Animal Farm (\'All animals are equal, but some are more equal\')',
                    'Ernest Hemingway: Iceberg Theory, Nobel Prize 1954, The Old Man and the Sea',
                    'Samuel Beckett: Theatre of the Absurd, Waiting for Godot'
                ],
                'guide' => [
                    'title' => '20th Century Modern & Post-Modern Literature Guide',
                    'summary' => 'Comprehensive coverage of modern poetry, stream-of-consciousness novels, anti-romantic drama, and iconic 20th-century authors.',
                    'sections' => [
                        [
                            'title' => 'T.S. Eliot: The Waste Land (1922)',
                            'type' => SectionType::EXPLANATION,
                            'content' => "T.S. Eliot (1888–1965) published 'The Waste Land' in 1922 (edited by Ezra Pound):\n• Considered the most important poem of the 20th century depicting post-WWI spiritual and cultural sterility.\n• Famous opening line: 'April is the cruellest month...'\n• Consists of 5 sections: 1. The Burial of the Dead, 2. A Game of Chess, 3. The Fire Sermon, 4. Death by Water, 5. What the Thunder Said (ends with Sanskrit chant 'Shantih shantih shantih').",
                        ],
                        [
                            'title' => 'Major 20th Century Literary Landmarks',
                            'type' => SectionType::FORMULA,
                            'content' => "| Author | Work | Genre / Theme |\n|---|---|---|\n| George Bernard Shaw | Arms and the Man | Anti-romantic comedy on war and heroism (Bluntschli, Raina) |\n| George Bernard Shaw | Pygmalion | Social phonetics comedy (Professor Higgins, Eliza Doolittle) |\n| George Orwell | Animal Farm | Political satire on totalitarian communism (Napoleon, Snowball) |\n| George Orwell | 1984 | Dystopian novel of totalitarian surveillance ('Big Brother is watching you') |\n| Ernest Hemingway | The Old Man and the Sea | Santiago's struggle with the giant marlin ('A man can be destroyed but not defeated') |\n| Samuel Beckett | Waiting for Godot | Absurdist play where Vladimir and Estragon wait for Godot who never arrives |",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who wrote the poem \"The Waste Land\"?' — Answer: 'T.S. Eliot'. [43rd BCS]\n2. 'Who wrote the play \"Arms and the Man\"?' — Answer: 'George Bernard Shaw'. [41st BCS]\n3. '\"A man can be destroyed but not defeated\" is from:' — Answer: 'The Old Man and the Sea' by Ernest Hemingway. [36th BCS]\n4. 'Who wrote \"Animal Farm\"?' — Answer: 'George Orwell'. [38th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ George Bernard Shaw is the ONLY person to win both the Nobel Prize in Literature (1925) and an Academy Award / Oscar (1938 for screenplay of Pygmalion).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Who wrote the famous modernist poem 'The Waste Land'?",
                        'options' => ['W.B. Yeats', 'T.S. Eliot', 'Ezra Pound', 'W.H. Auden'],
                        'correct_option' => 'খ',
                        'explanation' => "থমাস স্টার্নস এলিয়ট (T.S. Eliot) ১৯২২ সালে প্রথম বিশ্বযুদ্ধোত্তর পশ্চিমা সমাজের অবক্ষয় ও শূন্যতা নিয়ে তাঁর কালজয়ী দীর্ঘ কবিতা 'The Waste Land' প্রকাশ করেন।",
                        'source' => $src43,
                    ],
                    [
                        'question' => "'A man can be destroyed, but not defeated' — is a famous quote from which novel?",
                        'options' => ['A Farewell to Arms', 'The Old Man and the Sea', 'For Whom the Bell Tolls', 'The Sun Also Rises'],
                        'correct_option' => 'খ',
                        'explanation' => "আর্নেস্ট হেমিংওয়ে (Ernest Hemingway)-র নোবেলজয়ী উপন্যাস 'The Old Man and the Sea' (১৯৫২)-তে বৃদ্ধ কিউবান জেলে সান্তিয়াগোর মুখ দিয়ে এই অমর বাক্যটি উচ্চারিত হয়।",
                        'source' => $src36 ?? $src40,
                    ],
                ],
            ],

            // 34. Literary Terms, Figures of Speech & Poetic Forms
            [
                'name' => 'Literary Terms, Figures of Speech & Poetic Forms',
                'slug' => 'literary-terms-figures-of-speech',
                'description' => 'Simile, Metaphor, Personification, Hyperbole, Irony, Oxymoron, Soliloquy, Sonnet, Elegy, Ode, Ballad, and Epic forms.',
                'is_high_yield' => true,
                'sort_order' => 10,
                'subtopics' => [
                    'Figures of Speech based on Similarity: Simile (with as/like) vs Metaphor (implicit comparison)',
                    'Figures based on Contrast: Oxymoron (opposites paired), Paradox, Antithesis, Irony',
                    'Figures based on Association: Metonymy, Synecdoche, Personification, Apostrophe',
                    'Poetic Forms: Sonnet (14 lines), Elegy (mourning poem), Ode (lofty address), Ballad (story in song)',
                    'Dramatic Devices: Soliloquy (thinking aloud alone), Aside, Climax, Catharsis, Hamartia'
                ],
                'guide' => [
                    'title' => 'Literary Terms & Figures of Speech Definitive Guide',
                    'summary' => 'Clear definitions and examples of literary devices, figures of speech, and poetic forms frequently tested in BCS Preliminary.',
                    'sections' => [
                        [
                            'title' => 'Figures of Speech Essential Matrix',
                            'type' => SectionType::FORMULA,
                            'content' => "| Literary Device | Definition | Example |\n|---|---|---|\n| Simile | Explicit comparison using 'like' or 'as' | 'I wandered lonely AS a cloud.' |\n| Metaphor | Direct implied comparison without 'as/like' | 'Life is a broken-winged bird.' / 'Camel is the ship of the desert.' |\n| Personification | Giving human qualities to non-human things | 'The wind whispered through the trees.' |\n| Hyperbole | Deliberate extreme exaggeration for effect | 'All the perfumes of Arabia will not sweeten this little hand.' |\n| Oxymoron | Two contradictory words placed side by side | 'Sweet sorrow', 'Deafening silence', 'Open secret' |\n| Irony | Expression where meaning is opposite to literal words | Saying 'What lovely weather!' during a thunderstorm. |\n| Alliteration | Repetition of consonant sounds at word starts | 'Peter Piper picked a peck of pickled peppers.' |",
                        ],
                        [
                            'title' => 'Poetic & Dramatic Forms',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Sonnet: A 14-line poem in iambic pentameter with a strict rhyme scheme (Petrarchan: octave + sestet; Shakespearean: 3 quatrains + 1 rhyming couplet).\n• Elegy: A sorrowful poem lamenting the death of a beloved person (e.g. Milton's 'Lycidas', Shelley's 'Adonais', Gray's 'Elegy Written in a Country Churchyard').\n• Soliloquy: An act of speaking one's innermost thoughts aloud when alone on stage, revealing inner psychological conflict (e.g. Hamlet's 'To be or not to be').",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'A poem of fourteen lines is called:' — Answer: 'Sonnet'. [38th BCS]\n2. '\"All the perfumes of Arabia will not sweeten this little hand\" is an example of:' — Answer: 'Hyperbole'. [36th BCS]\n3. 'A speech by an actor alone on stage is called a:' — Answer: 'Soliloquy'. [37th BCS]\n4. 'An elegy is a poem written to mourn:' — Answer: 'Death of someone'. [41st BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Remember: Soliloquy is spoken ALONE on stage; Monologue can be addressed to a silent audience or other characters on stage.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "What is the literary term for a fourteen-line poem written in iambic pentameter?",
                        'options' => ['Elegy', 'Ode', 'Sonnet', 'Ballad'],
                        'correct_option' => 'গ',
                        'explanation' => "চৌদ্দ চরণের এবং নির্দিষ্ট অন্ত্যমিলবিশিষ্ট কবিতাকে 'Sonnet' (সনেট) বলা হয়। পেত্রার্কীয় ও শেক্সপিয়রীয়—এই দুই ধারা সর্বাধিক পরিচিত।",
                        'source' => $src38,
                    ],
                    [
                        'question' => "'All the perfumes of Arabia will not sweeten this little hand' — what figure of speech is this?",
                        'options' => ['Simile', 'Metaphor', 'Hyperbole', 'Oxymoron'],
                        'correct_option' => 'গ',
                        'explanation' => "নাটক 'Macbeth'-এ লেডি ম্যাকবেথের এই সংলাপে চরম ভাবাবেগ প্রকাশের জন্য অসম্ভব রকমের অতিরঞ্জন (Extreme Exaggeration) করা হয়েছে, যা অলঙ্কারশাস্ত্রে 'Hyperbole' (অতিশয়োক্তি) নামে পরিচিত।",
                        'source' => $src45,
                    ],
                ],
            ],

            // 35. Famous Quotations & Characters
            [
                'name' => 'Famous Quotations & Memorable Literary Characters',
                'slug' => 'famous-quotations-and-characters',
                'description' => 'Top 50 most frequently tested literary quotes, memorable lines from English classics, and iconic characters.',
                'is_high_yield' => true,
                'sort_order' => 11,
                'subtopics' => [
                    'Top 50 Quotations in BCS Preliminary Examinations',
                    'Who Said to Whom in Shakespearean Classics',
                    'Iconic Characters: Hamlet, Shylock, Iago, Heathcliff, Santiago, Sydney Carton',
                    'Famous Epics & Dramas Reference Index'
                ],
                'guide' => [
                    'title' => 'Famous Quotations & Characters Reference Index',
                    'summary' => 'Comprehensive index of famous lines, original sources, authors, and iconic literary characters for BCS English.',
                    'sections' => [
                        [
                            'title' => 'Top 20 BCS Literature Quotations Index',
                            'type' => SectionType::FORMULA,
                            'content' => "| Quotation | Source Work | Author |\n|---|---|---|\n| 'To be, or not to be, that is the question.' | Hamlet | William Shakespeare |\n| 'Frailty, thy name is woman!' | Hamlet | William Shakespeare |\n| 'Cowards die many times before their deaths...' | Julius Caesar | William Shakespeare |\n| 'All that glitters is not gold.' | The Merchant of Venice | William Shakespeare |\n| 'Water, water, everywhere, nor any drop to drink.' | The Rime of the Ancient Mariner | S.T. Coleridge |\n| 'A thing of beauty is a joy forever.' | Endymion | John Keats |\n| 'Beauty is truth, truth beauty.' | Ode on a Grecian Urn | John Keats |\n| 'If Winter comes, can Spring be far behind?' | Ode to the West Wind | P.B. Shelley |\n| 'To strive, to seek, to find, and not to yield.' | Ulysses | Alfred Lord Tennyson |\n| 'It was the best of times, it was the worst of times...' | A Tale of Two Cities | Charles Dickens |",
                        ],
                        [
                            'title' => 'Memorable Literary Characters & Creators',
                            'type' => SectionType::EXPLANATION,
                            'content' => "• Shylock (Vengeful Jewish moneylender who demands a pound of flesh) ➔ The Merchant of Venice (Shakespeare)\n• Iago (The Machiavellian villain who destroys Othello with jealousy) ➔ Othello (Shakespeare)\n• Sydney Carton (The heroic lover who sacrifices his life at the guillotine) ➔ A Tale of Two Cities (Dickens)\n• Heathcliff (The brooding, passionate anti-hero) ➔ Wuthering Heights (Emily Brontë)\n• Santiago (The courageous old fisherman) ➔ The Old Man and the Sea (Hemingway)\n• Captain Ahab (The monomaniacal captain hunting Moby Dick) ➔ Moby-Dick (Herman Melville)",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Shylock is a character in the play:' — Answer: 'The Merchant of Venice'. [41st BCS]\n2. '\"Frailty, thy name is woman!\" is from:' — Answer: 'Hamlet'. [44th BCS]\n3. '\"Cowards die many times before their deaths\" is by:' — Answer: 'Shakespeare' (in Julius Caesar).",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Questions asking 'Who is the author of [Quote]?' usually come from Shakespeare (about 50%), Romantic poets (30%), or Victorian/Modern classics (20%).",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "In which of Shakespeare's plays does the character 'Shylock' appear?",
                        'options' => ['As You Like It', 'The Merchant of Venice', 'Twelfth Night', 'The Tempest'],
                        'correct_option' => 'খ',
                        'explanation' => "শাইলক (Shylock) উইলিয়াম শেক্সপিয়রের বিখ্যাত নাটক 'The Merchant of Venice'-এর অন্যতম প্রধান চরিত্র, যিনি এক পাউন্ড মাংস দাবি করে চুক্তিবদ্ধ হয়েছিলেন।",
                        'source' => $src41,
                    ],
                    [
                        'question' => "'Cowards die many times before their deaths; The valiant never taste of death but once' — who said this in Julius Caesar?",
                        'options' => ['Brutus', 'Mark Antony', 'Julius Caesar', 'Cassius'],
                        'correct_option' => 'গ',
                        'explanation' => "শেক্সপিয়রের ঐতিহাসিক ট্র্যাজেডি 'Julius Caesar'-এ জুলিয়াস সিজার তাঁর স্ত্রী ক্যালপার্নিয়ার আশঙ্কার জবাবে এই অবিস্মরণীয় উক্তিটি করেছিলেন।",
                        'source' => $src46,
                    ],
                ],
            ],

            // 36. Nobel Prize in Literature & Revision
            [
                'name' => 'Nobel Laureates in Literature & Competitive Exam Review',
                'slug' => 'nobel-prize-in-literature-revision',
                'description' => 'Important Nobel Prize winners in literature, first Asian laureate, recent winners, and master revision strategy for BCS English.',
                'is_high_yield' => false,
                'sort_order' => 12,
                'subtopics' => [
                    'First Nobel Laureate in Literature (Sully Prudhomme, 1901)',
                    'Rabindranath Tagore: First Asian & Non-European Laureate (1913, Song Offerings / Gitanjali)',
                    'W.B. Yeats (1923), G.B. Shaw (1925), T.S. Eliot (1948), William Faulkner (1949), Ernest Hemingway (1954)',
                    'Winston Churchill: Nobel in Literature (1953) for biographical and historical writing and brilliant oratory',
                    'Recent Nobel Laureates: Bob Dylan (2016), Abdulrazak Gurnah (2021), Annie Ernaux (2022), Jon Fosse (2023), Han Kang (2024)',
                    'BCS 35-Mark English Preliminary Strategy & Checklist'
                ],
                'guide' => [
                    'title' => 'Nobel Laureates in Literature & BCS Final Strategy',
                    'summary' => 'Comprehensive list of frequently tested Nobel laureates in literature and strategic revision guide for BCS and Bank English.',
                    'sections' => [
                        [
                            'title' => 'Essential Nobel Laureates Table for BCS',
                            'type' => SectionType::FORMULA,
                            'content' => "| Year | Laureate | Country | Landmark Reason / Works |\n|---|---|---|---|\n| 1901 | Sully Prudhomme | France | First recipient of Nobel Prize in Literature |\n| 1907 | Rudyard Kipling | UK | The Jungle Book (Youngest recipient at age 41) |\n| 1913 | Rabindranath Tagore | India/Bengal | Gitanjali (Song Offerings) - First Asian laureate |\n| 1923 | W.B. Yeats | Ireland | Lyric poetry expressing the spirit of a nation |\n| 1925 | George Bernard Shaw | Ireland/UK | Drama and satire (Only person with Nobel + Oscar) |\n| 1948 | T.S. Eliot | UK/USA | Pioneer of modern poetry (The Waste Land) |\n| 1953 | Winston Churchill | UK | Mastery of historical description and brilliant oratory |\n| 1954 | Ernest Hemingway | USA | The Old Man and the Sea |\n| 1993 | Toni Morrison | USA | First African-American woman to win Nobel |\n| 2016 | Bob Dylan | USA | Poetic expressions within American song tradition |",
                        ],
                        [
                            'title' => 'BCS English 35-Mark Distribution Strategy',
                            'type' => SectionType::EXPLANATION,
                            'content' => "In BCS Preliminary:\n• English Language & Grammar: 20 Marks (Parts of Speech, Prepositions, Voice, Narration, Correction, Idioms, Synonyms/Antonyms).\n• English Literature: 15 Marks (Periods, Shakespeare [usually 2-3 questions], Romantic poets [2 questions], Victorian/Modern [3-4 questions], Quotes & Terms [3 questions]).\n\nHigh-Yield Checklist:\n1. Solve all 10th–46th BCS English questions.\n2. Master Shakespeare's 4 great tragedies and memorable quotes.\n3. Memorize 100 Appropriate Prepositions and 100 Idioms.\n4. Practice Subject-Verb Agreement and Right Form of Verbs daily.",
                        ],
                        [
                            'title' => 'Past BCS Questions Analysis',
                            'type' => SectionType::EXAMPLE,
                            'content' => "1. 'Who won the Nobel Prize in Literature for \"Gitanjali\" in 1913?' — Answer: 'Rabindranath Tagore'. [40th BCS]\n2. 'Which British Prime Minister won the Nobel Prize in Literature?' — Answer: 'Winston Churchill' (in 1953). [38th BCS]\n3. 'Bob Dylan won the Nobel Prize in Literature in:' — Answer: '2016'. [38th BCS]",
                        ],
                        [
                            'title' => 'Exam Tip',
                            'type' => SectionType::PRACTICE,
                            'content' => "★ Winston Churchill won the Nobel Prize in LITERATURE in 1953, NOT the Nobel Peace Prize! This is a very common trap question in BCS and job exams.",
                        ],
                    ],
                ],
                'questions' => [
                    [
                        'question' => "Which former British Prime Minister received the Nobel Prize in Literature in 1953?",
                        'options' => ['Margaret Thatcher', 'Winston Churchill', 'Tony Blair', 'Clement Attlee'],
                        'correct_option' => 'খ',
                        'explanation' => "উইন্সটন চার্চিল (Winston Churchill) ১৯৫৩ সালে তাঁর ঐতিহাসিক স্মৃতিচারণ ও তেজস্বী বক্তৃতার জন্য সাহিত্যে নোবেল পুরস্কার লাভ করেন (শান্তিতে নয়)।",
                        'source' => $src38,
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
