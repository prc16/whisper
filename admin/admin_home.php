<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin home</title>
</head>
<body>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <button type="submit" name="create_table">Create Table</button>
        <button type="submit" name="delete_table">Delete Table</button>
    </form>

</body>
</html>

<?php

// Database connection details
$database_hostname ="localhost";
$database_username = "admin";
$database_password = "password";
$database_name = "socialmedia_db";

// Create connection
$conn = new mysqli($database_hostname, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create the table
function createTable() {
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
function deleteTable() {
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
    createTable();
}

// Check if the form is submitted for table deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_table"])) {
    deleteTable();
}

$conn->close();
?>
