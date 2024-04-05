<?php

include_once '../functions/database.php';
include_once '../functions/uuid.php';

$conn = getConnection();

// Process the signup form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $password = $_POST["password"];

    // Validate username format
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        http_response_code(400);
        echo "Invalid username. Username can only contain letters, numbers, and underscores.";
        exit;
    }

    // Check if the username already exists
    if (usernameExists($conn, $username)) {
        http_response_code(409); // Conflict
        echo "Username already exists. Please choose a different username.";
        exit;
    }

    // Generate a unique user_id
    $user_id = genUUID();

    // Hash the password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    if (insertUser($conn, $user_id, $username, $password_hash)) {
        // Start a PHP session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Start a user session
        $_SESSION["user_id"] = $user_id;

        // Redirect to the home page
        header('Location: ../maintenance/');
        exit;
    } else {
        // Error handling
        http_response_code(500); // Internal Server Error
        echo "Error: Unable to create account. Please try again later.";
        exit;
    }
}

// Close the database connection
$conn->close();
