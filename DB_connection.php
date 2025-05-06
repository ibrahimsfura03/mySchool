<?php
// Database credentials
$host     = '127.0.0.1';
$port     = '3307';
$db_name  = 'mySchool';
$username = 'root';
$password = '8569IBB';

// Build DSN (include charset if you like)
$dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";

try {
    // Create PDO instance
    $conn = new PDO($dsn, $username, $password);
    // Throw exceptions on errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // (Optional) set default fetch mode to associative arrays
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Connection successful!
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}
