<?php

// Define database connection details
define("DATABASE_HOSTNAME", "localhost");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "");
define("DATABASE_NAME", "whisper_db");

try {
    $conn = new mysqli(DATABASE_HOSTNAME, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    error_log("Error: $errorMessage");
    echo json_encode(['error' => $errorMessage]);
    exit();
}
