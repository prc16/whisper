<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

$conn = getConnection();
if(!$conn) {
    header('Location: /maintenance');
    exit;
}

$username = 'Anonymous';

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];    
    $username = getUsername($conn, $userId);
}

$conn->close();

header('Location: /u/' . $username);