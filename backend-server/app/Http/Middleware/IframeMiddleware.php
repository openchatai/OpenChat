<?php

namespace App\Http\Middleware;

use App\Models\Chatbot;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IframeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // @todo  Set X-Frame-Options header to allow iframe embedding from the chatbot's website and its data sources
        $response = $next($request);
        //  $response->headers->set('X-Frame-Options', 'ALLOW-FROM ' . implode(' ', $domains), false);
        return $response;
    }
}
