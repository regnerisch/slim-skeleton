<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use DI\ContainerBuilder;
use Slim\App;

class AppServiceProvider
{
    public function registerContainer()
    {
        return (new ContainerServiceProvider())(new ContainerBuilder());
    }

    public function register(App $app)
    {
        (new MiddlewareServiceProvider())($app);
        (new RouteServiceProvider())($app);
    }
}
