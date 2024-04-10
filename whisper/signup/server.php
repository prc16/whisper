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

// Check if the username is provided
if (!isset($_POST['signup_username']) || empty($_POST['signup_username'])) {
    errorResponse(400, 'No Username Provided.');
}

// Check if the password is provided
if (!isset($_POST['signup_password']) || empty($_POST['signup_password'])) {
    errorResponse(400, 'No Password Provided.');
}

// Process the signup form data

// Validate and sanitize user inputs
$username = htmlspecialchars($_POST["signup_username"], ENT_QUOTES, 'UTF-8');
$password = $_POST["signup_password"];

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    errorResponse(400, 'Invalid username. Username can only contain letters, numbers, and underscores.');
}

// Check if the username already exists
if (usernameExists($conn, $username)) {
    errorResponse(409, 'Username already exists. Please choose a different username.');
}

// Generate a unique user_id
$user_id = genUUID();

// Hash the password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into the database
if (insertUser($conn, $user_id, $username, $password_hash)) {

    session_start();

    // Start a user session
    $_SESSION["user_id"] = $user_id;
} else {
    // Error handling
    errorResponse(500, 'Error: Unable to create account.');  // Internal Server Error
    exit;
}


// Close the database connection
$conn->close();
