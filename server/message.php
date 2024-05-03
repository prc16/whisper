<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse(400, 'Bad Request');
    exit;
}

session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'Unauthorized fetch request');
    exit;
}

// Check if username is not set to "Anonymous"
if (strcasecmp($_GET['username'] ?? null, 'Anonymous') === 0) {
    header('Location: /messages');
    exit;
}

$_SESSION['reqUsername'] = $_GET['username'] ?? null;

header('Location: /messages');