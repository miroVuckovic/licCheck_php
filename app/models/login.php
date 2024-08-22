<?php

session_start();

require '../../config/config.php';  // Database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        echo 'Invalid email address.';
        die;
    }

    $password = $_POST['psw'];

    if (empty($password)) {
        echo ('Password cannot be empty.');
        die;
    }

    if ($email && $password) {
        $stmt = $conn->prepare('SELECT id, password, username FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $username);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;

                $stmt = $conn->prepare('SELECT role_id FROM user_roles WHERE user_id = ?');
                $stmt->bind_param('s', $id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($role_id);
                    $stmt->fetch();
                }

                $_SESSION['role_id'] = $role_id;

                $stmt = $conn->prepare('SELECT role_name FROM roles WHERE id = ?');
                $stmt->bind_param('s', $role_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($role_desc);
                    $stmt->fetch();
                }

                $_SESSION['role_desc'] = $role_desc;

                header('Location: ../../index.php');
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
