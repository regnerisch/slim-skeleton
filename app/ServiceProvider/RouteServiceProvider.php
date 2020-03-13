<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Slim\App;

class RouteServiceProvider
{
    public function __invoke(App $app)
    {
        $routes = scandir(ABS_PATH . '/routes');
        foreach ($routes as $route) {
            if (false !== stripos($route, '.php')) {
                require_once ABS_PATH . '/routes/' . $route;
            }
        }
    }
}
