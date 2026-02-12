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
            if (!Auth::check()) {
                return redirect('/');
            }

            // Only allow Admin (Type 1)
            if (Auth::user()->user_type == 1) {
                return $next($request);
            }

            // If they aren't Admin, send them to their own dashboard
            return redirect()->back()->with('error', 'Access Denied: You do not have Admin permissions.');
        }
}
