<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tapis Berbères', 'icon' => '🎨'],
            ['name' => 'Broderies', 'icon' => '🧵'],
            ['name' => 'Céramiques', 'icon' => '🏺'],
            ['name' => 'Djellabas', 'icon' => '👘'],
            ['name' => 'Teintures Naturelles', 'icon' => '🌿'],
            ['name' => 'Kilims', 'icon' => '🎨'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'description' => "Artisanat traditionnel marocain — {$category['name']}.",
                ]
            );
        }
    }
}
