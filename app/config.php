<?php

use DI\ContainerBuilder;
use Huckabuild\Services\LatteService;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LatteService::class => function () {
            return new LatteService();
        }
    ]);
}; 