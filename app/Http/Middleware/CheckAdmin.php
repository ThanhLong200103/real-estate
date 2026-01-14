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
            return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực này.');
        }

        return $next($request);
    }
}