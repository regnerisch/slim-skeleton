<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

final class CustomErrorHandler extends ErrorHandler
{
    private $logger;

    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, LoggerInterface $logger)
    {
        parent::__construct($callableResolver, $responseFactory);
        $this->logger = $logger;
    }

    protected function logError(string $error): void
    {
        $this->logger->error($error);
    }
}
