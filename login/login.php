<?php

include "../config.php";

// Create connection
$conn = new mysqli($database_hostname, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT uid, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($uid, $password_hash);

    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $password_hash)) {
            echo "Log In successful!";
            // TODO: Add session handling or redirect to a secure area here
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Email is not registerd. Please Sign Up instead";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
