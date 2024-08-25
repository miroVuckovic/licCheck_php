<?php

// return [
//     'host' => 'localhost',
//     'database' => 'licCheck',
//     'username' => 'root',
//     'password' => '',
// ];

// Database connection
$host = 'localhost';
$dbname = 'licCheck';
$username = 'root'; // Replace with your DB username
$password = ''; // Replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
