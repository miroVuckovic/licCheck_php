<?php
define('DB_SERVER', 'localhost');
define('DB_NAME', 'licCheck');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



