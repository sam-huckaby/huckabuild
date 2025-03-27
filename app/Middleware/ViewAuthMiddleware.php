<?php

namespace Huckabuild\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Huckabuild\Services\LatteService;

class ViewAuthMiddleware
{
    private LatteService $view;

    public function __construct(LatteService $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Set the request in the view service
        $this->view->setRequest($request);

        // Store auth data in the request attributes
        $request = $request->withAttribute('auth', (object)[
            'isLoggedIn' => isset($_SESSION['user_id']),
            'username' => $_SESSION['username'] ?? null,
            'displayName' => $_SESSION['display_name'] ?? null
        ]);

        return $handler->handle($request);
    }
} 