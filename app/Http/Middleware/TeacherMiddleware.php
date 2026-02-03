<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in
        if (!Auth::check()) {
            return redirect('/');
        }

        // If logged in but not admin
        if (Auth::user()->user_type != 2) {
            Auth::logout();
            return redirect('/');
        }

        // User is admin
        return $next($request);
    }
}
