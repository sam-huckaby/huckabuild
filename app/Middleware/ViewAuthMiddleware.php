<?php

namespace Huckabuild\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ViewAuthMiddleware
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $this->view->getEnvironment()->addGlobal('auth', [
            'isLoggedIn' => isset($_SESSION['user_id']),
            'username' => $_SESSION['username'] ?? null,
            'displayName' => $_SESSION['display_name'] ?? null
        ]);

        return $handler->handle($request);
    }
} 