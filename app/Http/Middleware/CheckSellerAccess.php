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
        $user = auth()->user();

        // Cek apakah user adalah seller
        if (!$user || $user->role !== 'seller') {
            auth()->logout();
            return redirect()->route('filament.seller.auth.login')
                ->with('error', 'Anda tidak memiliki akses ke Seller Dashboard.');
        }

        // Cek apakah seller sudah diapprove
        if (($user->seller_info['status'] ?? '') !== 'approved') {
            auth()->logout();
            return redirect()->route('filament.seller.auth.login')
                ->with('error', 'Akun Seller Anda belum disetujui oleh Admin.');
        }

        // Cek apakah user dibanned
        if ($user->is_banned) {
            auth()->logout();
            return redirect()->route('filament.seller.auth.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
