<?php

declare(strict_types=1);

use App\ServiceProvider\AppServiceProvider;
use DI\Bridge\Slim\Bridge;
use Laminas\Diactoros\ServerRequestFactory;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

define('ABS_PATH', __DIR__ . '/..');

$dotenv = new Dotenv();
$dotenv->load(ABS_PATH . '/.env');

$appServiceProvider = new AppServiceProvider();
$container = $appServiceProvider->registerContainer();

$app = Bridge::create($container);

$app->addRoutingMiddleware();

$appServiceProvider->register($app);

$request = ServerRequestFactory::fromGlobals();
$app->run($request);
