<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

$conn = getConnection();
if(!$conn) {
    header('Location: /maintenance');
    exit;
}

session_start();

// Initialize variables with default values
$loggedIn = false;
$username = 'Anonymous';
$profilePicture = DEFAULT_PROFILE;
$keyPairId = '';

if (isset($_SESSION['user_id'])) {
    // If user is logged in
    $loggedIn = true;
    $userId = $_SESSION['user_id'];    
    $username = getUsername($conn, $userId);
    $profilePicture = getProfilePicture($conn, $userId);
    $keyPairId = getKeyPairId($conn, $userId);
}

$conn->close();

// HTML output