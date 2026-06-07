<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            GradeLevelSeeder::class,
            AssessmentCategorySeeder::class,
            StrandSeeder::class,
            AcademicStructureSeeder::class,
            UserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            AdminUserSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
