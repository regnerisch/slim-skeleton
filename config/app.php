<?php

declare(strict_types=1);

return [
    'environment' => $_ENV['APP_ENV'],
    'logger' => [
        'name' => 'Monolog',
        'path' => ABS_PATH . '/var/logs/logs.log',
        'level' => (int) $_ENV['LOG_LEVEL'],
    ],
];
