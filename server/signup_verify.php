<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';

$conn = getConnection();
if(!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the username and password are provided
if (!isset($_POST['signup_username'], $_POST['signup_password']) || empty($_POST['signup_username']) || empty($_POST['signup_password'])) {
    errorResponse(400, 'Both username and password are required.');
}

// Process the signup form data

// Sanitize and validate user inputs
$username = htmlspecialchars($_POST["signup_username"], ENT_QUOTES, 'UTF-8');
$password = $_POST["signup_password"];

// Check if username is not set to "Anonymous"
if (strcasecmp($username, 'Anonymous') === 0) {
    $conn->close();
    errorResponse(400, 'Username "Anonymous" not allowed.');
}

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $conn->close();
    errorResponse(400, 'Invalid username. Username can only contain letters, numbers, and underscores.');
}

// Check if the username already exists
if (usernameExists($conn, $username)) {
    $conn->close();
    errorResponse(409, 'Username already exists. Please choose a different username.');
}

// Close the database connection
$conn->close();
