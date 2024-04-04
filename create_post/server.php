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
if (!isset($_POST['post_text'])) {
    badRequestResponse('No post_text provided.');
}

// get Database Connection
$conn = getConnection();
$userId = $_SESSION['user_id'];
$post_text = $_POST['post_text'];

try {
    // Update username
    createPost($conn, $userId, $post_text);
    
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
