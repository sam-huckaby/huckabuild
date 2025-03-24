<?php

namespace Foundry\Controllers;

use Foundry\Models\Page;
use Foundry\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController
{
    public function dashboard(Request $request, Response $response)
    {
        $user = User::find($_SESSION['user_id']);
        $pageCount = Page::count();

        return $this->container->get('view')->render($response, 'admin/dashboard.twig', [
            'user' => $user,
            'pageCount' => $pageCount,
            'lastLogin' => $user->last_login
        ]);
    }
} 