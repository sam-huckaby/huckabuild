<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Loader\FilesystemLoader;
use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

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

// Set up database connection
$container->set('db', function () {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection([
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../database/huckabuild.sqlite',
        'prefix' => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
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