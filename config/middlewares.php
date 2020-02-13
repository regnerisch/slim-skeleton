<?php

declare(strict_types=1);

use Middlewares\Cors;
use Middlewares\TrailingSlash;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Regnerisch\Skeleton\Services\App\ErrorHandler;
use Slim\App;

return static function (App $app, ContainerInterface $container) {
    // Trailing Slash
    $app->addMiddleware((new TrailingSlash())->redirect($app->getResponseFactory()));

    // CORS
    $corsSettings = configureCORS($container);
    $analyzer = Analyzer::instance($corsSettings);
    $app->addMiddleware(new Cors($analyzer));

    // Error Handler
    $isDev = 'development' === $_ENV['ENVIRONMENT'];
    $errorMiddleware = $app->addErrorMiddleware($isDev, true, true);
    $errorHandler = new ErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $container->get(LoggerInterface::class));
    $errorMiddleware->setDefaultErrorHandler($errorHandler);
};

function configureCORS(ContainerInterface $container): Settings
{
    $settings = $container->get('settings')['cors'];

    $corsSettings = new Settings();

    if (empty($settings['scheme']) || empty($settings['host']) || empty($settings['port'])) {
        $corsSettings->disableCheckHost();
    } else {
        $corsSettings->enableCheckHost();
        $corsSettings->setServerOrigin($settings['scheme'], $settings['host'], $settings['port']);
    }

    if (in_array('*', $settings['allowedOrigins'], true)) {
        $corsSettings->enableAllOriginsAllowed();
    } else {
        $corsSettings->setAllowedOrigins($settings['allowedOrigins'] ?? []);
    }

    if (in_array('*', $settings['allowedMethods'], true)) {
        $corsSettings->enableAllMethodsAllowed();
    } else {
        $corsSettings->setAllowedMethods($settings['allowedMethods'] ?? []);
    }

    if (in_array('*', $settings['allowedHeaders'], true)) {
        $corsSettings->enableAllHeadersAllowed();
    } else {
        $corsSettings->setAllowedHeaders($settings['allowedHeaders'] ?? []);
    }

    $corsSettings->setExposedHeaders($settings['exposedHeaders'] ?? []);

    if (array_key_exists('credentialsSupported', $settings) && $settings['credentialsSupported']) {
        $corsSettings->setCredentialsSupported();
    } else {
        $corsSettings->setCredentialsNotSupported();
    }

    if (array_key_exists('preFlightCacheMaxAge', $settings) && 0 <= $settings['preFlightCacheMaxAge']) {
        $corsSettings->setPreFlightCacheMaxAge($settings['preFlightCacheMaxAge']);
    }

    if (array_key_exists('preFlightAddAllowedMethodsToResponse', $settings) && $settings['preFlightAddAllowedMethodsToResponse']) {
        $corsSettings->enableAddAllowedMethodsToPreFlightResponse();
    } else {
        $corsSettings->disableAddAllowedMethodsToPreFlightResponse();
    }

    if (array_key_exists('preFlightAddAllowedHeadersToResponse', $settings) && $settings['preFlightAddAllowedHeadersToResponse']) {
        $corsSettings->enableAddAllowedHeadersToPreFlightResponse();
    } else {
        $corsSettings->disableAddAllowedHeadersToPreFlightResponse();
    }

    if ($container->has(LoggerInterface::class)) {
        $corsSettings->setLogger($container->get(LoggerInterface::class));
    }

    return $corsSettings;
}
