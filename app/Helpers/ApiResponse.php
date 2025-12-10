<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    /**
     * Success response with optional data and message
     */
    public static function success($data = null, string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Error response with message and optional errors
     */
    public static function error(string $message = 'Error', $errors = null, int $status = 400)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * Resource response (Eloquent / Resource)
     */
    public static function resource($resource, string $message = 'Success', array $extra = [], int $status = 200)
    {
        if ($resource instanceof \Illuminate\Http\Resources\Json\JsonResource) {
            return response()->json(array_merge([
                'status'  => 'success',
                'message' => $message,
                'data'    => $resource->resolve(), // resolved resource array
            ], $extra), $status);
        }

        return self::success($resource, $message, $status);
    }
    public static function collection($resource, string $message = 'Success', int $status = 200, $meta = [])
    {
        if ($resource instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            return $resource->additional(array_merge([
                'status' => 'success',
                'message' => $message,
            ], $meta))
                ->response()
                ->setStatusCode($status);
        }

        return self::success($resource, $message, $status);
    }
}
