<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;

class AppServiceProvider
{
    public function registerContainer(): ContainerInterface
    {
        return (new ContainerServiceProvider())(new ContainerBuilder());
    }

    public function register(App $app): void
    {
        (new EventServiceProvider())($app);
        (new MiddlewareServiceProvider())($app);
        (new RouteServiceProvider())($app);
    }
}
