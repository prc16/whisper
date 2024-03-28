<?php

include_once '../database/functions.php';

$conn = getConnection();

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (isset($_SESSION["user_id"])) {
    $loggedIn = true;
    $userId = $_SESSION["user_id"];
} else {
    $loggedIn = false;
}

if ($loggedIn) {
    // Display user ID when logged in
    $username = getUsername($conn, $userId);
    echo '<p class="username">' . $username . '</p>';
    // Display logout button
    echo '<a href="../logout/logout.php" class="logout-btn">Logout</a>';
} else {
    // Display login and signup buttons when not logged in
    echo '<a href="../login/" class="login-btn">Login</a>';
    echo '<a href="../signup/" class="signup-btn">Sign Up</a>';
}

// Close the database connection
$conn->close();
