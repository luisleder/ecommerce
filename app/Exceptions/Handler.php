<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function(CustomException $ce){
            return response()->json([
                'message' => $ce->getMessage()
            ], $ce->getCode());
        });

        $this->renderable(function(MethodNotAllowedHttpException $e){
            return response()->json([
                'message' => "Route not found"
            ], 404);
        });

    }

}
