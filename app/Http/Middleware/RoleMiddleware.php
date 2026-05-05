<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        abort(403);
    }

    $user = auth()->user();

    if ($user->role !== $role) {
        abort(403, 'Unauthorized role: ' . $user->role);
    }

    return $next($request);
}
}