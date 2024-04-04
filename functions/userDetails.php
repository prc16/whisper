<?php
include_once '../database/functions.php';

$conn = getConnection();

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
    $userId = $_SESSION['user_id'];    
    $username = getUsername($conn, $userId);
    $profilePicture = getProfilePicture($conn, $userId);
}

$conn->close();

// HTML output