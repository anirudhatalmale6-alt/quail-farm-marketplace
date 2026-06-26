<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->timestamps = false;
            $user->last_seen_at = now();
            $user->is_online = true;
            $user->save();
        }

        return $next($request);
    }
}
