<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();
        $teacher = User::where('username', 'teacher1')->first();
        $student = User::where('username', 'student1')->first();

        DB::table('activity_logs')->insert([
            [
                'user_id' => $admin?->id,
                'role_id' => $admin?->role_id,
                'action_type' => 'CREATE',
                'module_name' => 'User Management',
                'description' => 'Created teacher account Juan Dela Cruz.',
                'old_values' => null,
                'new_values' => json_encode([
                    'username' => 'teacher1',
                    'role' => 'Teacher'
                ]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Desktop',
                'browser' => 'Chrome',
                'operating_system' => 'Windows 11',
                'session_id' => null,
                'created_at' => Carbon::now()->subMinutes(5),
            ],

            [
                'user_id' => $teacher?->id,
                'role_id' => $teacher?->role_id,
                'action_type' => 'UPDATE',
                'module_name' => 'Grades',
                'description' => 'Updated grades for Grade 11 STEM - Section A.',
                'old_values' => json_encode([
                    'average' => 88
                ]),
                'new_values' => json_encode([
                    'average' => 90
                ]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Desktop',
                'browser' => 'Chrome',
                'operating_system' => 'Windows 11',
                'session_id' => null,
                'created_at' => Carbon::now()->subMinutes(12),
            ],

            [
                'user_id' => $student?->id,
                'role_id' => $student?->role_id,
                'action_type' => 'LOGIN',
                'module_name' => 'Authentication',
                'description' => 'Logged into the system.',
                'old_values' => null,
                'new_values' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Mobile',
                'browser' => 'Chrome Mobile',
                'operating_system' => 'Android',
                'session_id' => null,
                'created_at' => Carbon::now()->subMinutes(20),
            ],

            [
                'user_id' => $admin?->id,
                'role_id' => $admin?->role_id,
                'action_type' => 'APPROVE',
                'module_name' => 'Class Records',
                'description' => 'Approved Grade 12 ABM class records.',
                'old_values' => null,
                'new_values' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Desktop',
                'browser' => 'Edge',
                'operating_system' => 'Windows 11',
                'session_id' => null,
                'created_at' => Carbon::now()->subMinutes(35),
            ],

            [
                'user_id' => $teacher?->id,
                'role_id' => $teacher?->role_id,
                'action_type' => 'UPLOAD',
                'module_name' => 'Excel Synchronization',
                'description' => 'Uploaded updated class record spreadsheet.',
                'old_values' => null,
                'new_values' => json_encode([
                    'file' => 'grade11_stem.xlsx'
                ]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Desktop',
                'browser' => 'Chrome',
                'operating_system' => 'Windows 11',
                'session_id' => null,
                'created_at' => Carbon::now()->subHour(),
            ],

            [
                'user_id' => $admin?->id,
                'role_id' => $admin?->role_id,
                'action_type' => 'DELETE',
                'module_name' => 'Subject Management',
                'description' => 'Deleted subject TEST101.',
                'old_values' => json_encode([
                    'code' => 'TEST101'
                ]),
                'new_values' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'device_type' => 'Desktop',
                'browser' => 'Chrome',
                'operating_system' => 'Windows 11',
                'session_id' => null,
                'created_at' => Carbon::now()->subHours(2),
            ],
        ]);
    }
}