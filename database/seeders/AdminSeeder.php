<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tissu.ma'],
            [
                'name' => 'Administrateur Tissu',
                'password' => 'password',
                'role' => 'admin',
                'phone' => '+212600000001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
