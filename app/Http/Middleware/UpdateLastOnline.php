<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastOnline
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (!$user->last_online || $user->last_online->lt(now()->subMinute()))) {
            $user->forceFill(['last_online' => now()])->saveQuietly();
        }

        return $next($request);
    }
}
