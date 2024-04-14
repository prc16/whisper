<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

session_start();

// Validate session and action
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'You need to login to vote.');
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
    exit;
}

// Retrieve the JSON data sent from the client
$data = json_decode(file_get_contents("php://input"));


// Validate form data
$encryptedData = $data->encryptedData;
$initializationVector = $data->initializationVector;
$username = $data->username;

// Request is valid, handle vote request
$userId = $_SESSION['user_id'];

$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

$reqUserId = getUserId($conn, $username);

if (!insertMessage($conn, $userId, $reqUserId, $encryptedData, $initializationVector)) {
    $conn->close();
    errorResponse(500, 'Failed to handle vote request');
    exit;
}

$conn->close();
