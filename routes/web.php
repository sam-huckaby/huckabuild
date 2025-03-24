<?php

use Foundry\Controllers\PageController;
use Foundry\Controllers\AuthController;
use Foundry\Middleware\GuestMiddleware;
use Slim\Routing\RouteCollectorProxy;

// Public routes
$app->get('/', [PageController::class, 'home']);
$app->get('/page/{slug}', [PageController::class, 'show']);

// Auth routes
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->get('', [AuthController::class, 'showLoginForm']);
    $group->post('', [AuthController::class, 'login']);
})->add(new GuestMiddleware());

$app->get('/logout', [AuthController::class, 'logout']); 