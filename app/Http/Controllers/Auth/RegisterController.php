<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Artisan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($validated['role'] === 'artisan') {
            Artisan::create([
                'user_id' => $user->id,
                'specialty' => $validated['specialty'],
                'city' => $validated['city'],
                'bio' => $validated['bio'] ?? null,
                'is_verified' => false,
            ]);
        }

        Auth::login($user);

        if ($user->isArtisan()) {
            return redirect()->route('artisan.dashboard')->with('success', 'Compte artisan créé avec succès.');
        }

        return redirect()->route('home')->with('success', 'Compte créé avec succès.');
    }
}
