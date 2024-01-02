<?php

session_start();

// Constants
$database_username = 'user';
$database_password = 'php';
$database_name = 'socialmedia_db';
$table_name = 'users';
$columns = "user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    username VARCHAR(20) NOT NULL UNIQUE,
    password_hash VARCHAR(60) NOT NULL";
$privileges = "SELECT, INSERT, UPDATE, DELETE";

// Retrieve session data
$database_hostname = $_SESSION['database_hostname'];
$admin_username = $_SESSION['admin_username'];
$admin_password = $_SESSION['admin_password'];

include "admin.php";

function logout()
{
    // Check if a session is active
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();
    }

    // Redirect hadled by AJAX request... Uncomment if needed
    //header("Location: ../index.html");
    //exit();
}

// Check the form submission and take appropriate action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $conn = connectToDatabase($database_hostname, $admin_username, $admin_password);

    if (isset($_POST["action"])) {
        $action = $_POST["action"];

        switch ($action) {
            case "create_database":
                createDatabase($conn, $database_name);
                break;
            case "delete_database":
                deleteDatabase($conn, $database_name);
                break;
            case "create_user":
                createUser(
                    $conn,
                    $database_hostname,
                    $database_username,
                    $database_password,
                    $database_name,
                    $privileges
                );
                break;
            case "delete_user":
                deleteUser(
                    $conn,
                    $database_hostname,
                    $database_username,
                    $database_name
                );
                break;
            case "create_table":
                createTable(
                    $conn,
                    $database_name,
                    $table_name,
                    $columns);
                break;
            case "delete_table":
                deleteTable(
                    $conn,
                    $database_name,
                    $table_name);
                break;
            case "logout":
                logout();
                break;
            default:
                echo "Invalid action.";
                break;
        }
    }

    $conn->close();
}
?>