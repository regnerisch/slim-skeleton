<?php

declare(strict_types=1);

use App\Controller\InfoController;
use Slim\Interfaces\RouteCollectorProxyInterface;

$app->group('/v1', function (RouteCollectorProxyInterface $v1Group) {
    $v1Group->get('', [InfoController::class, 'index']);
});
