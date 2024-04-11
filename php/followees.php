<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

$conn = getConnection();
if(!$conn) {
    header('Location: /whisper/maintenance/');
    exit;
}

session_start();

// Initialize variables with default values
$loggedIn = false;
$reqUsername = 'Anonymous';
$reqProfilePicture = DEFAULT_PROFILE;
$followees = array();
$followers = array();

if (isset($_SESSION['user_id'])) {
    // If user is logged in
    $loggedIn = true;
    $userId = $_SESSION['user_id'];    
    $reqUsername = getUsername($conn, $userId);
    $reqProfilePicture = getProfilePicture($conn, $userId);
    $followees = getFollowees($conn, $userId);
    $followers = getFollowers($conn, $userId);
}
