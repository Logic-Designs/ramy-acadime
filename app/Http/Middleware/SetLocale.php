<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('lang', 'en');

        if (!in_array($locale, ['en', 'ar'])) {
            return FacadesResponse::error('Invalid language specified.', [], 400);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
