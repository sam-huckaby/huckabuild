<?php

use Huckabuild\Controllers\PageController;
use Huckabuild\Controllers\AuthController;
use Huckabuild\Controllers\Admin\SettingsController;
use Huckabuild\Middleware\GuestMiddleware;
use Slim\Routing\RouteCollectorProxy;

// Public routes
$app->get('/', [PageController::class, 'home']);

// Auth routes
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->get('', [AuthController::class, 'showLoginForm']);
    $group->post('', [AuthController::class, 'login']);
})->add(new GuestMiddleware());

$app->get('/logout', [AuthController::class, 'logout']);

// Admin routes
$app->group('/admin', function (RouteCollectorProxy $group) {
    $group->get('/settings', [SettingsController::class, 'index']);
    $group->post('/settings', [SettingsController::class, 'update']);
});

// Custom pages route - must be last and exclude reserved paths
$app->get('/{slug}', [PageController::class, 'show'])
    ->setName('page.show')
    ->setArgument('slug', '[a-zA-Z0-9-]+')
    ->add(function ($request, $handler) {
        $slug = $request->getAttribute('slug');
        // List of reserved paths that should not be handled by this route
        $reservedPaths = ['admin', 'login', 'logout', 'docs', 'api'];
        if (in_array($slug, $reservedPaths)) {
            throw new \Exception('Page not found');
        }
        return $handler->handle($request);
    }); 