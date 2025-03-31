<?php

namespace Huckabuild\Controllers\Admin;

use Huckabuild\Models\Page;
use Huckabuild\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

class DashboardController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function dashboard(Request $request, Response $response)
    {
        $user = User::find($_SESSION['user_id']);
        $pageCount = Page::count();

        return $this->container->get('view')->render($response, 'admin/dashboard.latte', [
            'user' => $user,
            'pageCount' => $pageCount,
            'lastLogin' => $user->last_login
        ]);
    }
} 