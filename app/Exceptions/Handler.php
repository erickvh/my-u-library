<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request,  $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated'
            ], 401);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json([
                'error' => 'Endpoint not found'
            ], 404);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method not allowed'
            ], 405);
        }


        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->json([
                'message' => 'You are not authorized to access this resource'
            ], 403);
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->json([
                'message' => 'You are not authorized to access this resource'
            ], 403);
        }
        return parent::render($request, $exception);
    }
}
