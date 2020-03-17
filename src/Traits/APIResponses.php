<?php

namespace InnoFlash\LaraStart\Traits;

use Illuminate\Routing\ResponseFactory;

trait APIResponses
{
    function successResponse($message, array $data = [], int $statusCode = 200)
    {
        return ResponseFactory::successResponse($message, array_merge([
            'success' => true,
            'message' => $message
        ], $data), $statusCode);
    }
}
