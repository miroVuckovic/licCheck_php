<?php
// Database configuration settings
define('DB_SERVER', 'localhost:5215');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'licCheck');

// Create a connection to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connection success!";

