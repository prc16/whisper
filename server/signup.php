<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the required fields are provided
$requiredFields = ['signup_username', 'signup_password', 'publicKeyJwk', 'keyPairId'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        errorResponse(400, "No $field provided.");
    }
}

// Process the signup form data

// Sanitize and validate user inputs
$username = htmlspecialchars($_POST["signup_username"], ENT_QUOTES, 'UTF-8');
$password = $_POST["signup_password"];
$keyPairId = $_POST["keyPairId"];
$publicKeyJwk = json_encode(json_decode($_POST["publicKeyJwk"])->publicKeyJwk);

// Check if username is not set to "Anonymous"
if (strcasecmp($username, 'Anonymous') === 0) {
    $conn->close();
    errorResponse(400, 'Username "Anonymous" not allowed.');
}

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    errorResponse(400, 'Invalid username. Username can only contain letters, numbers, and underscores.');
}

// Check if the username already exists
if (usernameExists($conn, $username)) {
    errorResponse(409, 'Username already exists. Please choose a different username.');
}

// Validate keyPairId length
if (strlen($keyPairId) !== 16) {
    errorResponse(400, 'Invalid key pair id');
}

$conn->autocommit(false);

try {
    // Generate a unique user_id
    $user_id = genUUID();

    // Hash the password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user and public key into the database
    if (insertUser($conn, $user_id, $username, $password_hash) && insertPublicKey($conn, $user_id, $keyPairId, $publicKeyJwk)) {
        session_start();
        // Start a user session
        $_SESSION["user_id"] = $user_id;
        $conn->commit();
        $conn->autocommit(true);
        $conn->close();
    } else {
        throw new Exception();
    }
} catch (Exception $e) {
    $conn->rollback();
    $conn->close();
    errorResponse(500, 'Error: Unable to create account.'); // Internal Server Error
    exit;
}
