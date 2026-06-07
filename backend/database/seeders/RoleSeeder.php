<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System administrator'],
            ['name' => 'head_teacher', 'description' => 'Head teacher — approvals and DepEd forms'],
            ['name' => 'teacher', 'description' => 'Classroom teacher — grade encoding'],
            ['name' => 'student', 'description' => 'Enrolled student'],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
