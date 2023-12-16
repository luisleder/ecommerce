<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    // Add new method to handler unauthenticated users
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'UnAuthenticated',
        ], 401));
    }

    // Handle an incoming request.
    public function handle($request, Closure $next, ...$guards)
    {
        // validate the header 'Authorization'
        if (!$request->header('Authorization')) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
