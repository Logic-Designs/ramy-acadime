<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, $request) {
            // Custom API exception handling
            if ($request->expectsJson()) {
                return handleApiException($exception, $request);
            }

            // Default rendering for non-JSON requests (e.g., web requests)
            return null;
        });
    })->create();


function handleApiException(Throwable $exception, $request)
{
    if ($exception instanceof ValidationException) {
        return Response::error(
            __('validation.failed'),
            $exception->errors(),
            422
        );
    }

    if ($exception instanceof AuthenticationException) {
        return Response::error(
            __('auth.unauthenticated'),
            [],
            401
        );
    }

    if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
        return Response::error(
            __('auth.unauthorized'),
            [],
            403
        );
    }

    if ($exception instanceof NotFoundHttpException) {
        return Response::error(
            __('errors.not_found'),
            [],
            404
        );
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        return Response::error(
            __('errors.method_not_allowed'),
            [],
            405
        );
    }

    // Handling Throttle Limit Exceeded (Too Many Requests)
    if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
        return Response::error(
            __('errors.too_many_requests'),
            [],
            429
        );
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        return Response::error(
            $exception->getMessage() ?: __('errors.internal_error'),
            [],
            $exception->getStatusCode()
        );
    }

    return Response::error(
        $exception->getMessage() ?: __('errors.internal_error'),
        [],
        500
    );


}
