<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        form {
            text-align: center;
            margin-top: 50px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #217dbb;
        }
    </style>
</head>

<body>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

        <button type="submit" name="create_database">Create Database</button>
        <button type="submit" name="create_user">Create User</button>
        <button type="submit" name="create_table">Create Table</button><br>

        <button type="submit" name="delete_database">Delete Database</button>
        <button type="submit" name="delete_user">Delete User</button>
        <button type="submit" name="delete_table">Delete Table</button><br>

    </form>

</body>

</html>
<?php

session_start();

// Constants
define('TABLE_NAME', 'users');
define('DATABASE_USERNAME', 'user');
define('DATABASE_PASSWORD', 'php');
define('DATABASE_NAME', 'socialmedia_db');

$database_hostname = $_SESSION['database_hostname'];
$admin_username = $_SESSION['admin_username'];
$admin_password = $_SESSION['admin_password'];

// Function to establish a database connection
function connectToDatabase()
{
    global $database_hostname;
    global $admin_username;
    global $admin_password;

    $conn = new mysqli($database_hostname, $admin_username, $admin_password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to database
function createDatabase($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database already exists
        echo "Database already exist.<br>";
    } else {
        // Database does not exists, create it
        $createQuery = "CREATE DATABASE " . DATABASE_NAME;
        if ($conn->query($createQuery) === TRUE) {
            echo "Database created successfully.<br>";
        } else {
            echo "Error createing database: " . $conn->error . "<br>";
        }
    }
}

// Function to delete database
function deleteDatabase($conn)
{
    // Check if the database exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DATABASE_NAME . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Database exists, delete it
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

// Function to create the table
function createUser($conn)
{
    global $database_hostname;
    // Check if the user exists before attempting to drop it
    $checkUserQuery = "SELECT 1 FROM mysql.user WHERE user = '" . DATABASE_USERNAME . "' AND host = '$database_hostname'";
    $checkUserResult = $conn->query($checkUserQuery);

    if ($checkUserResult->num_rows > 0) {
        // User already exists
        echo "User already exist.<br>";
    } else {

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
            echo "privileges flushed successfully.<br>";
        } else {
            echo "Error flushing privileges: " . $conn->error . "<br>";
        }
    }
}

// Function to delete the user
function deleteUser($conn)
{
    global $database_hostname;
    // Check if the user exists before attempting to drop it
    $checkUserQuery = "SELECT 1 FROM mysql.user WHERE user = '" . DATABASE_USERNAME . "' AND host = '$database_hostname'";
    $checkUserResult = $conn->query($checkUserQuery);

    if ($checkUserResult->num_rows > 0) {
        // User exists, drop it
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


// Function to create the table
function createTable($conn)
{
    // Select database
    $conn->select_db(DATABASE_NAME);

    // Check if table exists before attempting to creat it
    $checkTableQuery = "SHOW TABLES LIKE '" . TABLE_NAME . "'";
    $checkTableResult = $conn->query($checkTableQuery);

    if ($checkTableResult->num_rows > 0) {
        // Table already exists
        echo "Table already exist<br>";
    } else {
        // Table does not exist, create it
        $createTableQuary = "CREATE TABLE " . TABLE_NAME . " (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(50) NOT NULL UNIQUE,
            username VARCHAR(20) NOT NULL UNIQUE,
            password_hash VARCHAR(60) NOT NULL
        )";

        $createTableResult = $conn->query($createTableQuary);
        if ($createTableResult === TRUE) {
            echo "Table created successfully<br>";
        } else {
            echo "Error creating table: " . $conn->error . "<br>";
        }
    }
}

// Function to delete the table
function deleteTable($conn)
{
    // Select database
    $conn->select_db(DATABASE_NAME);

    // Check if table exists before attempting to delete it
    $checkTableQuery = "SHOW TABLES LIKE '" . TABLE_NAME . "'";
    $checkTableResult = $conn->query($checkTableQuery);

    if ($checkTableResult->num_rows > 0) {
        // Table exists, delete it
        $dropTableQuary = "DROP TABLE " . TABLE_NAME;
        $dropTableResult = $conn->query($dropTableQuary);
        if ($dropTableResult === TRUE) {
            echo "Table dropped successfully.<br>";
        } else {
            // Table does not exist
            echo "Error dropping table: " . $conn->error . "<br>";
        }
    } else {
        echo "Table does not exist.<br>";
    }
}

// Check the form submission and take appropriate action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectToDatabase();

    if (isset($_POST["create_database"])) {
        createDatabase($conn);
    } elseif (isset($_POST["create_user"])) {
        createUser($conn);
    } elseif (isset($_POST["create_table"])) {
        createTable($conn);
    } elseif (isset($_POST["delete_database"])) {
        deleteDatabase($conn);
    } elseif (isset($_POST["delete_user"])) {
        deleteUser($conn);
    } elseif (isset($_POST["delete_table"])) {
        deleteTable($conn);
    }

    $conn->close();
}
?>