<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->isStarted()) {
            session()->start();
        }
        \Log::info('session', ['sesh' => session()]);
        // Allow access to the login page and authentication routes
        if (
            $request->routeIs('login')
            || $request->routeIs('login.post')
            || $request->routeIs('password.request')
            || $request->routeIs('password.email')
            || $request->routeIs('password.reset')
            || $request->routeIs('password.store')
            || $request->routeIs('register')
            || $request->routeIs('register.post')
        ) {
            return $next($request);
        }

        // If session contains user_id but Laravel lost authentication, restore it
        if (!Auth::check() && session()->has('user_id')) {
            Auth::login(User::find(session('user_id')));
            Auth::setUser(User::find(session('user_id')));
        }

        // Allow authenticated users to continue
        if (Auth::check()) {
            \Log::info('user', ['data', Auth::user()]);
            \Log::info('user', ['data', $request->user()]);
            return $next($request);
        }

        // Redirect only if unauthenticated and no user_id session exists
        return redirect()->route('login');
    }
}
