<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsArtisan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'artisan' || ! $user->artisan) {
            abort(403, 'Accès réservé aux artisans.');
        }

        return $next($request);
    }
}
