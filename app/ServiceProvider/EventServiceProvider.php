<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use App\Event\ExampleEvent;
use App\Listener\ExampleListener;
use League\Event\EmitterInterface;
use Slim\App;

class EventServiceProvider
{
    public function __invoke(App $app)
    {
        $container = $app->getContainer();
        $emitter = $container->get(EmitterInterface::class);

        $emitter->addListener(ExampleEvent::class, new ExampleListener());
    }
}
