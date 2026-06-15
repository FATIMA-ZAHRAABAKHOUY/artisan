<?php

namespace App\Http\Controllers;

use App\Models\Artisan;

class ArtisanController extends Controller
{
    public function index()
    {
        $artisans = Artisan::query()
            ->where('is_verified', true)
            ->with('user')
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->orderByDesc('rating')
            ->paginate(12);

        return view('artisans.index', compact('artisans'));
    }

    public function show(Artisan $artisan)
    {
        if (! $artisan->is_verified) {
            abort(404);
        }

        $artisan->load('user');

        $products = $artisan->products()
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('artisans.show', compact('artisan', 'products'));
    }
}
