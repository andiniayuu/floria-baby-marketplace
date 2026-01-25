<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Cek apakah user adalah admin
        if (!$user || $user->role !== 'admin') {
            auth()->logout();

            return redirect()
                ->route('filament.admin.auth.login')
                ->with('error', 'Anda tidak memiliki akses ke Admin Panel.');
        }

        // Cek apakah user dibanned
        if ($user->is_banned) {
            auth()->logout();

            return redirect()
                ->route('filament.admin.auth.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
