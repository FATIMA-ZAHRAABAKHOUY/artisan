<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Formation;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->publicCatalogue()
            ->where('is_featured', true)
            ->with(['artisan.user', 'category'])
            ->latest()
            ->limit(4)
            ->get();

        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->publicCatalogue()])
            ->orderBy('name')
            ->get();

        $formations = Formation::query()
            ->where('is_active', true)
            ->where('date_debut', '>=', now()->toDateString())
            ->with('artisan.user')
            ->orderBy('date_debut')
            ->limit(3)
            ->get();

        $stats = [
            'artisans' => Artisan::where('is_verified', true)->count(),
            'products' => Product::publicCatalogue()->count(),
            'formations' => Formation::where('is_active', true)->count(),
        ];

        return view('home.index', compact('featuredProducts', 'categories', 'formations', 'stats'));
    }
}
