<?php

include_once '../functions/database.php';

$conn = getConnection();

// Process the login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username exists
    if (!usernameExists($conn, $username)) {
        http_response_code(404); // Not Found
        echo "Username is not registered. Please Sign Up instead";
        exit;
    }


    // Verify the password
    if (!verifyPassword($conn, $username, $password)) {
        http_response_code(401); // Unauthorized
        echo "Invalid password";
        exit;
    }

    // Start a PHP session
    session_start();
    
    // Start a user session
    $_SESSION["user_id"] = getUserId($conn, $username);

    // Redirect to the home page
    header('Location: ../maintenance/');
    exit;
}

// Close the database connection
$conn->close();
