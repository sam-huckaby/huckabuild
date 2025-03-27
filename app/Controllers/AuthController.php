<?php

namespace Huckabuild\Controllers;

use Huckabuild\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function showLoginForm(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'auth/login.latte');
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = User::where('username', $username)->first();

        if (!$user || !$user->verifyPassword($password)) {
            return $this->container->get('view')->render($response, 'auth/login.latte', [
                'error' => 'Invalid username or password'
            ]);
        }

        // Update last login time
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // Set session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['display_name'] = $user->display_name;

        // Check if password reset is required
        if ($user->password_reset_required) {
            return $response->withHeader('Location', '/admin/reset-password')->withStatus(302);
        }

        return $response->withHeader('Location', '/admin')->withStatus(302);
    }

    public function logout(Request $request, Response $response)
    {
        session_destroy();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
} 