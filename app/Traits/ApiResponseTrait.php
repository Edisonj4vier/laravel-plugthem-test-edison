<?php

namespace App\Traits;

trait ApiResponseTrait{
    /**
     * Respuesta exitosa
     */
     protected function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $code);
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse($message = 'Error', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
        ], $code);
    }
}
