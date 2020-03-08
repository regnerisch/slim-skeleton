<?php

declare(strict_types=1);

namespace App\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InfoController
{
    public function index(Request $request, Response $response): Response
    {
        return new JsonResponse([
            'version' => PHP_VERSION,
        ], 200);
    }
}
