<?php

// Define privileges as constants
define('PRIVILEGE_TO_CHECK', 'WITH GRANT OPTION');

// Input validation
$database_hostname = $_POST['database_hostname'] ?? '';
$admin_username = $_POST['admin_username'] ?? '';
$admin_password = $_POST['admin_password'] ?? '';

if (empty($database_hostname) || empty($admin_username)) {
    die("Please provide all required information.");
}

try {
    // Database connection
    $conn = new mysqli($database_hostname, $admin_username, $admin_password);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input to prevent SQL injection
    $admin_username = $conn->real_escape_string($admin_username);
    $database_hostname = $conn->real_escape_string($database_hostname);

    // Query to check if the user has the specified privilege
    $query = "SHOW GRANTS FOR '$admin_username'@'$database_hostname'";
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Check if the admin has privileges required
            if (strpos($row['Grants for ' . $admin_username . '@' . $database_hostname], PRIVILEGE_TO_CHECK) !== false) {
                // admin has privileges, start the session
                
                // Secure session handling
                session_start();
                session_regenerate_id();

                // Code to process and set variables
                $_SESSION['database_hostname'] = $database_hostname;
                $_SESSION['admin_username'] = $admin_username;
                $_SESSION['admin_password'] = $admin_password;

                // Release the session lock
                session_write_close();

                // Close the database connection
                $conn->close();

                // Redirect to admin home page
                header("Location: admin_home.php");
                exit();
            }
        }
        // User does not have required privileges
        throw new Exception("Error: User does not have required privileges");
    } else {
        throw new Exception("Error: " . $conn->error);
    }
} catch (Exception $e1) {
    echo $e1->getMessage();
} catch (Exception $e2) {
    error_log("Exception caught: " . $e2->getMessage());
    // log the exception to the screen for debugging purposes
    echo "Exception caught: " . $e2->getMessage();
    // Change the error message on release.
    //echo "An error occurred. Please try again later.";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
