<?php

namespace InnoFlash\LaraStart\Traits;

trait APIResponses
{
    function successResponse($message, array $data = [])
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message
        ], $data));
    }
}
