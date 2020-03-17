<?php

namespace InnoFlash\LaraStart\Mixins;

class ResponseMixin
{
    /**
     * Returns a success formatted response.
     *
     * @return \Closure
     */
    function successResponse()
    {
        return function ($message, array $data = [], int $statusCode = 200) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $message
            ], $data), $statusCode);
        };
    }
}
