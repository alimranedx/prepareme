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
            PublicQuestionSeeder::class,
            PersonalNotebookSeeder::class,
        ]);
    }
}
