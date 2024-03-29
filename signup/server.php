<?php

include_once '../database/functions.php';

$conn = getConnection();

// Process the signup form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (email, password_hash, user_id) VALUES (?, ?, ?)");
        
        // Generate a user_id
        $user_id = genUUID();
        
        // Hash the password before storing it in the database
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $email, $password_hash, $user_id);

        if ($stmt->execute()) {
            // Start a PHP session
            session_start();
            // Start a user session
            $_SESSION["user_id"] = $user_id;

            // Redirect to the home page
            header('Location: ../home/');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the email check statement
    $checkEmailStmt->close();

}

// Close the database connection
$conn->close();
?>
