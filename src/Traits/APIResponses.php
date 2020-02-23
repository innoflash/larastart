<?php

namespace InnoFlash\LaraStart\Traits;

trait APIResponses
{
    function successResponse($message, array $data = [], int $statusCode = 200)
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message
        ], $data), $statusCode);
    }
}
