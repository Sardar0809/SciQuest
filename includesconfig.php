<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'scitasker');
define('DB_USER', 'root');       // change to your database username
define('DB_PASS', '');           // change to your database password

// Site URL (change after deployment)
define('SITE_URL', 'http://localhost/scitasker');

// File upload directory
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Include helper functions
require_once 'functions.php';
?>