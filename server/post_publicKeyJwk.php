<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

session_start();

// Validate session and action
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'Unauthorized');
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
    exit;
}

// Retrieve the JSON data sent from the client
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->publicKeyJwk)) {
    errorResponse(400, 'No public key provided');
    exit;
}

// Validate form data
$publicKeyJwk = json_encode($data->publicKeyJwk);
$keyPairId  = genUUID();
// Request is valid, handle vote request
$userId = $_SESSION['user_id'];

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

if (!insertPublicKey($conn, $userId, $keyPairId, $publicKeyJwk)) {
    $conn->close();
    errorResponse(500, 'Failed to handle upload public key request');
    exit;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(array("keyPairId" => $keyPairId));