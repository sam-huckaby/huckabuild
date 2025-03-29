<?php

namespace Huckabuild\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class CsrfMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Generate CSRF token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // For POST/PUT/DELETE requests, validate CSRF token
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->getHeaderLine('X-CSRF-Token');
            $formData = $request->getParsedBody();
            $formToken = $formData['csrf_token'] ?? null;

            if ((empty($token) && empty($formToken)) || 
                ($token !== $_SESSION['csrf_token'] && $formToken !== $_SESSION['csrf_token'])) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => 'Invalid CSRF token']));
                return $response
                    ->withStatus(403)
                    ->withHeader('Content-Type', 'application/json');
            }
        }

        return $handler->handle($request);
    }
} 