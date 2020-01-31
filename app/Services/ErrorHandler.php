<?php

declare(strict_types=1);

namespace Regnerisch\Skeleton\Services;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Slim\Interfaces\CallableResolverInterface;

class ErrorHandler extends \Slim\Handlers\ErrorHandler
{
    private $logger;

    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, LoggerInterface $logger)
    {
        parent::__construct($callableResolver, $responseFactory);
        $this->logger = $logger;
    }

    protected function logError(string $error): void
    {
        parent::logError($error);
        $this->logger->log(LogLevel::ERROR, $error);
    }
}
