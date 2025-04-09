<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }else if (auth()->check() && auth()->user()->isStudent()) {
            return redirect()->route('studentDashboard');
        }else if (auth()->check() && auth()->user()->isParent()) {
            return redirect()->route('parentDashboard');
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
