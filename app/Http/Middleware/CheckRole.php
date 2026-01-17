<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (in_array($user->role, $roles)) {
            // Cek khusus untuk seller
            if ($user->role === 'seller' && $user->seller_status !== 'approved') {
                abort(403, 'Your seller account is pending approval');
            }
            
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
