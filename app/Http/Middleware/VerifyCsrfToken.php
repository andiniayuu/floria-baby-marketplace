<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URI yang dikecualikan dari verifikasi CSRF.
     * Webhook Midtrans berasal dari server eksternal sehingga tidak punya CSRF token.
     *
     * @var array<int, string>
     */
    protected $except = [
        'payment/notification',
    ];
}
