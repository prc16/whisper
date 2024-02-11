<?php

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (isset($_SESSION["user_id"])) {
    $loggedIn = true;
    $user_id = $_SESSION["user_id"];
} else {
    $loggedIn = false;
}

if ($loggedIn) {
    // Display user ID when logged in
    echo '<p class="username">' . $user_id . '</p>';
    // Display logout button
    echo '<a href="../logout/logout.php" class="logout-btn">Logout</a>';
} else {
    // Display login and signup buttons when not logged in
    echo '<a href="../login/" class="login-btn">Login</a>';
    echo '<a href="../signup/" class="signup-btn">Sign Up</a>';
}
