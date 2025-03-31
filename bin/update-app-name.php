<?php

$envPath = __DIR__ . '/../.env';
$projectName = basename(dirname(__DIR__));

if (!file_exists($envPath)) {
    echo "Error: .env file not found.\n";
    exit(1);
}

// Read the .env file
$envContent = file_get_contents($envPath);

// Update APP_NAME
$envContent = preg_replace(
    '/^APP_NAME=.*/m',
    'APP_NAME=' . $projectName,
    $envContent
);

// Write back to .env
if (file_put_contents($envPath, $envContent) === false) {
    echo "Error: Could not update .env file.\n";
    exit(1);
}

echo "Updated APP_NAME to: {$projectName}\n"; 