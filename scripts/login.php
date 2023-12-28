<?php

// Include the database connection file
include('connection.php');

// Process the login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT username, password FROM $tableName WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($dbUsername, $dbPassword);

    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $dbPassword)) {
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
