<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isRestaurantOwner()) {
            abort(403, 'Access denied. Restaurant owners only.');
        }

        return $next($request);
    }
}