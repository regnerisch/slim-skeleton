<?php


namespace Regnerisch\Skeleton\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Regnerisch\Skeleton\Services\Renderer;

class IndexController
{
    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function index(Request $request, Response $response)
    {
        return $this->renderer->render($response, 'index.html.twig');
    }
}
