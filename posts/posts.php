<?php

include '../database/config.php';
include '../database/functions.php';
$conn = getDBConnection();

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session and action
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        exit();
    }

    // Disable autocommit to start a new transaction
    $conn->autocommit(false);

    try {
        validateInput(['action']);
        switch ($_POST['action']) {
            case 'create':
                validateInput(['content']);
                createPost($conn, $_SESSION['user_id'], $_POST['content']);
                break;
            case 'upvote':
            case 'downvote':
                validateInput(['post_id']);
                handleVote($conn, $_SESSION['user_id'], $_POST['post_id'], $_POST['action']);
                break;
            default:
                throw new InvalidArgumentException("Invalid action provided.");
        }

        // Commit the transaction if no exceptions occur
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        handleException($e);
    } finally {
        // Re-enable autocommit
        $conn->autocommit(true);
    }
}

// Return posts with user votes as JSON
header('Content-Type: application/json');
echo json_encode(getPostsWithVotes($conn, $_SESSION['user_id'])); // TODO: handle the case where 'user_id' is not set

// Close the database connection
$conn->close();
