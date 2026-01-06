<?php
// app/Exceptions/CustomExceptionHandler.php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomExceptionHandler
{
    public function __invoke(Throwable $e, $request)
    {
        \Log::info('CustomExceptionHandler invoked', [
            'exception' => get_class($e),
            'message' => $e->getMessage()
        ]);

        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        // For web requests, return null to let Laravel handle it
        return null;
    }

    protected function handleApiException($request, Throwable $e)
    {
        // Handle NotFoundHttpException for missing models
        if ($e instanceof NotFoundHttpException) {
            $previousException = $e->getPrevious();
            if ($previousException instanceof ModelNotFoundException) {
                $modelName = class_basename($previousException->getModel());
                $ids = $previousException->getIds();
                $id = !empty($ids) ? $ids[0] : 'unknown';
                return ApiResponse::error(
                    "{$modelName} with ID {$id} not found",
                    404
                );
            }
            return ApiResponse::error('Resource not found', 404);
        }

        // Handle MethodNotAllowedHttpException
        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error(
                'HTTP method not allowed for this route',
                405
            );
        }

        // Handle ValidationException
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Handle AuthenticationException
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error('Unauthenticated', 401);
        }

        // Handle other HttpException
        if ($e instanceof HttpException) {
            return ApiResponse::error(
                $e->getMessage() ?: 'HTTP error occurred',
                $e->getStatusCode()
            );
        }

        // Fallback for all other exceptions
        \Log::error('Unhandled API Exception:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return ApiResponse::error(
            config('app.debug') ? $e->getMessage() : 'Something went wrong. Please try again later.',
            500
        );
    }
}
