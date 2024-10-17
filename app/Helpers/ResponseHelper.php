<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($message, $data = [], $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => __($message),
            'data' => $data
        ], $status);
    }

    public static function error($message, $errors = [], $status = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => __($message),
            'errors' => $errors
        ], $status);
    }
}
