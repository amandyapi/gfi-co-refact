<?php

// src/Helpers/JsonHelper.php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonHelper 
{

    public function createJsonResponse($data, int $statusCode = 200, array $headers = [], array $groups = [])
    {
        $defaultHeaders = ['Access-Control-Allow-Origin' => '*', 'Referrer-Policy' => 'no-referrer'];
        $headers = array_merge($defaultHeaders, $headers);

        return [$data, $statusCode, [], ['groups' => $groups]];
    }
}