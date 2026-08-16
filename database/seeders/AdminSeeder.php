<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cvadz.com'],
            [
                'name' => 'Admin CV Adzra',
                'password' => 'admin123',
                'role' => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'staff@cvadz.com'],
            [
                'name' => 'Staff CV Adzra',
                'password' => 'staff123',
                'role' => 'staff',
            ],
        );
    }
}
