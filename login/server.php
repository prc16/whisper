<?php

include_once '../php/all.php';
include_once '../php/database.php';
include_once '../php/errors.php';

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the username is provided
if (!isset($_POST['login_username']) || empty($_POST['login_username'])) {
    errorResponse(400, 'No Username Provided.');
}

// Check if the password is provided
if (!isset($_POST['login_password']) || empty($_POST['login_password'])) {
    errorResponse(400, 'No Password Provided.');
}

// Sanitize and validate user inputs
$username = htmlspecialchars($_POST["login_username"], ENT_QUOTES, 'UTF-8');
$password = $_POST["login_password"];

// Check if the username exists
if (!($userId = getUserId($conn, $username))) {
    errorResponse(404, 'Username is not registered. Please Sign Up instead');
    exit;
}

// Verify the password
if (!verifyPassword($conn, $username, $password)) {
    errorResponse(401, 'Invalid password');
    exit;
}

// Start a PHP session
session_start();

// Start a user session
$_SESSION["user_id"] = $userId;


// Close the database connection
$conn->close();
