<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class AbstractApiController extends \Illuminate\Routing\Controller
{
    const STATUS_OK = 'OK';
    const STATUS_KO = 'KO';


    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getValidResponse(array $data) :  \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => static::STATUS_OK,
            'data' => $data
        ], Response::HTTP_OK);
    }
}
