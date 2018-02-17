<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse([
            "status" => 'error',
            "message" => "Validation errors",
            "errors" => $errors
        ], 422);
    }
}
