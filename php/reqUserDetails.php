<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

if(!isset($_GET['username'])) {
    errorResponse(400, 'Bad Request');
    exit;
}

$conn = getConnection();
if(!$conn) {
    header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/whisper/maintenance/');
    exit;
}

session_start();

// Initialize variables with default values
$reqLoggedIn = false;
$reqUsername = $_GET['username'];
$reqProfilePicture = DEFAULT_PROFILE;
$ownUserId = null;
$reqUserId = null;

if (isset($_SESSION['user_id'])) {
    $ownUserId = $_SESSION['user_id'];
}


if(usernameExists($conn, $reqUsername)) {
    $reqUserId = getUserId($conn, $reqUsername);
    $reqProfilePicture = getProfilePicture($conn, $reqUserId);
    if($reqUserId == $ownUserId) {
        $reqLoggedIn = true;
    }
} else {
    $reqUsername = 'Anonymous';
}

$conn->close();
