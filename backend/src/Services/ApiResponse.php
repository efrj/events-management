<?php

namespace App\Services;

class ApiResponse
{
    public static function send(mixed $data, int $statusCode = 200, string $message = ''): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);

        $response = [
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ];

        echo json_encode($response);
        exit;
    }

    public static function error(string $message, int $statusCode = 400): void
    {
        self::send(null, $statusCode, $message);
    }
}
