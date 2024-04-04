<?php
include_once '../database/functions.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables with default values
$loggedIn = false;
$username = 'Anonymous';
$profilePicture = '../images/Default_Profile.jpg';

// Check if profile picture and username are already stored in session
if (isset($_SESSION['user_id'])) {
    // If user is logged in
    $loggedIn = true;
    if (!isset($_SESSION['profile_picture']) || !isset($_SESSION['username']) || isset($_SESSION['profile_refresh_needed'])) {
        // If profile picture and username are not stored in session, fetch from the database
        $userId = $_SESSION['user_id'];
        $conn = getConnection();
        $username = getUsername($conn, $userId);
        $profilePicture = getProfilePicture($conn, $userId);
        $_SESSION['username'] = $username;
        $_SESSION['profile_picture'] = $profilePicture;
        unset($_SESSION['profile_refresh_needed']);
        // Close the database connection
        $conn->close();
    } else {
        // If profile picture and username are already stored in session, retrieve them
        $username = $_SESSION['username'];
        $profilePicture = $_SESSION['profile_picture'];
    }
}

// HTML output