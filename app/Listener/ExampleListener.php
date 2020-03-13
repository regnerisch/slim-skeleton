<?php

declare(strict_types=1);

namespace App\Listener;

use League\Event\AbstractListener;
use League\Event\EventInterface;

class ExampleListener extends AbstractListener
{
    public function handle(EventInterface $event)
    {
    }
}
