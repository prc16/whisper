<?php

include_once '../database/functions.php';

$conn = getConnection();

session_start();

// Validate session and action
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = getUsername($conn, $userId);
    $profilePicture = getProfilePicture($conn, $userId);
} else {
    $username = 'Anonymous';
    $profilePicture = '../images/Default_Profile.jpg';
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Return profile info as JSON
echo json_encode(array('profile_picture' => $profilePicture, 'username' => $username));
