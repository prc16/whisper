<?php

include '../database/connection.php';

/**
 * Establishes a connection to the MySQL database.
 *
 * @return mysqli|null The MySQL database connection object, or null if the connection fails.
 * @throws Exception If the connection to the MySQL database fails.
 */
function getConnection() {
    $conn = new mysqli(DATABASE_HOSTNAME, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    
    // Check if connection was successful
    if ($conn->connect_errno) {
        // Throw a custom exception
        throw new Exception("Failed to connect to Database: " . $conn->connect_error);
    }
    
    return $conn;
}

/**
 * Generates a Universally Unique Identifier (UUID) string.
 *
 * @param int $length The desired length of the UUID. Default is 16.
 * @return string The generated UUID string.
 */
function genUUID($length = 16)
{
    return substr(str_replace('.', '', uniqid('', true)), 0, $length);
}

/**
 * Retrieves posts with corresponding votes for a specific user.
 *
 * @param mysqli $conn The database connection object.
 * @param int $userId The ID of the user whose posts are being retrieved.
 * @param int $limit The maximum number of posts to retrieve (default is 10).
 * @return array An array containing the retrieved posts with associated votes.
 */
function getPostsWithVotes($conn, $userId, $limit = 10)
{
    $sql = "SELECT 
                p.post_id, 
                p.user_id, 
                p.content, 
                p.votes, 
                COALESCE(u.username, 'Anonymous') AS username, 
                IFNULL(v.vote_type, '') AS vote_type 
            FROM 
                posts p 
            LEFT JOIN 
                usernames u ON p.user_id = u.user_id
            LEFT JOIN 
                votes v ON p.post_id = v.post_id AND v.user_id = ? 
            ORDER BY 
                p.id DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $userId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = fetchPosts($result);

    $stmt->close();
    return $posts;
}

/**
 * Retrieves posts from the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $limit The maximum number of posts to retrieve (default is 10).
 * @return array An array containing the retrieved posts.
 */
function getPosts($conn, $limit = 10)
{
    $sql = "SELECT 
                p.post_id,
                p.user_id,
                COALESCE(u.username, 'Anonymous') AS username,
                p.content,
                p.votes 
            FROM
                posts p 
            LEFT JOIN
                usernames u ON p.user_id = u.user_id 
            ORDER BY
                p.id DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = fetchPosts($result);

    $stmt->close();
    return $posts;
}

/**
 * Fetches posts from the database result set.
 *
 * @param mysqli_result $result The result set from a database query.
 * @return array An array containing the fetched posts.
 */
function fetchPosts($result)
{
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    return $posts;
}

function usernameExists($conn, $userId)
{
    $checkSql = "SELECT 1 FROM usernames WHERE user_id=? LIMIT 1";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
}

function getUsername($conn, $userId)
{
    $username = "";

    $sql = "SELECT username FROM usernames WHERE user_id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Check if username is empty, if so, return "Anonymous"
    if (empty($username)) {
        return "Anonymous";
    }

    return $username;
}

function editUsername($conn, $userId, $username)
{
    if (usernameExists($conn, $userId)) {
        // Update username
        $sql = "UPDATE usernames SET username = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new record
        $sql = "INSERT INTO usernames (user_id, username) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userId, $username);
        $stmt->execute();
        $stmt->close();
    }
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
