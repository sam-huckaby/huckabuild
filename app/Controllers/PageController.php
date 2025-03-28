<?php

namespace Huckabuild\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Huckabuild\Services\LatteService;
use Huckabuild\Models\Page;
use Huckabuild\Models\Menu;
use Huckabuild\Models\MenuItem;

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
        
        // Load active menu items
        $activeMenu = Menu::where('is_active', true)->first();
        $menuItems = $activeMenu ? MenuItem::where('menu_id', $activeMenu->id)
            ->orderBy('order_index')
            ->get()
            ->map(function($item) {
                $item->url = $item->external_url ?? ($item->page ? '/' . $item->page->slug : '#');
                return $item;
            }) : [];

        return $this->view->render($response, 'pages/home', [
            'menu_items' => $menuItems
        ]);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $this->view->setRequest($request);
        $slug = $args['slug'];
        $page = Page::where('slug', $slug)->firstOrFail();
        
        // Load active menu items
        $activeMenu = Menu::where('is_active', true)->first();
        $menuItems = $activeMenu ? MenuItem::where('menu_id', $activeMenu->id)
            ->orderBy('order_index')
            ->get()
            ->map(function($item) {
                $item->url = $item->external_url ?? ($item->page ? '/' . $item->page->slug : '#');
                return $item;
            }) : [];
        
        return $this->view->render($response, 'pages/show', [
            'page' => $page,
            'page_title' => $page->title,
            'menu_items' => $menuItems
        ]);
    }
} 