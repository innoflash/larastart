<?php

namespace InnoFlash\LaraStart\Traits;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionsTrait
{

    function apiExceptions(Request $request, Exception $exception, bool $rawError = false)
    {
        if ($rawError)
            return parent::render($request, $exception);
        else {
            if ($exception instanceof ModelNotFoundException) {
                $errorBody = [
                    'status' => Response::HTTP_NOT_FOUND,
                    'code' => 'MOD_04',
                    'message' => $exception->getMessage(),
                ];
            } else if ($exception instanceof NotFoundHttpException)
                $errorBody = [
                    'status' => Response::HTTP_NOT_FOUND,
                    'code' => 'RT_04',
                    'message' => 'Invalid route',
                ];
            else if ($exception instanceof InvalidArgumentException)
                $errorBody = [
                    'status' => Response::HTTP_NOT_ACCEPTABLE,
                    'code' => 'AUT_01',
                    'message' => 'Authorization code is empty.',
                ];
            else if ($exception instanceof MethodNotAllowedHttpException)
                $errorBody = [
                    'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'code' => 'MET_05',
                    'message' => $exception->getMessage(),
                ];
            else if ($exception instanceof ValidationException) {
                $messages = [];
                foreach ($exception->errors() as $key => $error) {
                    foreach ($error as $key => $err) {
                        array_push($messages, $err);
                    }
                }
                $errorBody = [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'code' => 'VAL_22',
                    'message' => implode(PHP_EOL, $messages),
                ];
            } else if ($exception instanceof BadRequestHttpException)
                $errorBody = [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'code' => 'VAL_22',
                    'message' => $exception->getMessage(),
                ];
            else if ($exception instanceof AuthorizationException)
                $errorBody = [
                    'status' => Response::HTTP_FORBIDDEN,
                    'code' => 'AUT_02',
                    'message' => $exception->getMessage(),
                ];
            else
                $errorBody = [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'code' => 'SER_00',
                    'message' => $exception->getMessage(),
                ];
            return \response()->json($errorBody, $errorBody['status']);
        }
    }
}
