<?php


namespace Regnerisch\Skeleton\Services;


use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Twig\Environment;

class Renderer
{
    private $twig;
    private $logger;

    public function __construct(Environment $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function render(ResponseInterface $response, string $name, array $context = [])
    {
        $this->logger->log(LogLevel::DEBUG, __METHOD__, ['name' => $name, 'context' => $context]);

        $response->getBody()->write(
            $this->twig->render($name, $context)
        );

        return $response;
    }

    public function renderFile(string $name, array $context = []) {
        $this->logger->log(LogLevel::DEBUG, __METHOD__, ['name' => $name, 'context' => $context]);

        return $this->twig->render($name, $context);
    }
}
