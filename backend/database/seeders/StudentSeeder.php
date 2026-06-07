<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role_id', 4)->get();

        foreach ($students as $index => $user) {
            Student::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'student_id_no' => 'STU-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'lrn' => str_pad((string) rand(100000000000, 999999999999), 12, '0'),
                'first_name' => 'Student',
                'middle_name' => 'M',
                'last_name' => $index + 1,
                'gender' => $index % 2 === 0 ? 'Male' : 'Female',
                'birthdate' => now()->subYears(rand(16, 19))->format('Y-m-d'),
            ]);
        }
    }
}