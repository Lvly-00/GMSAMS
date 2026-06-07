<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $hashedPassword = Hash::make('password');

        // Head Teacher
        User::create([
            'id' => Str::uuid(),
            'role_id' => 2,
            'username' => 'headteacher',
            'email' => 'headteacher@example.com',
            'password_hash' => $hashedPassword, 
            'email_verified' => true,
        ]);

        // Teachers
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'id' => Str::uuid(),
                'role_id' => 3,
                'username' => "teacher{$i}",
                'email' => "teacher{$i}@example.com",
                'password_hash' => $hashedPassword, 
                'email_verified' => true,
            ]);
        }

        // Students
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'id' => Str::uuid(),
                'role_id' => 4,
                'username' => "student{$i}",
                'email' => "student{$i}@example.com",
                'password_hash' => $hashedPassword, 
                'email_verified' => true,
            ]);
        }
    }
}