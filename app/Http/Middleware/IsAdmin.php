<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login dan rolenya adalah admin, silakan lewat!
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, tendang!
        return response()->json(['message' => 'Akses ditolak. Anda bukan Admin.'], 403);
    }
}