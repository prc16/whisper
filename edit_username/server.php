<?php

include_once '../database/functions.php';

$conn = getConnection();

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session and action
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        $conn->close();
        exit();
    }

    $userId = $_SESSION['user_id'];

    try {
        // Validate JSON data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        validateJsonData($data, ['username']);
        $username = $data['username'];
        editUsername($conn, $userId, $username);
    } catch (Exception $e) {
        handleException($e);
    }
}

$conn->close();
