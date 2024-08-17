<?php

session_start();

require '../../config/config.php';  // Database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        // Handle invalid email address
        die('Invalid email address.');
    }

    $password = $_POST['psw'];

    if (empty($password)) {
        // Handle empty password
        die('Password cannot be empty.');
    }

    if ($email && $password) {
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['logged_in'] = true;
                header('Location: http://localhost/licCheck/index.php');
                exit;
            } else {
                $error_message = "Invalid email or password.";
                echo $error_message;
            }
        } else {
            $error_message = "Invalid email or password.";
            echo $error_message;
        }
        $stmt->close();
    } else {
        $error_message = "Please enter a valid email and password.";
        echo $error_message;
    }
}
