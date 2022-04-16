<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class AbstractApiController extends \Illuminate\Routing\Controller
{
    const STATUS_OK = 'OK';
    const STATUS_KO = 'KO';

    /**
     * @param \Illuminate\Support\MessageBag|array $messages
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getValidationErrorResponse(\Illuminate\Support\MessageBag|array $messages) :  \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => static::STATUS_KO,
            'message' => 'Validation Error',
            'errors' => $messages
        ], Response::HTTP_BAD_REQUEST);
    }

    protected function getValidResponse(array $data) :  \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => static::STATUS_OK,
            'data' => $data
        ], Response::HTTP_OK);
    }
}
