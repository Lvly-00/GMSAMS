<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role_id', 2)->get();

        foreach ($teachers as $index => $user) {
            Teacher::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'employee_id_no' => 'EMP-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'first_name' => 'Teacher',
                'last_name' => $index + 1,
                'is_head_teacher' => $index === 0,
                'department' => 'Senior High School',
            ]);
        }
    }
}