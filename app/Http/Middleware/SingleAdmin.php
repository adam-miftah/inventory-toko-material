<?php

namespace App\Http\Middleware;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SingleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    if (User::count() >= 1 && Auth::check()) {
        abort(403, 'Akses hanya untuk admin terdaftar');
    }
    
    return $next($request);
}
}