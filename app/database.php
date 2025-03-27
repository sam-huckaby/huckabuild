<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'sqlite',
    'database'  => __DIR__ . '/../' . ($_ENV['DB_DATABASE'] ?? 'database/huckabuild.sqlite'),
    'prefix'    => $_ENV['DB_PREFIX'] ?? '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent(); 