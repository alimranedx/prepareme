<?php

namespace Database\Seeders;

use App\Enums\ProgressStatus;
use App\Models\Bookmark;
use App\Models\PersonalQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PersonalNotebookSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'user1@prepareme.com')->first();
        $user2 = User::where('email', 'user2@prepareme.com')->first();

        if (! $user1 || ! $user2) return;

        $bangla = Subject::where('slug', 'bangla')->first();
        $sandhi = Topic::where('slug', 'sandhi')->first();
        $math = Subject::where('slug', 'mathematics')->first();
        $percentage = Topic::where('slug', 'percentage-and-profit-loss')->first();

        $guides = StudyGuide::published()->get();

        // 1. Personal questions for user1
        $pq1 = PersonalQuestion::create([
            'user_id' => $user1->id,
            'subject_id' => $bangla?->id,
            'topic_id' => $sandhi?->id,
            'question' => "নিপাতনে সিদ্ধ ব্যঞ্জনসন্ধির গুরুত্বপূর্ণ ৫টি শব্দ ও বিচ্ছেদ কী কী?",
            'answer' => "১. আ + চর্য = আশ্চর্য\n২. গো + পদ = গোষ্পদ\n৩. বন + পতি = বনস্পতি\n৪. বৃহৎ + পতি = বৃহস্পতি\n৫. মনস্ + ঈষা = মনীষা",
            'explanation' => "এগুলো সাধারণ নিয়মের বাইরে তাই পরীক্ষায় বারবার আসে। প্রতিদিন সকালে একবার রিভিশন দিতে হবে।",
            'source_title' => "অগ্রদূত বাংলা ব্যাকরণ",
            'source_page' => "পৃষ্ঠা ৮৭",
            'status' => 'active',
        ]);

        $tagImp = Tag::firstOrCreate(['user_id' => $user1->id, 'slug' => 'khub-guruttopurno'], ['name' => 'খুব গুরুত্বপূর্ণ']);
        $tagRev = Tag::firstOrCreate(['user_id' => $user1->id, 'slug' => 'revision'], ['name' => 'রিভিশন']);
        $pq1->tags()->sync([$tagImp->id, $tagRev->id]);

        $pq2 = PersonalQuestion::create([
            'user_id' => $user1->id,
            'subject_id' => $math?->id,
            'topic_id' => $percentage?->id,
            'question' => "একটি দ্রব্য ১০% লাভে বিক্রি করা হলো। ক্রয়মূল্য ১০% কম এবং বিক্রয়মূল্য ১৮ টাকা বেশি হলে লাভ হতো ২৫%। ক্রয়মূল্য কত?",
            'answer' => "৬০০ টাকা",
            'explanation' => "ক্রয়মূল্য x টাকা। ১ম বিক্রয়মূল্য ১.১০x। শর্তানুযায়ী: ০.৯০x এর ১২৫% = ১.১০x + ১৮\n=> ১.১২৫x - ১.১০x = ১৮\n=> ০.০২৫x = ১৮\n=> x = ১৮ / ০.০২৫ = ৬০০ টাকা।",
            'source_title' => "খায়রুলস বেসিক ম্যাথ (Khairul's Basic Math)",
            'source_page' => "পৃষ্ঠা ২১৪",
            'status' => 'active',
        ]);
        $pq2->tags()->sync([$tagImp->id]);

        // 2. Personal question for user2 (strictly isolated)
        $pq3 = PersonalQuestion::create([
            'user_id' => $user2->id,
            'subject_id' => $bangla?->id,
            'topic_id' => $sandhi?->id,
            'question' => "বাংলা ভাষার আদি নিদর্শন 'চর্যাপদ' নিয়ে ড. মুহম্মদ শহীদুল্লাহর অভিমত কী ছিল?",
            'answer' => "ড. মুহম্মদ শহীদুল্লাহর মতে চর্যাপদের রচনাকাল সপ্তম থেকে দ্বাদশ শতাব্দী (৬৫০-১২০০ খ্রিষ্টাব্দ)।",
            'explanation' => "সুনীতিকুমার চট্টোপাধ্যায়ের মতে এটি ৯৫০ থেকে ১২০০ খ্রিষ্টাব্দ। বিসিএসে দুইজনের মতই বিকল্প হিসেবে আসে।",
            'source_title' => "লাল নীল দীপাবলী — ড. হুমায়ুন আজাদ",
            'source_page' => "পৃষ্ঠা ২৯",
            'status' => 'active',
        ]);
        $tagBcs = Tag::firstOrCreate(['user_id' => $user2->id, 'slug' => 'bcs-prep'], ['name' => 'BCS Prep']);
        $pq3->tags()->sync([$tagBcs->id]);

        // 3. Seed bookmarks and progress for user1
        if ($guides->isNotEmpty()) {
            $firstGuide = $guides->first();
            Bookmark::firstOrCreate([
                'user_id' => $user1->id,
                'study_guide_id' => $firstGuide->id,
            ]);

            UserProgress::firstOrCreate(
                ['user_id' => $user1->id, 'study_guide_id' => $firstGuide->id],
                [
                    'status' => ProgressStatus::IN_PROGRESS,
                    'progress_percent' => 65,
                    'last_read_at' => now()->subHours(2),
                ]
            );

            if ($guides->count() > 1) {
                $secondGuide = $guides->get(1);
                UserProgress::firstOrCreate(
                    ['user_id' => $user1->id, 'study_guide_id' => $secondGuide->id],
                    [
                        'status' => ProgressStatus::COMPLETED,
                        'progress_percent' => 100,
                        'last_read_at' => now()->subDay(),
                        'completed_at' => now()->subDay(),
                    ]
                );
            }
        }
    }
}
