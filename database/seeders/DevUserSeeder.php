<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create development user
        User::updateOrCreate(
            ['email' => 'dev@dev.com'],
            [
                'name' => 'Development User',
                'email' => 'dev@dev.com',
                'password' => Hash::make('dev'),
                'email_verified_at' => now(),
            ]
        );
    }
}
