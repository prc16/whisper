<?php
include_once '../database/functions.php';

$conn = getConnection();
if(!$conn) {
    serverMaintenanceResponse();
    exit;
}

session_start();

// Initialize variables with default values
$loggedIn = false;
$username = 'Anonymous';
$profilePicture = DEFAULT_PROFILE;

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