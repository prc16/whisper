<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

session_start();

$reqUsername = $_GET['username'] ?? null;

// Establish database connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

header('Content-Type: application/json');

// Determine the user ID
$reqUserId = null;
if ($reqUsername) {
    $reqUserId = getUserId($conn, $reqUsername);
}

if (!$reqUserId) {
    $conn->close();
    errorResponse(400, 'Bad Request');
    exit;
}

$publicKeyJwk = getPublicKey($conn, $reqUserId);


if (!$publicKeyJwk) {
    $conn->close();
    errorResponse(500, 'failed to get public key');
    exit;
}

echo json_encode(['publicKeyJwk' => $publicKeyJwk]);

// Close the database connection
$conn->close();
