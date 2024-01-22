<?php

include "../config.php";

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

            // Start a PHP session
            session_start();
            // Start a user session
            $_SESSION["user_id"] = $uid;

            // Redirect to the home page
            header('Location: ../home/');
            exit;
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Email is not registered. Please Sign Up instead";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
