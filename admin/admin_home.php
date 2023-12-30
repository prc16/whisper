<?php

session_start();

// Constants
define('TABLE_NAME', 'users');
define('DATABASE_USERNAME', 'user');
define('DATABASE_PASSWORD', 'php');
define('DATABASE_NAME', 'socialmedia_db');

// Function to establish a database connection
function connectToDatabase() {
    $database_hostname = $_SESSION['database_hostname'];
    $admin_username = $_SESSION['admin_username'];
    $admin_password = $_SESSION['admin_password'];

    $conn = new mysqli($database_hostname, $admin_username, $admin_password, DATABASE_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to create the table
function createTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS " . TABLE_NAME . " (
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
function deleteTable($conn) {
    $sql = "DROP TABLE IF EXISTS " . TABLE_NAME;

    if ($conn->query($sql) === TRUE) {
        echo "Table deleted successfully";
    } else {
        echo "Error deleting table: " . $conn->error;
    }
}

// Check if the form is submitted for table creation or deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDatabase();

    if (isset($_POST["create_table"])) {
        createTable($conn);
    } elseif (isset($_POST["delete_table"])) {
        deleteTable($conn);
    }

    $conn->close();
}
?>
