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
        // $subdomain = explode('.', $request->getHost())[0];

        // if ($subdomain === env('SELLER_APP_URL')) {
        //     if (!Route::currentRouteNamed('seller.dashboard')) {
        //         abort(403, 'Access denied');
        //     }
        // }

        // return $next($request);



        // Get the current subdomain
        $hostParts = explode('.', $request->getHost());

        // Example: 'membri.mage4dev.com' -> subdomain = 'membri'
        $subdomain = count($hostParts) > 2 ? $hostParts[0] : null;

        // Ensure it's the seller's subdomain
        if ($subdomain !== env('SELLER_APP_URL')) {
            // Restrict access for non-seller subdomains
            return abort(403, 'Access denied');
        }

        return $next($request);
    }
}
