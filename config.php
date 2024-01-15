<?php

// Database connection details
$database_hostname ="localhost";
$database_username = "root";
$database_password = "";
$database_name = "whisper_db";

// Create connection
$conn = new mysqli($database_hostname, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
