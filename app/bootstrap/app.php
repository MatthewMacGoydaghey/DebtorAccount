<?php

use App\Http\Middleware\AcceptHeaderMiddleware;
use App\Http\Middleware\CustomSanctumAuthResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(AcceptHeaderMiddleware::class);
        $middleware->append(CustomSanctumAuthResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $e, $request) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], $e->getStatusCode());
        });
        $exceptions->render(function (ValidationException $e, $request) {
                return response()->json([
                    'status' => false,
                    'message' => $e->validator->errors(),
                ], 400);
        });
        $exceptions->render(function (Error $e, $request) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
    });
    })->create();