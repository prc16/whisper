<?php

include_once '../php/all.php';
include_once '../php/database.php';
include_once '../php/errors.php';

$response = array();

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'User not logged in');
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the username is provided
if (!isset($_POST['newUsername'])) {
    errorResponse(400, 'No username provided.');
}

// Verify if the username contains only letters, numbers, and underscores
if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['newUsername'])) {
    errorResponse(400, 'Invalid username. Username can only contain letters, numbers, and underscores.');
}

// get Database Connection
$conn = getConnection();
if(!$conn) {
    serverMaintenanceResponse();
    exit;
}
$userId = $_SESSION['user_id'];
$username = $_POST['newUsername'];


// Check if username exists
if (usernameExists($conn, $username)) {
    $conn->close();
    errorResponse(400, 'Username already exists.');
}

// Update username
if (!updateUsername($conn, $userId, $username)) {
    $conn->close();
    errorResponse(500, 'Exception thrown during update username');
}

// Success response
$response['message'] = 'Username updated successfully.';
echo json_encode($response);
$conn->close();
