<?php

namespace Huckabuild\Controllers;

use Huckabuild\Models\Menu;
use Huckabuild\Models\MenuItem;
use Huckabuild\Models\Page;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MenuAdminController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $menus = Menu::with('items')->get();
        return $this->container->get('view')->render($response, 'admin/menus/index.latte', [
            'menus' => $menus,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function create(Request $request, Response $response)
    {
        $pages = Page::all();
        return $this->container->get('view')->render($response, 'admin/menus/create.latte', [
            'pages' => $pages,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        $menu = new Menu();
        $menu->name = $data['name'];
        $menu->save();

        if (isset($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                $menuItem = new MenuItem();
                $menuItem->menu_id = $menu->id;
                $menuItem->title = $item['title'];
                $menuItem->page_id = $item['page_id'] ?? null;
                $menuItem->external_url = $item['external_url'] ?? null;
                $menuItem->order_index = $index;
                $menuItem->save();
            }
        }

        return $response->withHeader('Location', '/admin/menus')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args)
    {
        $menu = Menu::with('items')->findOrFail($args['id']);
        $pages = Page::all();
        
        return $this->container->get('view')->render($response, 'admin/menus/edit.latte', [
            'menu' => $menu,
            'pages' => $pages,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $menu = Menu::findOrFail($args['id']);
        
        $menu->name = $data['name'];
        $menu->save();

        // Delete existing items
        MenuItem::where('menu_id', $menu->id)->delete();

        // Add new items
        if (isset($data['items'])) {
            foreach ($data['items'] as $index => $item) {
                $menuItem = new MenuItem();
                $menuItem->menu_id = $menu->id;
                $menuItem->title = $item['title'];
                $menuItem->page_id = $item['page_id'] ?? null;
                $menuItem->external_url = $item['external_url'] ?? null;
                $menuItem->order_index = $index;
                $menuItem->save();
            }
        }

        return $response->withHeader('Location', '/admin/menus')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $menu = Menu::findOrFail($args['id']);
        $menu->delete();

        return $response->withHeader('Location', '/admin/menus')->withStatus(302);
    }

    public function activate(Request $request, Response $response, $args)
    {
        // Deactivate all menus
        Menu::where('is_active', true)->update(['is_active' => false]);

        // Activate selected menu
        $menu = Menu::findOrFail($args['id']);
        $menu->is_active = true;
        $menu->save();

        return $response->withHeader('Location', '/admin/menus')->withStatus(302);
    }

    public function reorderItems(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        foreach ($data['items'] as $index => $itemId) {
            MenuItem::where('id', $itemId)->update(['order_index' => $index]);
        }

        return $response->withStatus(200)->withJson(['success' => true]);
    }
} 