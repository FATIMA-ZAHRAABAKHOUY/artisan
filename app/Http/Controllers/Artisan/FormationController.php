<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;

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
}
