<?php

use Huckabuild\Controllers\AdminController;
use Huckabuild\Controllers\PageAdminController;
use Huckabuild\Controllers\MenuAdminController;
use Huckabuild\Controllers\MediaAdminController;
use Huckabuild\Controllers\UserAdminController;
use Huckabuild\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

// Admin routes
$app->group('/admin', function (RouteCollectorProxy $group) {
    // Dashboard
    $group->get('', [AdminController::class, 'dashboard']);
    
    // Pages management
    $group->group('/pages', function (RouteCollectorProxy $group) {
        $group->get('', [PageAdminController::class, 'index']);
        $group->get('/create', [PageAdminController::class, 'create']);
        $group->post('', [PageAdminController::class, 'store']);
        $group->get('/{id}', [PageAdminController::class, 'edit']);
        $group->put('/{id}', [PageAdminController::class, 'update']);
        $group->delete('/{id}', [PageAdminController::class, 'delete']);
        $group->post('/{id}/set-landing', [PageAdminController::class, 'setLanding']);
    });
    
    // Menus management
    $group->group('/menus', function (RouteCollectorProxy $group) {
        $group->get('', [MenuAdminController::class, 'index']);
        $group->get('/create', [MenuAdminController::class, 'create']);
        $group->post('', [MenuAdminController::class, 'store']);
        $group->get('/{id}', [MenuAdminController::class, 'edit']);
        $group->put('/{id}', [MenuAdminController::class, 'update']);
        $group->delete('/{id}', [MenuAdminController::class, 'delete']);
        $group->post('/{id}/activate', [MenuAdminController::class, 'activate']);
        $group->post('/items/reorder', [MenuAdminController::class, 'reorderItems']);
    });
    
    // Media management
    $group->group('/media', function (RouteCollectorProxy $group) {
        $group->get('', [MediaAdminController::class, 'index']);
        $group->get('/create', [MediaAdminController::class, 'create']);
        $group->post('', [MediaAdminController::class, 'store']);
        $group->get('/{id}', [MediaAdminController::class, 'edit']);
        $group->put('/{id}', [MediaAdminController::class, 'update']);
        $group->delete('/{id}', [MediaAdminController::class, 'delete']);
    });
    
    // Users management
    $group->group('/users', function (RouteCollectorProxy $group) {
        $group->get('', [UserAdminController::class, 'index']);
        $group->get('/create', [UserAdminController::class, 'create']);
        $group->post('', [UserAdminController::class, 'store']);
        $group->get('/{id}', [UserAdminController::class, 'edit']);
        $group->put('/{id}', [UserAdminController::class, 'update']);
        $group->delete('/{id}', [UserAdminController::class, 'delete']);
    });
    
    // Logout
    $group->get('/logout', [AuthController::class, 'logout']);
})->add(new AuthMiddleware()); 