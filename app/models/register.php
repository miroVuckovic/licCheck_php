<?php
// Start the session
session_start();

// Include the database configuration file
require '../../config/config.php'; // Ensure this file contains your database connection setup

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["psw"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["psw"])) < 4) {
        $password_err = "Password must have at least 4 characters.";
    } else {
        $password = trim($_POST["psw"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["psw-repeat"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["psw-repeat"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page

                // Prepare the statement to select the id from users table where email matches
                $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
                $stmt->bind_param('s', $email); // Bind the email parameter
                $stmt->execute(); // Execute the statement
                $stmt->store_result(); // Store the result to check for rows

                if ($stmt->num_rows > 0) { // If there is at least one row
                    $stmt->bind_result($id); // Bind the result to the variable $id
                    $stmt->fetch(); // Fetch the result to get the id

                    // Now prepare the insert statement for user_roles table
                    $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
                    $stmt_insert = $conn->prepare($sql); // Prepare the insert statement
                    $role_id = 2; // Set the role_id value
                    $stmt_insert->bind_param("ii", $id, $role_id); // Bind the user_id and role_id parameters. Use "ii" for integer binding
                    $stmt_insert->execute(); // Execute the insert statement 
                }


                header("location: http://localhost/licCheck/index.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
