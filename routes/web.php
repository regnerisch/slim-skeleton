<?php

use Regnerisch\Skeleton\Controller;

$app->get('/', Controller\IndexController::class . ':index');
