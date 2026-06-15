<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Beni Ouarain Premium',
                'slug' => 'beni-ouarain-premium',
                'category' => 'tapis-berberes',
                'artisan_email' => 'fatima.ait-hassan@tissu.ma',
                'price' => 1200.00,
                'is_featured' => true,
                'description' => 'Tapis Beni Ouarain tissé à la main en laine naturelle, motifs géométriques traditionnels du Moyen Atlas.',
                'material' => 'Laine naturelle',
                'dimensions' => '200 x 300 cm',
                'weight' => 12.50,
                'stock' => 5,
            ],
            [
                'name' => 'Broderie Fassi sur Soie',
                'slug' => 'broderie-fassi-sur-soie',
                'category' => 'broderies',
                'artisan_email' => 'khadija.bensouda@tissu.ma',
                'price' => 850.00,
                'is_featured' => true,
                'description' => 'Pièce brodée à la main selon la tradition fassi, sur soie premium aux motifs floraux raffinés.',
                'material' => 'Soie',
                'dimensions' => '150 x 200 cm',
                'weight' => 1.20,
                'stock' => 8,
            ],
            [
                'name' => 'Écharpe Sahara',
                'slug' => 'echarpe-sahara',
                'category' => 'teintures-naturelles',
                'artisan_email' => 'nezha.ouazzani@tissu.ma',
                'price' => 320.00,
                'is_featured' => true,
                'description' => 'Écharpe teinte aux pigments naturels (henné, indigo, safran), inspirée des couleurs du désert.',
                'material' => 'Coton bio',
                'dimensions' => '70 x 200 cm',
                'weight' => 0.35,
                'stock' => 15,
            ],
            [
                'name' => 'Djellaba Rbatie Brodée',
                'slug' => 'djellaba-rbatie-brodee',
                'category' => 'djellabas',
                'artisan_email' => 'amina.elhachimi@tissu.ma',
                'price' => 1800.00,
                'is_featured' => true,
                'description' => 'Djellaba traditionnelle de Rabat, broderie dorée et finitions artisanales d\'exception.',
                'material' => 'Coton et soie',
                'dimensions' => 'Taille unique',
                'weight' => 1.80,
                'stock' => 4,
            ],
            [
                'name' => 'Kilim Chefchaouen',
                'slug' => 'kilim-chefchaouen',
                'category' => 'kilims',
                'artisan_email' => 'mohamed.ziani@tissu.ma',
                'price' => 680.00,
                'is_featured' => false,
                'description' => 'Kilim aux tons bleus et blancs, tissé à la main dans la tradition de Chefchaouen.',
                'material' => 'Laine et coton',
                'dimensions' => '120 x 180 cm',
                'weight' => 4.20,
                'stock' => 10,
            ],
            [
                'name' => 'Tajine Peint Main',
                'slug' => 'tajine-peint-main',
                'category' => 'ceramiques',
                'artisan_email' => 'fatima.ait-hassan@tissu.ma',
                'price' => 450.00,
                'is_featured' => false,
                'description' => 'Tajine en céramique peinte à la main, motifs berbères multicolores, pièce utilisable au four.',
                'material' => 'Céramique',
                'dimensions' => 'Ø 30 cm',
                'weight' => 2.50,
                'stock' => 12,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->firstOrFail();
            $artisan = Artisan::whereHas('user', fn ($q) => $q->where('email', $data['artisan_email']))->firstOrFail();

            Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'artisan_id' => $artisan->id,
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'weight' => $data['weight'],
                    'dimensions' => $data['dimensions'],
                    'material' => $data['material'],
                    'is_featured' => $data['is_featured'],
                    'is_active' => true,
                ]
            );
        }
    }
}
