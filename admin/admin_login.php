<?php

// Admin login credentials
$admin_username = "admin";
$admin_password = "password"; 

// Process the login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = $_POST["username"];
    $entered_password = $_POST["password"];

    if ($entered_username == $admin_username && $entered_password == $admin_password) {
        // Correct login credentials, allow access to the admin home page
        header("Location: admin_home.php");
        exit;
    } else {
        // Incorrect credentials, display an error message
        echo "Incorrect username or password";
        exit;
    }
}
?>
