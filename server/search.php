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

$query = $_GET['query'] ?? null;

// Establish database connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

if(!$query) {
    $conn->close();
    errorResponse(400, 'Bad Request');
}

$usernames = searchUsername($conn, $query);

if($userId) {
    $posts = searchPostsWithVotes($conn, $userId, $query);
} else {
    $posts = searchPosts($conn, $query);
}

$response = array('usernames' => $usernames, 'posts' => $posts);

echo json_encode($response);

// Close the database connection
$conn->close();
