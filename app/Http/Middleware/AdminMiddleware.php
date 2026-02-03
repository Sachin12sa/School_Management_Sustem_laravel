<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in
        if (!Auth::check()) {
            return redirect('/');
        }

        // If logged in but not admin
        if (Auth::user()->user_type != 1) {
            Auth::logout();
            return redirect('/');
        }

        // User is admin
        return $next($request);
    }
}
