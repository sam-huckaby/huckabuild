<?php

namespace Huckabuild\Controllers;

use Huckabuild\Models\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageAdminController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return $this->container->get('view')->render($response, 'admin/pages/index.twig', [
            'pages' => $pages
        ]);
    }

    public function create(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'admin/pages/create.twig');
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
        return $this->container->get('view')->render($response, 'admin/pages/edit.twig', [
            'page' => $page
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