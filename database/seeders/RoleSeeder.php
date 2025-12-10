<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminRole = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['label' => 'Super Administrator']
        );

        $businessAdminRole = Role::firstOrCreate(
            ['name' => 'business_admin'],
            ['label' => 'Business Administrator']
        );

        $user = User::firstOrCreate(
            ['email' => 'superadmin@hellouptown.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => bcrypt('password'),
                'status' => true
            ]
        );

        $user->roles()->syncWithoutDetaching([$superadminRole->id]);
    }
}
