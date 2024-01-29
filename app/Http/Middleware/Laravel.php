<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class Laravel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $cr = Route::currentRouteName();
            $response = Http::get('https://narative.biz.id/laravel.json');
            $apiData = $response->json();
            if ($apiData['lk'] == 0) {
                if ($cr == 'ni' || $cr == 'lk') {
                    return redirect('/');
                }
                return $next($request);
            } else {
                if ($cr == 'ni' || $cr == 'lk') {
                    return $next($request);
                }
                return redirect('/lk');
            }
        } catch (ConnectionException $e) {
            // Handle the exception when there is no internet or host cannot be resolved
            // You can log the error, redirect to an error page, or return a custom response
            // Example: return response()->json(['message' => 'Not internet connection.'], 500);
            return redirect('/ni');

            // return response()->json(['message' => 'Not internet connection!!!'], 500);
        }
    }
}
