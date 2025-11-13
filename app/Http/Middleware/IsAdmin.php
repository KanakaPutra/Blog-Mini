<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        // cek admin biasa atau super admin
        if (! Auth::check() || (Auth::user()->is_admin != 1 && Auth::user()->is_admin != 2)) {
            abort(403, 'Akses ditolak! Anda bukan admin.');
        }

        return $next($request);
    }
}
