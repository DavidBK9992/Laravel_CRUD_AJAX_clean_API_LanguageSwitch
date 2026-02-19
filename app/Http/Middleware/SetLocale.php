<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = ['en', 'de', 'it', 'hu', 'hi'];

        $sessionLocale = $request->session()->get('locale');
        $cookieLocale = $request->cookie('locale');
        $browserLocale = $request->getPreferredLanguage($availableLocales);

        $locale = $sessionLocale
            ?? $cookieLocale
            ?? $browserLocale
            ?? config('app.locale');

        if (! in_array($locale, $availableLocales, true)) {
            $locale = config('app.fallback_locale');
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        $response = $next($request);
        $response->headers->setCookie(cookie('locale', $locale, 60 * 24 * 30));

        return $response;
    }
}

