<?php

require __DIR__ . '/vendor/autoload.php';

use Regnerisch\PhpCsFixerConfig\Php72Config;

$config = new Php72Config();

$config->getFinder()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/routes',
    ]);

return $config;
