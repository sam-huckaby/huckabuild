<?php

namespace Huckabuild\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MethodOverrideMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parsedBody = $request->getParsedBody();
        
        if (isset($parsedBody['_METHOD'])) {
            $method = strtoupper($parsedBody['_METHOD']);
            if (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
                $request = $request->withMethod($method);
            }
        }
        
        return $handler->handle($request);
    }
} 