<?php
// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants if not already defined
if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost:3000/');
}

if (!defined('LOCALHOST')) {
    define('LOCALHOST', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'osa'); // Change to your database name (osa)
}

// Establish the database connection
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
