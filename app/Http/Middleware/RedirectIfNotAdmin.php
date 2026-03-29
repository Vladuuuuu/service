<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->isAdmin()) {
            if ($user->isService()) {
                return redirect()->route('service.dashboard');
            }
            return redirect()->route('client.dashboard');
        }

        return $next($request);
    }
}
