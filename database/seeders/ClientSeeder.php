<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Youssef Benali',
                'email' => 'youssef.benali@tissu.ma',
                'phone' => '+212612345678',
            ],
            [
                'name' => 'Leila Mansouri',
                'email' => 'leila.mansouri@tissu.ma',
                'phone' => '+212623456789',
            ],
            [
                'name' => 'Karim Alami',
                'email' => 'karim.alami@tissu.ma',
                'phone' => '+212634567890',
            ],
        ];

        foreach ($clients as $client) {
            User::updateOrCreate(
                ['email' => $client['email']],
                [
                    'name' => $client['name'],
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'phone' => $client['phone'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
