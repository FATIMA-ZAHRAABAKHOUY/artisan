<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ArtisanSeeder extends Seeder
{
    public function run(): void
    {
        $artisans = [
            [
                'name' => 'Fatima Ait Hassan',
                'email' => 'fatima.ait-hassan@tissu.ma',
                'specialty' => 'Tapis Berbères',
                'city' => 'Azrou',
                'rating' => 4.9,
            ],
            [
                'name' => 'Khadija Bensouda',
                'email' => 'khadija.bensouda@tissu.ma',
                'specialty' => 'Broderies (Fassi)',
                'city' => 'Fès',
                'rating' => 4.7,
            ],
            [
                'name' => 'Nezha Ouazzani',
                'email' => 'nezha.ouazzani@tissu.ma',
                'specialty' => 'Teintures',
                'city' => 'Marrakech',
                'rating' => 4.8,
            ],
            [
                'name' => 'Amina Elhachimi',
                'email' => 'amina.elhachimi@tissu.ma',
                'specialty' => 'Djellabas (Rbati)',
                'city' => 'Rabat',
                'rating' => 4.6,
            ],
            [
                'name' => 'Mohamed Ziani',
                'email' => 'mohamed.ziani@tissu.ma',
                'specialty' => 'Kilims',
                'city' => 'Chefchaouen',
                'rating' => 4.9,
            ],
        ];

        foreach ($artisans as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'artisan',
                    'phone' => null,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            Artisan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty' => $data['specialty'],
                    'city' => $data['city'],
                    'bio' => "Artisane passionnée spécialisée en {$data['specialty']}, basée à {$data['city']}.",
                    'is_verified' => true,
                    'rating' => $data['rating'],
                    'total_reviews' => rand(12, 48),
                ]
            );
        }
    }
}
