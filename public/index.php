<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Loader\FilesystemLoader;
use Psr\Container\ContainerInterface;
use Huckabuild\Middleware\ViewAuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Start session
session_start();

// Configure session security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize database connection
require __DIR__ . '/../app/database.php';

// Create Container
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Twig::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../resources/views');
        return new Twig($loader);
    },
    'view' => function (ContainerInterface $c) {
        return $c->get(Twig::class);
    }
]);
$container = $containerBuilder->build();

// Create App
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add middleware
$app->add(TwigMiddleware::createFromContainer($app));
$app->add(new ViewAuthMiddleware($container->get('view')));
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Include routes
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/admin.php';

// Run app
$app->run(); 