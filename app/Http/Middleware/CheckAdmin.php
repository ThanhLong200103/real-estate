<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // strtolower giúp khớp với cả 'Admin' (Seeder) và 'admin' (Middleware)
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'admin') {
           abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}