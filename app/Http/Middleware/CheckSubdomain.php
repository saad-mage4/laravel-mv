<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckSubdomain
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
        // Get the current subdomain
        $subdomain = explode('.', $request->getHost())[0];

        // Check if it's the membri subdomain
        if ($subdomain === env('SELLER_APP_URL')) {
            // Allow access only to seller.dashboard
            if (!Route::currentRouteNamed('seller.dashboard')) {
                abort(403, 'Access denied');
            }
        }

        return $next($request);
    }
}
