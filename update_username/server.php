<?php

include_once '../database/functions.php';

$response = array();

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    unauthorizedResponse();
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    badRequestResponse();
}

// Check if the username is provided
if (!isset($_POST['newUsername'])) {
    badRequestResponse('No username provided.');
}

// get Database Connection
$conn = getConnection();
$userId = $_SESSION['user_id'];
$username = $_POST['newUsername'];

try {
    // Check if username exists
    if (usernameExists($conn, $userId)) {
        $oldUsername = getUsername($conn, $userId);

        if ($username === $oldUsername) {
            $response['success'] = false;
            $response['message'] = 'Username already exists.';
            echo json_encode($response);
            $conn->close();
            exit();
        }
    } 
    
    // Update username
    updateUsername($conn, $userId, $username);
    
    // Success response
    $response['success'] = true;
    $response['message'] = 'Username updated successfully.';
    echo json_encode($response);
} catch (Exception $e) {
    handleException($e);
    errorResponse('Internal Server Error', 500);
} finally {
    $conn->close();
}
