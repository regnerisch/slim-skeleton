<?php

declare(strict_types=1);

use Regnerisch\Skeleton\Controller\v1\InfoController;
use Slim\Interfaces\RouteCollectorProxyInterface;

$app->group('/api', function (RouteCollectorProxyInterface $group) {
    $group->group('/v1', function (RouteCollectorProxyInterface $v1Group) {
        $v1Group->get('', InfoController::class . ':index');
    });
});
