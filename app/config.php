<?php

use DI\ContainerBuilder;
use Slim\Views\Twig;
use Twig\Loader\FilesystemLoader;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Twig::class => function () {
            $loader = new FilesystemLoader(__DIR__ . '/../templates');
            return new Twig($loader);
        }
    ]);
}; 