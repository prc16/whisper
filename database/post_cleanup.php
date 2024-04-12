<?php

include __DIR__ . '/config.php';

$logFile = DOCUMENT_SERVER_ROOT . PHP_LOG_FILE;

date_default_timezone_set('Asia/Kolkata');

// Turn off displaying errors to users
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', $logFile); // Specify the path to your error log file

function process_expired_posts()
{
    // Connect to MariaDB database
    try {
        $hostname = DATABASE_HOSTNAME;
        $username = DATABASE_USERNAME;
        $password = DATABASE_PASSWORD;
        $database = DATABASE_NAME;
        $postMediaDirectory = DOCUMENT_SERVER_ROOT . POSTS_DIRECTORY;

        $conn = new mysqli($hostname, $username, $password, $database);

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to Database: " . $conn->connect_error);
        }

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query for expired posts
        $query = "SELECT id, media_file_id, media_file_ext FROM posts WHERE expire_at <= NOW()";
        echo "Executing query: " . $query . "\n"; // Debug: Print the query before execution
        $result = $conn->query($query);

        // Check if any rows are returned
        if ($result->num_rows == 0) {
            return true;
        }

        // Delete expired posts and associated media files
        while ($row = $result->fetch_assoc()) {
            $post_id = $row["id"];
            $media_file_id = $row["media_file_id"];
            $media_file_ext = $row["media_file_ext"];

            // Delete post from database
            $delete_post_query = "DELETE FROM posts WHERE id = " . $post_id;
            $conn->query($delete_post_query);

            // Delete media file from file system
            $media_file_path = $postMediaDirectory . $media_file_id . "." . $media_file_ext;
            echo "Deleting " . $media_file_path ."\n";
            if (file_exists($media_file_path)) {
                unlink($media_file_path);
            } else {
                echo "Media file " . $media_file_path . " does not exist.\n";
            }

            echo "1 Post deleted.\n";
        }

        // Close connection
        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        if ($conn) {
            $conn->close();
        }
        return false;
    }
    return true;
}

// Run the script every 10 seconds
while (process_expired_posts()) {
    sleep(10); // Sleep for 10 seconds before running the script again
}
