<?php

namespace Database\Seeders;

use App\Models\AssessmentCategory;
use Illuminate\Database\Seeder;

class AssessmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Written Works', 'weight' => 25],
            ['name' => 'Performance Tasks', 'weight' => 50],
            ['name' => 'Quarterly Assessment', 'weight' => 25],
        ];

        foreach ($categories as $category) {
            AssessmentCategory::query()->updateOrCreate(
                ['name' => $category['name']],
                ['weight' => $category['weight']]
            );
        }
    }
}
