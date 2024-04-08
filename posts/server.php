<?php

include_once '../php/all.php';
include_once '../php/database.php';

$conn = getConnection();
if(!$conn) {
    serverMaintenanceResponse();
    exit;
}

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session and action
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        $conn->close();
        exit();
    }

    $userId = $_SESSION['user_id'];

    // Disable autocommit to start a new transaction
    $conn->autocommit(false);

    try {
        // Validate form data
        $action = $_POST['action'] ?? null;
        $postId = $_POST['post_id'] ?? null;
        if (!$action || !in_array($action, ['upvote', 'downvote']) || !$postId || strlen($postId) !== 16) {
            throw new InvalidArgumentException("Invalid action or post ID provided.");
        }

        handleVote($conn, $userId, $postId, $action);

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

// Set character encoding to UTF-8
header('Content-Type: application/json; charset=utf-8');

// Return posts as JSON
if (isset($_SESSION['user_id'])) {
    echo json_encode(getPostsWithVotes($conn, $_SESSION['user_id']));
} else {
    echo json_encode(getPosts($conn));
}

// Close the database connection
$conn->close();
