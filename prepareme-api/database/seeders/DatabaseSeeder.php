<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CurriculumSeeder::class,
            ComprehensiveCurriculumSeeder::class,
            ComprehensiveStudyGuidesSeeder::class,
            PublicQuestionSeeder::class,
            ExamAndModelTestSeeder::class,
            BanglaMasterComprehensiveCurriculumSeeder::class,
            EnglishMasterComprehensiveCurriculumSeeder::class,
            BcsPreliminaryCompleteSyllabusSeeder::class,
            PersonalNotebookSeeder::class,
        ]);
    }
}
