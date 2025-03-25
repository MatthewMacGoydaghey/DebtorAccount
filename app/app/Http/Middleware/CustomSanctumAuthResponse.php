<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomSanctumAuthResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            $response = $next($request);
            if ($response->getStatusCode() === 401) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Токен аутентификации отсутствует или не валиден',
                ], 401);
                } return $response;
        }
    }
}