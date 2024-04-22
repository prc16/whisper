<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

session_start();

// Validate session and action
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'You need to login to vote.');
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
    exit;
}

// Validate form data
$action = $_POST['action'] ?? null;
$postId = $_POST['post_id'] ?? null;
if (!$action || !in_array($action, ['upvote', 'downvote', 'approve', 'disapprove']) || !$postId || strlen($postId) !== 16) {
    errorResponse(400, 'Invalid action or post ID provided.');
    exit;
}

// Request is valid, handle vote request
$userId = $_SESSION['user_id'];

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

if (!handleVote($conn, $userId, $postId, $action)) {
    $conn->close();
    errorResponse(500, 'Failed to handle vote request');
    exit;
}

$conn->close();
