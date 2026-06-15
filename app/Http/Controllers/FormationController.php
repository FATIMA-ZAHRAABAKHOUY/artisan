<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollFormationRequest;
use App\Models\Formation;
use App\Models\FormationEnrollment;
use Illuminate\Http\RedirectResponse;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::query()
            ->where('is_active', true)
            ->with('artisan.user')
            ->orderBy('date_debut')
            ->paginate(12);

        return view('formations.index', compact('formations'));
    }

    public function show(Formation $formation)
    {
        if (! $formation->is_active) {
            abort(404);
        }

        $formation->load(['artisan.user', 'enrollments']);

        $isEnrolled = auth()->check()
            && $formation->enrollments()->where('user_id', auth()->id())->exists();

        return view('formations.show', compact('formation', 'isEnrolled'));
    }

    public function enroll(EnrollFormationRequest $request, Formation $formation): RedirectResponse
    {
        if (! $formation->is_active) {
            return back()->withErrors(['formation' => 'Cette formation n\'est plus disponible.']);
        }

        if (! $formation->hasAvailableSpots()) {
            return back()->withErrors(['formation' => 'Cette formation est complète.']);
        }

        $existingEnrollment = FormationEnrollment::query()
            ->where('formation_id', $formation->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($existingEnrollment) {
            return back()->withErrors(['formation' => 'Vous êtes déjà inscrit à cette formation.']);
        }

        FormationEnrollment::create([
            'formation_id' => $formation->id,
            'user_id' => $request->user()->id,
            'enrolled_at' => now(),
            'status' => 'confirmee',
        ]);

        $formation->increment('current_participants');

        return redirect()
            ->route('formations.show', $formation)
            ->with('success', 'Inscription confirmée avec succès.');
    }
}
