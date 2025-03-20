<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = Role::where('name', 'admin')->first()->id;

        User::firstOrCreate(
            ['email' => 'admin@delivery.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@delivery.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRoleId,
                'email_verified_at' => now(),
            ]
        );
    }
}
