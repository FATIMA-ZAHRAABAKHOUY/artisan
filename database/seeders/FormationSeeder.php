<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Formation;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        $formations = [
            [
                'title' => 'Tapis Beni Ouarain',
                'city' => 'Azrou',
                'price' => 480.00,
                'max_participants' => 10,
                'current_participants' => 7,
                'artisan_email' => 'fatima.ait-hassan@tissu.ma',
                'date_debut' => now()->addDays(21)->toDateString(),
                'description' => 'Initiation au tissage traditionnel des tapis Beni Ouarain : préparation de la laine, motifs et techniques ancestrales.',
            ],
            [
                'title' => 'Broderie Fassi',
                'city' => 'Fès',
                'price' => 420.00,
                'max_participants' => 10,
                'current_participants' => 4,
                'artisan_email' => 'khadija.bensouda@tissu.ma',
                'date_debut' => now()->addDays(35)->toDateString(),
                'description' => 'Atelier de broderie fassi sur soie : points traditionnels, composition de motifs et finitions artisanales.',
            ],
            [
                'title' => 'Teinture Naturelle',
                'city' => 'Marrakech',
                'price' => 320.00,
                'max_participants' => 10,
                'current_participants' => 2,
                'artisan_email' => 'nezha.ouazzani@tissu.ma',
                'date_debut' => now()->addDays(14)->toDateString(),
                'description' => 'Découverte des teintures végétales marocaines : indigo, henne, safran et techniques de fixation naturelle.',
            ],
        ];

        foreach ($formations as $data) {
            $artisan = Artisan::whereHas('user', fn ($q) => $q->where('email', $data['artisan_email']))->firstOrFail();

            Formation::updateOrCreate(
                [
                    'title' => $data['title'],
                    'city' => $data['city'],
                ],
                [
                    'artisan_id' => $artisan->id,
                    'description' => $data['description'],
                    'date_debut' => $data['date_debut'],
                    'price' => $data['price'],
                    'max_participants' => $data['max_participants'],
                    'current_participants' => $data['current_participants'],
                    'is_free' => false,
                    'is_active' => true,
                ]
            );
        }
    }
}
