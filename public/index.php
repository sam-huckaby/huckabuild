<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create Container
$container = new Container();

// Set up database connection
$container->set('db', function () {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection([
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../database/foundry.sqlite',
        'prefix' => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
});

// Set up Twig views
$container->set('view', function () {
    return Twig::create(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);
});

// Create App
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add middleware
$app->add(TwigMiddleware::createFromContainer($app));
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Include routes
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/admin.php';

// Run app
$app->run(); 