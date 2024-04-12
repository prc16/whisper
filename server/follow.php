<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

session_start();

// Validate session and action
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'You need to login to follow.');
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
    exit;
}

// Request is valid, handle follow request
$follower_id = $_SESSION['user_id'];

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Validate form data
$action = $_POST['action'] ?? null;
$followee_username = $_POST['username'] ?? null;

if (!$action || !in_array($action, ['follow', 'unfollow']) || !usernameExists($conn, $followee_username)) {
    $conn->close();
    errorResponse(400, 'Invalid action or username provided.');
    exit;
}

$followee_id = getUserId($conn, $followee_username);

if ($action == 'follow') {
    if (!insertFollower($conn, $follower_id, $followee_id)) {
        $conn->close();
        errorResponse(500, 'Failed to handle follow request');
        exit;
    }
} else {
    if (!deleteFollower($conn, $follower_id, $followee_id)) {
        $conn->close();
        errorResponse(500, 'Failed to handle unfollow request');
        exit;
    }
}

$conn->close();
