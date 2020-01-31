<?php

declare(strict_types=1);

use Regnerisch\Skeleton\Controller;

$app->get('/', Controller\IndexController::class . ':index');
