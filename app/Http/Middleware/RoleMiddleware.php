<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // If not logged in → go to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // If wrong role → 403
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}