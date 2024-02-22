<?php

/**
 * Generates a Universally Unique Identifier (UUID) string.
 *
 * @param int $length The desired length of the UUID. Default is 16.
 * @return string The generated UUID string.
 */
function genUUID($length = 16) {
    return substr(str_replace('.', '', uniqid('', true)), 0, $length);
}

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
            LEFT JOIN votes v ON p.post_id = v.post_id AND v.user_id = ?";
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

    // Prepare and execute the INSERT query for posts
    $sqlPost = "INSERT INTO posts (post_id, user_id, content, votes) VALUES (?, ?, ?, 1)";
    $stmtPost = $conn->prepare($sqlPost);
    $stmtPost->bind_param("sss", $postId, $userId, $content);
    $stmtPost->execute();

    // Add upvote entry into votes table
    createVote($conn, $userId, $postId, 'upvote');
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
    // Get the current vote type given by the user for the post
    $currentVoteType = getUserVoteType($conn, $userId, $postId);

    // Check if the current vote type matches the requested vote type
    if ($currentVoteType === $voteType) {
        // If they match, remove the user's vote
        removeUserVote($conn, $userId, $postId);
        updatePostVotes($conn, $postId, ($voteType === 'upvote' ? -1 : 1));
    } else {
        // If they don't match, remove the user's current vote and add new vote
        if ($currentVoteType !== null) {
            removeUserVote($conn, $userId, $postId);
            updatePostVotes($conn, $postId, ($currentVoteType === 'upvote' ? -1 : 1));
        }

        updatePostVotes($conn, $postId, ($voteType === 'upvote' ? 1 : -1));
        createVote($conn, $userId, $postId, $voteType);
    }
}

/**
 * Handles exceptions by setting appropriate HTTP response codes and logging the error message.
 *
 * @param Exception $e The exception to handle.
 */
function handleException($e)
{
    if ($e instanceof InvalidArgumentException) {
        http_response_code(400); // Bad Request
    } else {
        http_response_code(500); // Internal Server Error
    }
    $errorMessage = $e->getMessage();
    error_log("Error: $errorMessage");
    echo json_encode(['error' => $errorMessage]);
    exit();
}

/**
 * Validate JSON Data
 * 
 * This function validates the presence of required fields in a JSON data object.
 *
 * @param array $data   The JSON data object to be validated.
 * @param array $fields An array of field names that are required in the data object.
 * 
 * @throws InvalidArgumentException if a required field is missing or empty in the data object.
 */
function validateJsonData($data, $fields)
{
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new InvalidArgumentException(ucfirst($field) . " is required.");
        }
    }
}
