<?php

namespace InnoFlash\LaraStart\Traits;

use Illuminate\Routing\ResponseFactory;

trait APIResponses
{
    /**
     * The json response for success responses.
     *
     * @param $message
     * @param  array  $data
     * @param  int  $statusCode
     *
     * @return mixed
     */
    public function successResponse($message, $data = [], int $statusCode = 200)
    {
        $responseData = [
            'success' => true,
            'message' => $message,
        ];

        if ($data) {
            $responseData['data'] = $data;
        }

        return ResponseFactory::successResponse($message, $responseData, $statusCode);
    }
}
