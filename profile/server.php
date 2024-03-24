<?php

include '../database/functions.php';

try {
    $conn = getConnection();
} catch (Exception $e) {
    handleException($e);
    exit();
}

session_start();

// Validate session and action
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    $userId = '';
}

$username = getUsername($conn, $userId);

$profile_picture = '../uploads/' . $userId . '.jpg';

if (!file_exists($profile_picture)) {
    $profile_picture = '../images/Default_Profile.jpg';
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Return profile info as JSON
echo json_encode(array('profile_picture' => $profile_picture, 'username' => $username));
