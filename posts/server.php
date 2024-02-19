<?php

include '../database/config.php';
include '../database/functions.php';
$conn = getDBConnection();

session_start();

/**
 * Function to get posts with user votes
 *
 * @param $conn - The database connection
 * @param $userId - The user ID for filtering votes
 * @return array - Array of posts with user votes
 */
function getPostsWithVotes($conn, $userId)
{
    $sql = "SELECT p.*, IFNULL(v.vote_type, '') AS vote_type 
            FROM posts p
            LEFT JOIN (
                SELECT post_id, vote_type
                FROM votes
                WHERE user_id = ?
            ) v ON p.post_id = v.post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $posts = $result->fetch_all(MYSQLI_ASSOC);

    return $posts;
}

/**
 * Function to create a new post in the database
 *
 * @param $conn - The database connection
 * @param $userId - The ID of the user creating the post
 * @param $content - The content of the post
 */
function createPost($conn, $userId, $content)
{
    $postId = genUUID();
    $sql = "INSERT INTO posts (post_id, user_id, content, votes) VALUES (?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $postId, $userId, $content);
    $stmt->execute();
}

/**
 * Function to update the vote count of a post in the database
 *
 * @param $conn - The database connection
 * @param $postId - The ID of the post to update
 * @param $voteIncrement - The amount by which to increment the votes (+1 or -1)
 */
function updatePostVotes($conn, $postId, $voteIncrement)
{
    // Prepare and execute an SQL statement to update the post's vote count by a specified increment
    $sql = "UPDATE posts SET votes = votes + ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $voteIncrement, $postId);
    $stmt->execute();
    $stmt->close();
}

/**
 * Function to create a new vote record in the database or update an existing one if it already exists
 *
 * @param $conn - The database connection
 * @param $userId - The ID of the user who voted
 * @param $postId - The ID of the post being voted on
 * @param $voteType - The type of vote (upvote or downvote)
 */
function createVote($conn, $userId, $postId, $voteType)
{
    // Prepare and execute an SQL statement to insert a new vote record or update an existing one based on user and post IDs
    $sql = "INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE vote_type = VALUES(vote_type)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $userId, $postId, $voteType);
    $stmt->execute();
    $stmt->close();
}

/**
 * Function to retrieve the type of vote (upvote or downvote) given by a user for a specific post
 *
 * @param $conn - The database connection
 * @param $userId - The ID of the user
 * @param $postId - The ID of the post
 * @return string|null - The type of vote given by the user for the post, or null if no vote exists
 */
function getUserVoteType($conn, $userId, $postId)
{
    // Prepare and execute an SQL statement to select the vote type for a specific user and post
    $sql = "SELECT vote_type FROM votes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a vote record exists for the user and post, and return the vote type if found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['vote_type'];
    }

    // Return null if no vote record exists for the user and post
    return null;
}

/**
 * Function to remove a user's vote from a specific post in the database
 *
 * @param $conn - The database connection
 * @param $userId - The ID of the user whose vote will be removed
 * @param $postId - The ID of the post from which the user's vote will be removed
 */
function removeUserVote($conn, $userId, $postId)
{
    // Prepare and execute an SQL statement to delete the user's vote record for a specific post
    $sql = "DELETE FROM votes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userId, $postId);
    $stmt->execute();
    $stmt->close();
}


/**
 * Function to handle user voting on a post with transaction support
 *
 * @param $conn - The database connection
 * @param $userId - The ID of the user voting
 * @param $postId - The ID of the post being voted on
 * @param $voteType - The type of vote (upvote or downvote)
 */
function handleVote($conn, $userId, $postId, $voteType)
{
    // Disable autocommit to start a new transaction
    $conn->autocommit(false);

    try {
        // Get the current vote type given by the user for the post
        $currentVoteType = getUserVoteType($conn, $userId, $postId);

        // Check if the current vote type matches the requested vote type
        if ($currentVoteType === $voteType) {
            // If they match, remove the user's vote and update the post votes accordingly
            removeUserVote($conn, $userId, $postId);
            updatePostVotes($conn, $postId, ($voteType === 'upvote' ? -1 : 1));
        } else {
            // If they don't match, handle the change in vote type and update the post votes
            if ($currentVoteType !== null) {
                removeUserVote($conn, $userId, $postId);
                updatePostVotes($conn, $postId, ($currentVoteType === 'upvote' ? -1 : 1));
            }

            updatePostVotes($conn, $postId, ($voteType === 'upvote' ? 1 : -1));
            createVote($conn, $userId, $postId, $voteType);
        }

        // Commit the transaction if no exceptions occur
        $conn->commit();
    } catch (Exception $e) {
        // Roll back the transaction and re-throw the exception
        $conn->rollback();
        throw $e;
    } finally {
        // Re-enable autocommit after handling the voting process
        $conn->autocommit(true);
    }
}

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
                if (isset($_POST['postId'])) {
                    handleVote($conn, $_SESSION['user_id'], $_POST['postId'], $_POST['action']);
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
