<?php

namespace Huckabuild\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Huckabuild\Services\LatteService;
use Huckabuild\Models\Page;

class PageController
{
    private LatteService $view;

    public function __construct(LatteService $view)
    {
        $this->view = $view;
    }

    public function home(Request $request, Response $response): Response
    {
        $this->view->setRequest($request);
        return $this->view->render($response, 'pages/home');
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $this->view->setRequest($request);
        $slug = $args['slug'];
        $page = Page::where('slug', $slug)->firstOrFail();
        
        return $this->view->render($response, 'pages/show', [
            'page' => $page,
            'page_title' => $page->title
        ]);
    }
} 