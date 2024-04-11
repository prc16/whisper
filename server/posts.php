<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

session_start();

$userId = $_SESSION['user_id'] ?? null;
// $username = $_POST['username'] ?? null;
// $postId = $_POST['post_id'] ?? null;

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
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
