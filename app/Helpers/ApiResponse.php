<?php

namespace App\Helpers;

class ApiResponse
{
    public static function response($success, $message, $data = null, $error = null, $code = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], $code);
    }

    public static function serverError($error = 'Something went wrong.', $code = 500)
    {
        return response()->json([
            'success' => false,
            'error' => $error,
        ], $code);
    }
}
