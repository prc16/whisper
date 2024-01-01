<?php

session_start();

// Constants
define('TABLE_NAME', 'users');
define('DATABASE_USERNAME', 'user');
define('DATABASE_PASSWORD', 'php');
define('DATABASE_NAME', 'socialmedia_db');

// Retrieve session data
$database_hostname = $_SESSION['database_hostname'];
$admin_username = $_SESSION['admin_username'];
$admin_password = $_SESSION['admin_password'];

// Function to establish a database connection
function connectToDatabase()
{
    // Access the global variables
    global $database_hostname, $admin_username, $admin_password;

    // Create a new MySQLi connection
    $conn = new mysqli($database_hostname, $admin_username, $admin_password);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to create a database
function createDatabase($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database already exists
        echo "Database already exists.<br>";
    } else {
        // Database does not exist; create it
        $createQuery = "CREATE DATABASE " . DATABASE_NAME;
        if ($conn->query($createQuery) === TRUE) {
            echo "Database created successfully.<br>";
        } else {
            echo "Error creating database: " . $conn->error . "<br>";
        }
    }
}

// Function to delete a database
function deleteDatabase($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database exists; delete it
        $dropQuery = "DROP DATABASE " . DATABASE_NAME;
        $dropResult = $conn->query($dropQuery);
        if ($dropResult === TRUE) {
            echo "Database deleted successfully.<br>";
        } else {
            echo "Error deleting database: " . $conn->error . "<br>";
        }
    } else {
        // Database does not exist
        echo "Database does not exist.<br>";
    }
}

// Function to create a user
function createUser($conn)
{
    global $database_hostname;

    // Check if the user exists before attempting to create it
    $checkUserQuery = "SELECT 1 FROM mysql.user WHERE user = '" . DATABASE_USERNAME . "' AND host = '$database_hostname'";
    $checkUserResult = $conn->query($checkUserQuery);

    if ($checkUserResult->num_rows > 0) {
        // User already exists
        echo "User already exists.<br>";
    } else {
        // User does not exist; create it
        $createUserQuery = "CREATE USER '" . DATABASE_USERNAME . "'@'$database_hostname' IDENTIFIED BY '" . DATABASE_PASSWORD . "'";
        $createUserResult = $conn->query($createUserQuery);
        if ($createUserResult === TRUE) {
            echo "User created successfully.<br>";
        } else {
            echo "Error creating user: " . $conn->error . "<br>";
        }

        // Grant privileges
        $grantPrivilegesQuery = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . DATABASE_NAME . ".* TO '" . DATABASE_USERNAME . "'@'$database_hostname'";
        $grantPrivilegesResult = $conn->query($grantPrivilegesQuery);
        if ($grantPrivilegesResult === TRUE) {
            echo "Privileges granted successfully.<br>";
        } else {
            echo "Error granting privileges: " . $conn->error . "<br>";
        }

        // Flush privileges
        $flushPrivilegesQuery = "FLUSH PRIVILEGES";
        $flushPrivilegesResult = $conn->query($flushPrivilegesQuery);
        if ($flushPrivilegesResult === TRUE) {
            echo "Privileges flushed successfully.<br>";
        } else {
            echo "Error flushing privileges: " . $conn->error . "<br>";
        }
    }
}

// Function to delete a user
function deleteUser($conn)
{
    global $database_hostname;

    $checkUserQuery = "SELECT 1 FROM mysql.user WHERE user = '" . DATABASE_USERNAME . "' AND host = '$database_hostname'";
    $checkUserResult = $conn->query($checkUserQuery);

    if ($checkUserResult->num_rows > 0) {
        // User exists

        // Revoke privileges
        $revokePrivilegesQuery = "REVOKE ALL PRIVILEGES ON " . DATABASE_NAME . ".* FROM '" . DATABASE_USERNAME . "'@'$database_hostname'";
        $revokePrivilegesResult = $conn->query($revokePrivilegesQuery);
        if ($revokePrivilegesResult === TRUE) {
            echo "Privileges revoked successfully.<br>";
        } else {
            echo "Error revoking privileges: " . $conn->error . "<br>";
        }

        // Flush privileges
        $flushPrivilegesQuery = "FLUSH PRIVILEGES";
        $flushPrivilegesResult = $conn->query($flushPrivilegesQuery);
        if ($flushPrivilegesResult === TRUE) {
            echo "Privileges flushed successfully.<br>";
        } else {
            echo "Error flushing privileges: " . $conn->error . "<br>";
        }

        // Delete user
        $deleteUserQuery = "DROP USER '" . DATABASE_USERNAME . "'@'$database_hostname'";
        $deleteUserResult = $conn->query($deleteUserQuery);
        if ($deleteUserResult === TRUE) {
            echo "User deleted successfully.<br>";
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    } else {
        // User does not exist
        echo "User does not exist.<br>";
    }
}


// Function to create a table
function createTable($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database exists

        // Select database
        $conn->select_db(DATABASE_NAME);

        // Check if table exists before attempting to create it
        $checkTableQuery = "SHOW TABLES LIKE '" . TABLE_NAME . "'";
        $checkTableResult = $conn->query($checkTableQuery);

        if ($checkTableResult->num_rows > 0) {
            // Table already exists
            echo "Table already exists<br>";
        } else {
            // Table does not exist; create it
            $createTableQuery = "CREATE TABLE " . TABLE_NAME . " (
                    user_id INT PRIMARY KEY AUTO_INCREMENT,
                    email VARCHAR(50) NOT NULL UNIQUE,
                    username VARCHAR(20) NOT NULL UNIQUE,
                    password_hash VARCHAR(60) NOT NULL
                )";

            $createTableResult = $conn->query($createTableQuery);
            if ($createTableResult === TRUE) {
                echo "Table created successfully<br>";
            } else {
                echo "Error creating table: " . $conn->error . "<br>";
            }
        }
    } else {
        // Database does not exist
        echo "Database does not exist, cannot create table without database.";
    }
}

// Function to delete a table
function deleteTable($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database exists

        // Select database
        $conn->select_db(DATABASE_NAME);

        // Check if table exists before attempting to delete it
        $checkTableQuery = "SHOW TABLES LIKE '" . TABLE_NAME . "'";
        $checkTableResult = $conn->query($checkTableQuery);

        if ($checkTableResult->num_rows > 0) {
            // Table exists, delete it
            $dropTableQuery = "DROP TABLE " . TABLE_NAME;
            $dropTableResult = $conn->query($dropTableQuery);
            if ($dropTableResult === TRUE) {
                echo "Table dropped successfully.<br>";
            } else {
                echo "Error dropping table: " . $conn->error . "<br>";
            }
        } else {
            // Table does not exist
            echo "Table does not exist.<br>";
        }
    } else {
        // Database does not exist
        echo "Database does not exists, cannot delete table without database.";
    }
}

function logout()
{
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}

// Check the form submission and take appropriate action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDatabase();

    if (isset($_POST["action"])) {
        $action = $_POST["action"];

        switch ($action) {
            case "create_database":
                createDatabase($conn);
                break;
            case "delete_database":
                deleteDatabase($conn);
                break;
            case "create_user":
                createUser($conn);
                break;
            case "delete_user":
                deleteUser($conn);
                break;
            case "create_table":
                createTable($conn);
                break;
            case "delete_table":
                deleteTable($conn);
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