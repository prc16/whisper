<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'Unauthorized fetch request');
    exit;
}

$reqUsername = $_GET['username'] ?? null;

$userId = $_SESSION['user_id'];

// Establish database connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

if (!usernameExists($conn, $reqUsername)) {
    $conn->close();
    errorResponse(404, 'User not found');
    exit;
}

$reqUserId = getUserId($conn, $reqUsername);

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Fetch followee usernames based on follower_id
echo json_encode(getMessages($conn, $userId, $reqUserId));

// Close the database connection
$conn->close();
