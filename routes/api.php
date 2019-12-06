<?php

use Slim\Interfaces\RouteCollectorProxyInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->group('/api', function (RouteCollectorProxyInterface $group) {
    $group->group('/v1', function (RouteCollectorProxyInterface $v1Group) {
        $v1Group->get('/version', function (Request $request, Response $response) {
            $response->getBody()->write('PHP VERSION: ' . PHP_VERSION);
            return $response;
        });
    });
});
