<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Strand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $grade11 = GradeLevel::where('name', 'Grade 11')->first();
        $grade12 = GradeLevel::where('name', 'Grade 12')->first();

        $stem = Strand::where('code', 'STEM')->first();
        $abm = Strand::where('code', 'ABM')->first();
        $humss = Strand::where('code', 'HUMSS')->first();
        $gas = Strand::where('code', 'GAS')->first();

        $subjects = [
            // STEM
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $stem->id,
                'code' => 'GENMATH',
                'name' => 'General Mathematics',
            ],
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $stem->id,
                'code' => 'PRECAL',
                'name' => 'Pre-Calculus',
            ],
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $stem->id,
                'code' => 'BASICCAL',
                'name' => 'Basic Calculus',
            ],

            // ABM
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $abm->id,
                'code' => 'FABM1',
                'name' => 'Fundamentals of Accountancy, Business and Management 1',
            ],
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $abm->id,
                'code' => 'FABM2',
                'name' => 'Fundamentals of Accountancy, Business and Management 2',
            ],

            // HUMSS
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $humss->id,
                'code' => 'DISCIP',
                'name' => 'Disciplines and Ideas in the Social Sciences',
            ],
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $humss->id,
                'code' => 'TRENDS',
                'name' => 'Trends, Networks and Critical Thinking',
            ],

            // GAS
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $gas->id,
                'code' => 'UCSP',
                'name' => 'Understanding Culture, Society and Politics',
            ],
            [
                'grade_level_id' => $grade11->id,
                'strand_id' => $gas->id,
                'code' => 'PPG',
                'name' => 'Philippine Politics and Governance',
            ],

            // Grade 12 STEM
            [
                'grade_level_id' => $grade12->id,
                'strand_id' => $stem->id,
                'code' => 'PHYSICS',
                'name' => 'General Physics',
            ],
            [
                'grade_level_id' => $grade12->id,
                'strand_id' => $stem->id,
                'code' => 'CHEM',
                'name' => 'General Chemistry',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'id' => Str::uuid(),
                'grade_level_id' => $subject['grade_level_id'],
                'strand_id' => $subject['strand_id'],
                'code' => $subject['code'],
                'name' => $subject['name'],
                'is_hidden' => false,
            ]);
        }
    }
}