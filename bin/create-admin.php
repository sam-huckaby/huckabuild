<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Generate a secure random password
function generateSecurePassword($length = 16) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

$dbPath = __DIR__ . '/../database/huckabuild.sqlite';

if (!file_exists($dbPath)) {
    echo "Error: Database file not found. Please run init-db.php first.\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Generate password and hash it
    $password = generateSecurePassword();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['builder']);
    if ($stmt->fetch()) {
        echo "Admin user already exists.\n";
        exit(0);
    }

    // Insert new admin user
    $stmt = $pdo->prepare("INSERT INTO users (display_name, username, email, password, password_reset_required) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['Builder Admin', 'builder', 'builder@example.com', $hashedPassword, 0]);

    echo "\n=== Admin User Created Successfully ===\n";
    echo "Username: builder\n";
    echo "Password: " . $password . "\n";
    echo "=====================================\n";
    echo "\nIMPORTANT: Please save this password immediately. It will not be shown again.\n\n";

} catch (PDOException $e) {
    echo "Error creating admin user: " . $e->getMessage() . "\n";
    exit(1);
} 