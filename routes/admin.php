<?php

use Huckabuild\Controllers\Admin\DashboardController;
use Huckabuild\Controllers\Admin\PageController;
use Huckabuild\Controllers\Admin\MenuController;
use Huckabuild\Controllers\Admin\MediaController;
use Huckabuild\Controllers\Admin\UserController;
use Huckabuild\Controllers\Admin\AuthController;
use Huckabuild\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

// Admin routes
$app->group('/admin', function (RouteCollectorProxy $group) {
    // Dashboard
    $group->get('', [DashboardController::class, 'dashboard']);
    
    // Pages management
    $group->group('/pages', function (RouteCollectorProxy $group) {
        $group->get('', [PageController::class, 'index']);
        $group->get('/create', [PageController::class, 'create']);
        $group->post('', [PageController::class, 'store']);
        $group->get('/{id}', [PageController::class, 'edit']);
        $group->put('/{id}', [PageController::class, 'update']);
        $group->delete('/{id}', [PageController::class, 'delete']);
        $group->post('/{id}/set-landing', [PageController::class, 'setLanding']);
    });
    
    // Menus management
    $group->group('/menus', function (RouteCollectorProxy $group) {
        $group->get('', [MenuController::class, 'index']);
        $group->get('/create', [MenuController::class, 'create']);
        $group->post('', [MenuController::class, 'store']);
        $group->get('/{id}', [MenuController::class, 'edit']);
        $group->put('/{id}', [MenuController::class, 'update']);
        $group->delete('/{id}', [MenuController::class, 'delete']);
        $group->post('/{id}/activate', [MenuController::class, 'activate']);
        $group->post('/items/reorder', [MenuController::class, 'reorderItems']);
    });
    
    // Media management
    $group->group('/media', function (RouteCollectorProxy $group) {
        $group->get('', [MediaController::class, 'index']);
        $group->get('/create', [MediaController::class, 'create']);
        $group->post('', [MediaController::class, 'store']);
        $group->get('/{id}', [MediaController::class, 'edit']);
        $group->put('/{id}', [MediaController::class, 'update']);
        $group->delete('/{id}', [MediaController::class, 'delete']);
    });
    
    // Users management
    $group->group('/users', function (RouteCollectorProxy $group) {
        $group->get('', [UserController::class, 'index']);
        $group->get('/create', [UserController::class, 'create']);
        $group->post('', [UserController::class, 'store']);
        $group->get('/{id}', [UserController::class, 'edit']);
        $group->put('/{id}', [UserController::class, 'update']);
        $group->delete('/{id}', [UserController::class, 'delete']);
    });
    
    // Logout
    $group->get('/logout', [AuthController::class, 'logout']);
})->add(new AuthMiddleware()); 