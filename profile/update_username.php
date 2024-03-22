<?php

include '../database/functions.php';

try {
    $conn = getConnection();
} catch (Exception $e) {
    handleException($e);
    exit();
}

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session and action
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
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
