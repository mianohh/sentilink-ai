<?php
// Database configuration
define('DB_HOST', 'localhost'); // Change as needed
define('DB_NAME', '');
define('DB_USER', ''); // Change as needed
define('DB_PASS', ''); // Change as needed

// Application settings
define('ADMIN_SESSION_NAME', 'sentilink_admin');
define('MEME_UPLOAD_DIR', 'images/memes/');

// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session
session_start();
?>