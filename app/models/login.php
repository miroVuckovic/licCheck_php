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
        $sql = $conn->prepare('SELECT id, password, username FROM users WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        $sql->store_result();

        if ($sql->num_rows > 0) {
            $sql->bind_result($id, $hashed_password, $username);
            $sql->fetch();

            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;

                $sql = $conn->prepare('SELECT role_id FROM user_roles WHERE user_id = ?');
                $sql->bind_param('s', $id);
                $sql->execute();
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->bind_result($role_id);
                    $sql->fetch();
                }

                $_SESSION['role_id'] = $role_id;

                $sql = $conn->prepare('SELECT role_name FROM roles WHERE id = ?');
                $sql->bind_param('s', $role_id);
                $sql->execute();
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->bind_result($role_desc);
                    $sql->fetch();
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
        $sql->close();
    } else {
        $error_message = "Please enter a valid email and password.";
        echo $error_message;
    }
}
