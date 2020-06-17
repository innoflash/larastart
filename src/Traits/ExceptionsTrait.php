<?php

namespace InnoFlash\LaraStart\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

trait ExceptionsTrait
{
    /**
     * Class - error message key maps.
     * @var array
     */
    private array $errorMessages = [
        NotFoundHttpException::class  => 'Invalid route',
        RouteNotFoundException::class => 'Authorization headers missing',
    ];

    /**
     * Class - error code key maps.
     * @var array
     */
    private array $errorCodes = [
        ValidationException::class    => 422,
        RouteNotFoundException::class => 401,
    ];

    public function apiExceptions(Request $request, $exception, bool $rawError = false)
    {
        if ($rawError) {
            return parent::render($request, $exception);
        }

        if (in_array('getMessage', get_class_methods($exception)) && strlen($exception->getMessage())) {
            $message = $exception->getMessage();
        } else {
            $message = 'Unknown server error.';
        }
        $message = $this->errorMessages[get_class($exception)] ?? $message;

        $statusCode = $this->errorCodes[get_class($exception)] ?? 500;

        if (in_array('getStatusCode', get_class_methods($exception))) {
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof ValidationException) {
            $errors = collect($exception->errors())->flatten()->toArray();
            $message = implode(PHP_EOL, $errors);
        }

        return \response()->json([
            'exception'  => get_class($exception),
            'statusCode' => $statusCode,
            'message'    => $message,
        ], $statusCode);
    }
}
