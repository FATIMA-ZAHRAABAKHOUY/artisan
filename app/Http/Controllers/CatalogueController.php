<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->publicCatalogue()
            ->with(['artisan.user', 'category']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'))
                    ->orWhere('id', $request->input('category'));
            });
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('verified')) {
            $query->fromVerifiedArtisan();
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->withCount(['products' => fn ($q) => $q->publicCatalogue()])
            ->orderBy('name')
            ->get();

        return view('catalogue.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['artisan.user', 'category', 'images']);

        if (! $product->is_active || ! $product->artisan?->is_verified) {
            abort(404);
        }

        $relatedProducts = Product::query()
            ->publicCatalogue()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['artisan.user', 'category'])
            ->limit(4)
            ->get();

        return view('catalogue.show', compact('product', 'relatedProducts'));
    }
}
