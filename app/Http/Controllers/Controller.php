<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    const SUCCESS = 'success';
    const WARNING = 'warning';

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function success(string $message): JsonResponse
    {
        return new JsonResponse(['type' => self::SUCCESS, 'message' => $message, 'statusCode' => Response::HTTP_OK]);
    }
}
