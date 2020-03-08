<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use App\CustomErrorHandler;
use Middlewares\Cors;
use Middlewares\TrailingSlash;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Noodlehaus\ConfigInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

class MiddlewareServiceProvider
{
    public function __invoke(App $app)
    {
        // Trailing Slash
        $app->addMiddleware((new TrailingSlash())->redirect($app->getResponseFactory()));

        // CORS
        $corsSettings = $this->configureCORS($app->getContainer());
        $analyzer = Analyzer::instance($corsSettings);
        $app->addMiddleware(new Cors($analyzer));

        // Error Handler
        $isDev = 'development' === $_ENV['APP_ENV'];
        $errorMiddleware = $app->addErrorMiddleware($isDev, true, true);
        $errorHandler = new CustomErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $app->getContainer()->get(LoggerInterface::class));
        $errorHandler->forceContentType('application/json');
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    private function configureCORS(ContainerInterface $container): Settings
    {
        $config = $container->get(ConfigInterface::class);

        $corsSettings = new Settings();

        if (!$config->has('cors.scheme') || !$config->has('cors.host') || !$config->has('cors.post')) {
            $corsSettings->disableCheckHost();
        } else {
            $corsSettings->enableCheckHost();
            $corsSettings->setServerOrigin($config->get('cors.scheme'), $config->get('cors.host'), $config->get('cors.post'));
        }

        if (in_array('*', $config->get('cors.allowedOrigins', []), true)) {
            $corsSettings->enableAllOriginsAllowed();
        } else {
            $corsSettings->setAllowedOrigins($config->get('cors.allowedOrigins', []));
        }

        if (in_array('*', $config->get('cors.allowedMethods', []), true)) {
            $corsSettings->enableAllMethodsAllowed();
        } else {
            $corsSettings->setAllowedMethods($config->get('cors.allowedMethods', []));
        }

        if (in_array('*', $config->get('cors.allowedHeaders', []), true)) {
            $corsSettings->enableAllHeadersAllowed();
        } else {
            $corsSettings->setAllowedHeaders($config->get('cors.allowedHeaders', []));
        }

        $corsSettings->setExposedHeaders($config->get('cors.exposedHeaders', []));

        if ($config->has('cors.credentialsSupported') && $config->get('cors.credentialsSupported', false)) {
            $corsSettings->setCredentialsSupported();
        } else {
            $corsSettings->setCredentialsNotSupported();
        }

        if ($config->has('cors.preFlightCacheMaxAge') && 0 <= $config->get('cors.preFlightCacheMaxAge', 0)) {
            $corsSettings->setPreFlightCacheMaxAge($config->get('cors.preFlightCacheMaxAge', 0));
        }

        if ($config->has('cors.preFlightAddAllowedMethodsToResponse') && $config->get('cors.preFlightAddAllowedMethodsToResponse', false)) {
            $corsSettings->enableAddAllowedMethodsToPreFlightResponse();
        } else {
            $corsSettings->disableAddAllowedMethodsToPreFlightResponse();
        }

        if ($config->has('cors.preFlightAddAllowedHeadersToResponse') && $config->get('cors.preFlightAddAllowedHeadersToResponse', false)) {
            $corsSettings->enableAddAllowedHeadersToPreFlightResponse();
        } else {
            $corsSettings->disableAddAllowedHeadersToPreFlightResponse();
        }

        if ($container->has(LoggerInterface::class)) {
            $corsSettings->setLogger($container->get(LoggerInterface::class));
        }

        return $corsSettings;
    }
}
