<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RestrictLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        // Check if session('login_email') is set
        if (Session::has('login_email')) {
            // Redirect to dashboard with an error message
            return redirect()->route('UserTable')->with('error', 'You do not have permission to access this page.');
        }

        // Allow the request to proceed if not logged in
        return $next($request);
    }
}