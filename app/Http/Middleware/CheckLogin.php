<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
       public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('login_email') && !$request->is('login') && !$request->is('admin')) {
            return redirect()->route('system.auth.login')->with('error', 'Please login first');
        }

        return $next($request);
    }
    
}
