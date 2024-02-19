<?php

include '../database/config.php';
include '../database/functions.php';
$conn = getDBConnection();

session_start();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action and user_id are set in the session
    if (isset($_POST['action']) && isset($_SESSION['user_id'])) {
        switch ($_POST['action']) {
            case 'create':
                // Create a new post if title and content are provided and not empty
                if (isset($_POST['content']) && !empty($_POST['content'])) {
                    createPost($conn, $_SESSION['user_id'], $_POST['content']);
                }
                break;
            case 'upvote':
            case 'downvote':
                // Handle vote based on the action and postId
                if (isset($_POST['post_id'])) {
                    handleVote($conn, $_SESSION['user_id'], $_POST['post_id'], $_POST['action']);
                }
                break;
        }
    } else {
        // Handle unauthorized access
        http_response_code(401);
        exit();
    }
}

// Return posts with user votes as JSON
header('Content-Type: application/json');
echo json_encode(getPostsWithVotes($conn, $_SESSION['user_id']));

// Close the database connection
$conn->close();
