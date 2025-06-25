<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinifyHtml
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response && str_contains($response->headers->get('content-type'), 'text/html')) {
            $output = $response->getContent();
            // Minify HTML: remove comments, extra whitespace
            $output = preg_replace([
                '/<!--[\s\S]*?-->/',     // Remove comments
                '/\>[^\S ]+/s',          // Strip whitespace after tags
                '/[^\S ]+\</s',          // Strip whitespace before tags
                '/(\s)+/s',              // Shorten multiple whitespace
            ], ['', '>', '<', '\\1'], $output);
            $response->setContent($output);
        }

        return $response;
    }
}