<?php

include '../database/functions.php';

$conn = getConnection();

session_start();

// Validate session and action
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = getUsername($conn, $userId);
    if(profilePictureExists($conn, $userId)) {
        $fileId = getProfilePicture($conn, $userId);
        $profilePicture = '../uploads/' . $fileId . '.jpg';
        if (!file_exists($profilePicture)) {
            $profile_picture = '../images/Default_Profile.jpg';
        }    
    } else {
        $profilePicture = '../images/Default_Profile.jpg';
    }
} else {
    $username = 'Anonymous';
    $profilePicture = '../images/Default_Profile.jpg';
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Return profile info as JSON
echo json_encode(array('profile_picture' => $profilePicture, 'username' => $username));
