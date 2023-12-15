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
        if (! $request->expectsJson()) {
            // Comentario la línea de redirección
            // return route('login');
            
            // Devolver una respuesta JSON con código de estado 401
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        // Verificar la existencia del token en el encabezado 'Authorization'
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
