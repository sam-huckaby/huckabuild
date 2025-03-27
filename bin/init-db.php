<?php

$dbPath = __DIR__ . '/../database/huckabuild.sqlite';
$schemaPath = __DIR__ . '/../database/schema.sql';

// Create database directory if it doesn't exist
if (!is_dir(__DIR__ . '/../database')) {
    mkdir(__DIR__ . '/../database', 0755, true);
}

// Initialize database if it doesn't exist
if (!file_exists($dbPath)) {
    echo "Initializing SQLite database...\n";
    
    // Create empty database file
    touch($dbPath);
    
    // Initialize database with schema
    $command = sprintf('sqlite3 %s < %s', escapeshellarg($dbPath), escapeshellarg($schemaPath));
    exec($command, $output, $returnVar);
    
    if ($returnVar === 0) {
        echo "Database initialized successfully!\n";
    } else {
        echo "Error initializing database.\n";
        exit(1);
    }
} else {
    echo "Database already exists.\n";
} 