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
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT username, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_username, $db_password_hash);

    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $db_password_hash)) {
            echo "Login successful!";
            // Add session handling or redirect to a secure area here
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
