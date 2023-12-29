<?php

session_start();

// Access the variables from the session
$database_hostname = $_SESSION['database_hostname'];
$database_username = $_SESSION['database_username'];
$database_password = $_SESSION['database_password'];

// TODO: php function to create socialmedia_db 
// TODO: php function to create user named user with SELECT, INSERT, UPDATE, DELETE privileges
$database_name = "socialmedia_db";

// Create connection
$conn = new mysqli($database_hostname, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create the table
function create_table() {
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(50) NOT NULL UNIQUE,
        username VARCHAR(20) NOT NULL UNIQUE,
        password_hash VARCHAR(60) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}

// Function to delete the table
function delete_table() {
    global $conn;

    $sql = "DROP TABLE IF EXISTS users";

    if ($conn->query($sql) === TRUE) {
        echo "Table deleted successfully";
    } else {
        echo "Error deleting table: " . $conn->error;
    }
}

// Check if the form is submitted for table creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_table"])) {
    create_table();
}

// Check if the form is submitted for table deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_table"])) {
    delete_table();
}

$conn->close();
?>
