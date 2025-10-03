<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Recipient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isRecipient()) {
            abort(403, 'Access denied. Recipients only.');
        }

        if (!auth()->user()->isActive()) {
            abort(403, 'Access denied. Your account is not active. Please contact administrator.');
        }

        return $next($request);
    }
}