<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'johnnlopezt@gmail.com'],
            [
                'name' => 'John Lopez',
                'password' => Hash::make('1029880299'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}
