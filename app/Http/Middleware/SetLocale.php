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
        $availableLocales = ['en', 'de'];
        $locale = $request->session()->get('locale', config('app.locale'));

        if (! in_array($locale, $availableLocales, true)) {
            $locale = config('app.fallback_locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
