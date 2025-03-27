<?php

namespace Huckabuild\Controllers;

use Huckabuild\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserAdminController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return $this->container->get('view')->render($response, 'admin/users/index.twig', [
            'users' => $users
        ]);
    }

    public function create(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'admin/users/create.twig');
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        $user = new User();
        $user->display_name = $data['display_name'];
        $user->username = $data['username'];
        $user->password = User::generateSecurePassword();
        $user->password_reset_required = true;
        $user->save();

        // TODO: Send password to user via email

        return $response->withHeader('Location', '/admin/users')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args)
    {
        $user = User::findOrFail($args['id']);
        return $this->container->get('view')->render($response, 'admin/users/edit.twig', [
            'user' => $user
        ]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $user = User::findOrFail($args['id']);
        
        $user->display_name = $data['display_name'];
        $user->username = $data['username'];
        
        if (!empty($data['password'])) {
            $user->password = $data['password'];
            $user->password_reset_required = false;
        }
        
        $user->save();

        return $response->withHeader('Location', '/admin/users')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $user = User::findOrFail($args['id']);
        
        // Prevent deleting the last admin user
        if (User::count() <= 1) {
            throw new \Exception('Cannot delete the last user');
        }
        
        $user->delete();

        return $response->withHeader('Location', '/admin/users')->withStatus(302);
    }
} 