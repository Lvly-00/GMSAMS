<?php

namespace Database\Seeders;

use App\Models\Strand;
use Illuminate\Database\Seeder;

class StrandSeeder extends Seeder
{
    public function run(): void
    {
        $strands = [
            ['code' => 'STEM', 'name' => 'Science, Technology, Engineering and Mathematics'],
            ['code' => 'ABM', 'name' => 'Accountancy, Business and Management'],
            ['code' => 'HUMSS', 'name' => 'Humanities and Social Sciences'],
            ['code' => 'TVL', 'name' => 'Technical-Vocational-Livelihood'],
            ['code' => 'GAS', 'name' => 'General Academic Strand'],
        ];

        foreach ($strands as $strand) {
            Strand::query()->updateOrCreate(
                ['code' => $strand['code']],
                ['name' => $strand['name']]
            );
        }
    }
}
