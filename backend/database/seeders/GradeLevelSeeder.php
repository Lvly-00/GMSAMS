<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

class GradeLevelSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Grade 11', 'Grade 12'] as $name) {
            GradeLevel::query()->updateOrCreate(['name' => $name]);
        }
    }
}
