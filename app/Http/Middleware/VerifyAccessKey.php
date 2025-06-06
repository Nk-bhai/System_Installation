<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Allow access to the key input page
        if ($request->is('key')) {
            return $next($request);
        }

        // Check session for access key
        if (!session()->has('access_granted')) {
            return redirect('/key')->with('error', 'Access denied. Please enter the access key.');
        }
        return $next($request);
    }
}
