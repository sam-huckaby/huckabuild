<?php

namespace Huckabuild\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PageController
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function home(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'pages/home.twig');
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $slug = $args['slug'];
        return $this->view->render($response, 'pages/show.twig', [
            'slug' => $slug
        ]);
    }
} 