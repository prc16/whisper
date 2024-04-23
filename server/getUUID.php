<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Generate a new UUID
echo json_encode(['UUID' => genUUID()]);
