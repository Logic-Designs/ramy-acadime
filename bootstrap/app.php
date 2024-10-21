<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetLocale::class);

        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, $request) {
            // Custom API exception handling
            if ($request->expectsJson()) {
                return handleApiException($exception, $request);
            }
            return null;
        });
    })->create();


function handleApiException(Throwable $exception, $request)
{
    if ($exception instanceof ValidationException) {
        return Response::error(
            __('Validation failed'),
            $exception->errors(),
            422
        );
    }

    if ($exception instanceof AuthenticationException) {
        return Response::error(
            __('Unauthenticated'),
            [],
            401
        );
    }

    if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
        return Response::error(
            __('Unauthorized access'),
            [],
            403
        );
    }

    if ($exception instanceof NotFoundHttpException) {
        return Response::error(
            __('Resource not found'),
            [],
            404
        );
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        return Response::error(
            __('Method not allowed'),
            [],
            405
        );
    }

    // Handling Throttle Limit Exceeded (Too Many Requests)
    if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
        return Response::error(
            __('Too many requests'),
            [],
            429
        );
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        return Response::error(
            $exception->getMessage() ?: __('Internal server error'),
            [],
            $exception->getStatusCode()
        );
    }

    return Response::error(
        $exception->getMessage() ?: __('Internal server error'),
        [],
        500
    );



}
