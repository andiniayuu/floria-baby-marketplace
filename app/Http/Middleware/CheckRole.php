<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login'); // Frontend login, bukan Filament
        }

        $user = auth()->user();

        // Cek apakah role user sesuai dengan yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, redirect ke homepage dengan error
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
