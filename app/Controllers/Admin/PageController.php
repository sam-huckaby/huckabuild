<?php

namespace Huckabuild\Controllers\Admin;

use Huckabuild\Models\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return $this->container->get('view')->render($response, 'admin/pages/index.latte', [
            'pages' => $pages,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function create(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'admin/pages/create.latte', [
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        $page = new Page();
        $page->title = $data['title'];
        $page->slug = $this->createSlug($data['title']);
        $page->content = $data['content'];
        $page->save();

        return $response->withHeader('Location', '/admin/pages')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args)
    {
        $page = Page::findOrFail($args['id']);
        return $this->container->get('view')->render($response, 'admin/pages/edit.latte', [
            'page' => $page,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $page = Page::findOrFail($args['id']);
        
        $page->title = $data['title'];
        $page->content = $data['content'];
        $page->save();

        return $response->withHeader('Location', '/admin/pages')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $page = Page::findOrFail($args['id']);
        $page->delete();

        return $response->withHeader('Location', '/admin/pages')->withStatus(302);
    }

    public function setLanding(Request $request, Response $response, $args)
    {
        $page = Page::findOrFail($args['id']);
        
        // First, unset any existing landing page
        Page::where('is_landing_page', true)->update(['is_landing_page' => false]);
        
        // Set the new landing page
        $page->is_landing_page = true;
        $page->save();

        $response->getBody()->write(json_encode(['success' => true]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    private function createSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $count = 1;
        $originalSlug = $slug;
        
        while (Page::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }
} 