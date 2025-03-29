<?php

namespace Huckabuild\Controllers\Admin;

use Huckabuild\Models\Setting;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SettingsController
{
    private $container;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        return $this->container->get('view')->render($response, 'admin/settings/index.latte', [
            'settings' => $settings,
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function update(Request $request, Response $response)
    {
        $settings = $request->getParsedBody();
        
        foreach ($settings as $key => $value) {
            if ($key !== 'csrf_token') { // Skip CSRF token
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        // If session key was changed, invalidate all sessions
        if (isset($settings['session_key'])) {
            // You might want to implement session invalidation logic here
            // For example, by updating a timestamp in the database that all sessions check against
        }

        return $response->withHeader('Location', '/admin/settings')->withStatus(302);
    }
} 