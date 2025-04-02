<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{

    protected function JsonResponse(bool $status, string $message, $token = null) : JsonResponse
    {
        if (!$token)
        return response()->json([
            'status' => $status,
            'message' => $message,
            ]);

        return response()->json([
            'status' => $status,
            'message' => $message,
            'token' => $token
            ]);
    }

    protected function paginatedResponse(int $total, int $filtered, string $name, $result) {
        return response()->json([
            'records_total' => $total,
            'records_filtered' => $filtered,
            $name => $result
        ]);
    }

}
