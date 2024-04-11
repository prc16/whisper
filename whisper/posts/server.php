<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session and action
    if (!isset($_SESSION['user_id'])) {
        $conn->close();
        errorResponse(401, 'User not logged in.');
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Validate form data
    $action = $_POST['action'] ?? null;
    $postId = $_POST['post_id'] ?? null;
    if (!$action || !in_array($action, ['upvote', 'downvote']) || !$postId || strlen($postId) !== 16) {
        $conn->close();
        errorResponse(400, 'Invalid action or post ID provided.');
        exit;
    }

    if(!handleVote($conn, $userId, $postId, $action)) {
        $conn->close();
        errorResponse(500, 'Failed to handle vote request');
        exit;
    }
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Return posts as JSON
if (isset($_SESSION['user_id'])) {
    echo json_encode(getPostsWithVotes($conn, $_SESSION['user_id']));
} else {
    echo json_encode(getPosts($conn));
}

// Close the database connection
$conn->close();
