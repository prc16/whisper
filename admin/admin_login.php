<?php

// Define privileges as constants
define('PRIVILEGE_TO_CHECK', 'WITH GRANT OPTION');

// Input validation
$database_hostname = isset($_POST['database_hostname']) ? $_POST['database_hostname'] : '';
$database_username = isset($_POST['database_username']) ? $_POST['database_username'] : '';
$database_password = isset($_POST['database_password']) ? $_POST['database_password'] : '';

if (empty($database_hostname) || empty($database_username) || empty($database_password)) {
    die("Please provide all required information.");
}

try {
    // Database connection
    $conn = new mysqli($database_hostname, $database_username, $database_password);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input to prevent SQL injection
    $database_username = $conn->real_escape_string($database_username);
    $database_hostname = $conn->real_escape_string($database_hostname);

    // Query to check if the user has the specified privilege
    $query = "SHOW GRANTS FOR '$database_username'@'$database_hostname'";
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Check if the privilege is granted
            if (strpos($row['Grants for ' . $database_username . '@' . $database_hostname], PRIVILEGE_TO_CHECK) !== false) {
                // Secure session handling
                session_start();
                session_regenerate_id();

                // Code to process and set variables
                $_SESSION['database_hostname'] = $database_hostname;
                $_SESSION['database_username'] = $database_username;
                $_SESSION['database_password'] = $database_password;

                // Close the database connection
                $conn->close();

                // Redirect to admin home page
                header("Location: admin_home.html");
                exit();
            }
        }
    } else {
        // Handle the case where the query failed
        echo "Error: " . $conn->error;
    }
} catch (Exception $e) {
    // Handle exceptions, if any
    echo "Error: " . $e->getMessage();
} finally {
    // Close the database connection
    $conn->close();
}

?>