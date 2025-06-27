<?php

namespace App\Helpers;

class ApiResponse {

    public static function success($data = null, string $message = 'Success', int $code = 200) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error(string $message = 'Error', int $code = 500, $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

}