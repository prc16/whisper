<?php

// Retrieve database connection details from the form
$database_hostname = $_POST['database_hostname'];
$database_username = $_POST['database_username'];
$database_password = $_POST['database_password'];

$conn = new mysqli($database_hostname, $database_username, $database_password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$privilege_to_check = 'WITH GRANT OPTION';

// Query to check if the user has the specified privilege
$query = "SHOW GRANTS FOR '$database_username'@'$database_hostname'";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Check if the privilege is granted
        if (strpos($row['Grants for ' . $database_username . '@' . $database_hostname], $privilege_to_check) !== false) {
            // Redirect to another web page
            session_start();

            // code to process and set variables
            $_SESSION['database_hostname'] = $database_hostname;
            $_SESSION['database_username'] = $database_username;
            $_SESSION['database_password'] = $database_password;

            $conn->close();
            header("Location: admin_home.html");
            exit();
        }
    }
} else {
    // Handle the case where the query failed
    echo "Error: " . $conn->error;
    $conn->close();
}
?>