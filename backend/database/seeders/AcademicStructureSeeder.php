<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Strand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::query()->updateOrCreate(
            ['label' => '2025-2026'],
            ['is_current' => true]
        );

        Semester::query()->updateOrCreate(
            ['school_year_id' => $schoolYear->id, 'name' => '1st Semester'],
            [
                'start_date' => Carbon::parse('2025-06-01'),
                'end_date' => Carbon::parse('2025-10-31'),
                'is_active' => true,
            ]
        );

        Semester::query()->updateOrCreate(
            ['school_year_id' => $schoolYear->id, 'name' => '2nd Semester'],
            [
                'start_date' => Carbon::parse('2025-11-01'),
                'end_date' => Carbon::parse('2026-03-31'),
                'is_active' => false,
            ]
        );

        $grade11 = GradeLevel::query()->where('name', 'Grade 11')->first();
        $stem = Strand::query()->where('code', 'STEM')->first();

        if ($grade11 && $stem) {
            Section::query()->updateOrCreate(
                [
                    'school_year_id' => $schoolYear->id,
                    'grade_level_id' => $grade11->id,
                    'strand_id' => $stem->id,
                    'name' => 'STEM 11-A',
                ],
                ['adviser_id' => null]
            );
        }
    }
}
