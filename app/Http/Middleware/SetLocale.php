<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $availableLocales = ['en', 'ps', 'fa'];
        
        // Check if locale is set in session
        if (session()->has('locale')) {
            $locale = session('locale');
        } 
        // Check if locale is in URL parameter
        elseif ($request->has('lang')) {
            $locale = $request->get('lang');
        }
        // Check if locale is in URL path
        elseif ($request->segment(1) && in_array($request->segment(1), $availableLocales)) {
            $locale = $request->segment(1);
        }
        // Use browser language if available
        elseif ($request->hasHeader('Accept-Language')) {
            $browserLocale = substr($request->header('Accept-Language'), 0, 2);
            $locale = in_array($browserLocale, $availableLocales) ? $browserLocale : 'en';
        }
        // Default to English
        else {
            $locale = 'en';
        }
        
        // Validate locale
        if (in_array($locale, $availableLocales)) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }
        
        return $next($request);
    }
}
