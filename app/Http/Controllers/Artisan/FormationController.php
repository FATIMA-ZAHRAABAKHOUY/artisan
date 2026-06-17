<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Formation;

class FormationController extends Controller
{
    public function index()
    {
        $formations = auth()->user()->artisan
            ->formations()
            ->withCount('enrollments')
            ->orderBy('date_debut')
            ->paginate(12);

        return view('artisan.formations.index', compact('formations'));
    }

    public function enrollments(Formation $formation)
    {
        $artisan = auth()->user()->artisan;

        if ($formation->artisan_id !== $artisan->id) {
            abort(403);
        }

        $enrollments = $formation->enrollments()
            ->with('user')
            ->orderByDesc('enrolled_at')
            ->paginate(20);

        return view('artisan.formations.enrollments', compact('formation', 'enrollments'));
    }
}
