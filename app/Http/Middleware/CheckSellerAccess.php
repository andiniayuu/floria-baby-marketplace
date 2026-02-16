<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan user login
        if (!auth()->check()) {
            return redirect()->route('filament.seller.auth.login');
        }

        $user = auth()->user();

        // 2. Cek role seller
        if ($user->role !== 'seller') {
            abort(403, 'Anda bukan seller. Silakan ajukan pendaftaran seller.');
        }

        // 3. Cek status approval seller
        if ($user->seller_status !== 'approved') {
            auth()->logout();

            return redirect()->route('filament.seller.auth.login')
                ->with('error', 'Akun Seller Anda belum disetujui oleh Admin.');
        }

        // 4. Cek apakah akun dibanned
        if ($user->is_banned) {
            auth()->logout();

            return redirect()->route('filament.seller.auth.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
