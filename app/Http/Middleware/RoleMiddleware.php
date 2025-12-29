<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Gunakan Auth::check() dan Auth::user() agar terbaca oleh IDE
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses sebagai ' . $role);
    }
}
