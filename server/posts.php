<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

session_start();

$voterUserId = $_SESSION['user_id'] ?? null;
$reqUsername = $_GET['username'] ?? null;

// Establish database connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Determine the user ID
$reqUserId = null;
if ($reqUsername) {
    $reqUserId = getUserId($conn, $reqUsername);
}

// Fetch posts based on user and voter
if ($reqUserId !== null && $voterUserId !== null) {
    echo json_encode(getUserPostsWithVotes($conn, $voterUserId, $reqUserId));
} elseif ($reqUserId !== null) {
    echo json_encode(getUserPosts($conn, $reqUserId));
} elseif ($voterUserId !== null) {
    echo json_encode(getPostsWithVotes($conn, $voterUserId));
} else {
    echo json_encode(getPosts($conn));
}

// Close the database connection
$conn->close();
