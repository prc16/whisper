<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/database/config.php';

/**
 * Establishes a connection to the database.
 *
 * @throws Exception If there is an error connecting to the database.
 * @return mysqli|null Returns a MySQLi object representing the database connection,
 *                     or null if the connection attempt fails.
 */
function getConnection()
{
    try {
        $hostname = DATABASE_HOSTNAME;
        $username = DATABASE_USERNAME;
        $password = DATABASE_PASSWORD;
        $database = DATABASE_NAME;

        $conn = new mysqli($hostname, $username, $password, $database);

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to Database: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }

    return $conn;
}



/**
 * Checks if a username exists in the database.
 *
 * @param mysqli $conn    The mysqli database connection object.
 * @param string $username  The username to be checked.
 *
 * @return bool           Returns true if the username exists, false otherwise.
 */
function usernameExists($conn, $username)
{
    $checkSql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}


/**
 * Retrieves the username associated with the provided user ID from the database.
 *
 * This function executes a SQL query to fetch the username corresponding to the given user ID from the 'users' table in the database.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $userId The user ID for which the username needs to be retrieved.
 * @return string The username associated with the provided user ID. Returns an empty string if no username is found.
 */
function getUsername($conn, $userId)
{
    $username = "";

    $sql = "SELECT username FROM users WHERE user_id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    return $username;
}


/**
 * Inserts a new user into the 'users' table in the database.
 *
 * This function prepares and executes a SQL query to insert a new user into the 'users' table.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $user_id The user ID of the new user.
 * @param string $username The username of the new user.
 * @param string $password_hash The hashed password of the new user.
 * @return bool Returns true if the user insertion was successful, false otherwise.
 */
function insertUser($conn, $user_id, $username, $password_hash)
{
    $query = "INSERT INTO users (user_id, username, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $user_id, $username, $password_hash);
    return $stmt->execute();
}


/**
 * Updates the username of a user in the database.
 *
 * This function updates the username of a user with the specified user_id
 * in the database to the new_username provided.
 *
 * @param mysqli $conn        The mysqli database connection object.
 * @param string $user_id     The ID of the user whose username is to be updated.
 * @param string $new_username The new username to be set for the user.
 *
 * @return bool               Returns true if the username is successfully updated, false otherwise.
 */
function updateUsername($conn, $user_id, $new_username)
{
    $query = "UPDATE users SET username = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $new_username, $user_id);
    return $stmt->execute();
}


/**
 * Verifies the password hash against the stored hash for the given username in the 'users' table.
 *
 * This function executes a SQL query to retrieve the stored password hash for the provided username from the 'users' table.
 * It then compares the provided password hash with the retrieved hash using password_verify function.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $username The username for which the password hash needs to be verified.
 * @param string $password_hash The hashed password to be verified against the stored hash.
 * @return bool Returns true if the provided password hash matches the stored hash for the username, false otherwise.
 */
function verifyPassword($conn, $username, $password_hash)
{
    $stored_hash = '';
    $query = "SELECT password_hash FROM users WHERE username = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_hash);
    $stmt->fetch();
    $stmt->close();

    return ($stored_hash !== null && password_verify($password_hash, $stored_hash));
}


/**
 * Retrieves the user ID associated with the provided username from the database.
 *
 * This function executes a SQL query to fetch the user ID corresponding to the given username from the 'users' table in the database.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $username The username for which the user ID needs to be retrieved.
 * @return mixed Returns the user ID associated with the provided username if found, or false if the username is not found.
 */
function getUserId($conn, $username)
{
    $query = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $user_id;
    } else {
        $stmt->close();
        return false;
    }
}


/**
 * Retrieves the profile picture ID associated with the provided user ID from the database.
 *
 * This function executes a SQL query to fetch the profile picture ID corresponding to the given user ID from the 'profile_pictures' table in the database.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $userId The user ID for which the profile picture ID needs to be retrieved.
 * @return string The profile picture ID associated with the provided user ID. Returns an empty string if no profile picture ID is found.
 */
function getProfilePictureId($conn, $userId)
{
    $sql = "SELECT profile_file_id FROM profile_pictures WHERE user_id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($profileFileId);
    if ($stmt->fetch()) {
        $stmt->close();
        return $profileFileId;
    } else {
        $stmt->close();
        return false;
    }
}


/**
 * Retrieves the file path of the profile picture associated with the provided user ID.
 *
 * This function first obtains the profile picture ID for the given user ID using the getProfilePictureId function.
 * It then constructs the file path of the profile picture based on the obtained ID.
 * If the profile picture file does not exist, it returns the path of the default profile picture.
 *
 * @param mysqli $conn A mysqli database connection object.
 * @param string $userId The user ID for which the profile picture file path needs to be retrieved.
 * @return string The file path of the profile picture associated with the provided user ID. Returns the path of the default profile picture if no specific profile picture is found.
 */
function getProfilePicture($conn, $userId)
{
    $pictureId = getProfilePictureId($conn, $userId);
    $picture = PROFILES_DIRECTORY . $pictureId . '.jpg';
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $picture)) {
        $picture = DEFAULT_PROFILE;
    }
    return $picture;
}


/**
 * Checks if a profile picture exists for the given user ID in the database.
 *
 * @param mysqli $conn    The mysqli database connection object.
 * @param string $userId  The ID of the user whose profile picture is to be checked.
 *
 * @return bool           Returns true if a profile picture exists for the user, false otherwise.
 */
function profilePictureExists($conn, $userId)
{
    $checkSql = "SELECT COUNT(*) FROM profile_pictures WHERE user_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}



function insertProfilePicture($conn, $userId, $profileFileId)
{
    $sql = "INSERT INTO profile_pictures (user_id, profile_file_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userId, $profileFileId);
    return $stmt->execute();
}


function updateProfilePicture($conn, $userId, $profileFileId)
{
    $sql = "UPDATE profile_pictures SET profile_file_id = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $profileFileId, $userId);
    return $stmt->execute();
}


/**
 * Create a new post in the database.
 *
 * This function inserts a new post into the 'posts' table in the database.
 *
 * @param mysqli $conn          The mysqli database connection object.
 * @param string $user_id       The ID of the user creating the post.
 * @param string $post_text       The post_text of the post.
 * @param string $media_file_id (Optional) The ID of the media file associated with the post.
 * @param string $media_file_ext (Optional) The extension of the media file.
 * @param string $expire_at     (Optional) The expiration date and time of the post.
 *
 * @return bool                 Returns true if the post is successfully created, false otherwise.
 */
function createPost($conn, $user_id, $anon_post, $post_text, $media_file_id = null, $media_file_ext = null, $expire_at = null)
{
    $post_id = genUUID();
    $query = "INSERT INTO posts (post_id, user_id, anon_post, post_text, media_file_id, media_file_ext, expire_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssissss", $post_id, $user_id, $anon_post, $post_text, $media_file_id, $media_file_ext, $expire_at);
    return $stmt->execute();
}


function getPosts($conn, $limit = 10)
{
    $sql = "SELECT 
                p.post_id, 
                p.user_id, 
                u.username AS username, 
                p.post_text, 
                p.vote_count, 
                p.media_file_id, 
                p.media_file_ext, 
                pp.profile_file_id AS profile_file_id 
            FROM 
                posts p 
            LEFT JOIN 
                users u ON p.user_id = u.user_id 
            LEFT JOIN 
                profile_pictures pp ON p.user_id = pp.user_id 
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

function getPostsWithVotes($conn, $userId, $limit = 50)
{
    $sql = "SELECT 
                p.post_id, 
                u.username AS username, 
                p.anon_post, 
                p.post_text, 
                p.vote_count, 
                p.media_file_id, 
                p.media_file_ext, 
                pp.profile_file_id AS profile_file_id, 
                v.vote_type AS vote_type
            FROM 
                posts p 
            LEFT JOIN 
                users u ON p.user_id = u.user_id 
            LEFT JOIN 
                profile_pictures pp ON p.user_id = pp.user_id 
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

function fetchPosts($result)
{
    $posts = [];
    while ($row = $result->fetch_assoc()) {

        // Check if 'anon_post' key exists in the row
        if (isset($row['anon_post']) && $row['anon_post']) {
            $row['username'] = 'Anonymous';
            $row['profile_file_id'] = null;
            $row['profile_file_path'] = DEFAULT_PROFILE;
        } else {
            $row['profile_file_path'] = PROFILES_DIRECTORY . $row['profile_file_id'] . '.jpg';
            // Check if file exists and is a file
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row['profile_file_path']) && is_file($_SERVER['DOCUMENT_ROOT'] . $row['profile_file_path'])) {
                // File exists and is a file
            } else {
                // Use default profile if file doesn't exist or is not a file
                $row['profile_file_path'] = DEFAULT_PROFILE;
            }
        }

        $row['post_file_path'] = POSTS_DIRECTORY . $row['media_file_id'] . '.' . $row['media_file_ext'];
        // Check if file exists and is a file
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row['post_file_path']) && is_file($_SERVER['DOCUMENT_ROOT'] . $row['post_file_path'])) {
            // File exists and is a file
        } else {
            // Set post_file_path to empty if file doesn't exist or is not a file
            $row['post_file_path'] = '';
        }

        $posts[] = $row;
    }
    return $posts;
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
    $sql = "UPDATE posts SET vote_count = vote_count + ? WHERE post_id = ?";
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
}
