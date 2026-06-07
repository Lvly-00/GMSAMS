<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Optional dev seeder — run with: php artisan db:seed --class=AdminUserSeeder
     */
    public function run(): void
    {
        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();

        User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'role_id' => $adminRole->id,
                'email' => 'admin@atec-apalit.edu.ph',
                'password_hash' => Hash::make('Admin@123'),
                'is_active' => true,
                'email_verified' => true,
            ]
        );
    }
}
