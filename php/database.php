<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/database/config.php';

/**
 * Establishes a connection to the database.
 *
 * This function connects to the database using the provided credentials
 * and returns a MySQLi connection object if successful.
 *
 * @return mysqli|false Returns a MySQLi connection object if successful, false otherwise.
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
 * This function queries the database to check if a given username exists
 * in the 'users' table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $username The username to check for existence.
 *
 * @return bool Returns true if the username exists, false otherwise.
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
 * Retrieves the username associated with a given user ID from the database.
 *
 * This function queries the database to retrieve the username associated with
 * the specified user ID from the 'users' table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user whose username is to be retrieved.
 *
 * @return string|null Returns the username associated with the specified user ID,
 *                     or null if no username is found for the given user ID.
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
    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $user_id, $username, $password_hash);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


/**
 * Inserts a new user into the database.
 *
 * This function inserts a new user record into the 'users' table with the provided
 * user ID, username, and hashed password.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $user_id The ID of the new user.
 * @param string $username The username of the new user.
 * @param string $password_hash The hashed password of the new user.
 *
 * @return bool Returns true if the user is successfully inserted into the database, false otherwise.
 */
function updateUsername($conn, $user_id, $new_username)
{
    $query = "UPDATE users SET username = ? WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $new_username, $user_id);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


/**
 * Verifies the password for a given username in the database.
 *
 * This function retrieves the stored hashed password from the 'users' table
 * for the specified username and verifies it against the provided hashed password.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $username The username for which to verify the password.
 * @param string $password_hash The hashed password to verify.
 *
 * @return bool Returns true if the provided password matches the stored hashed password for the username, false otherwise.
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
 * Retrieves the user ID associated with a given username from the database.
 *
 * This function queries the database to retrieve the user ID associated with
 * the specified username from the 'users' table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $username The username for which to retrieve the user ID.
 *
 * @return string|false Returns the user ID associated with the specified username,
 *                      or false if no user ID is found for the given username.
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
 * Retrieves the profile picture ID associated with a given user ID from the database.
 *
 * This function queries the database to retrieve the profile picture ID associated with
 * the specified user ID from the 'profile_pictures' table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user for which to retrieve the profile picture ID.
 *
 * @return string|false Returns the profile picture ID associated with the specified user ID,
 *                      or false if no profile picture ID is found for the given user ID.
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
 * Retrieves the file path of the profile picture associated with a given user ID.
 *
 * This function retrieves the profile picture ID associated with the specified user ID
 * and constructs the file path to the corresponding profile picture file.
 * If the profile picture file does not exist, it falls back to a default profile picture.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user for which to retrieve the profile picture.
 *
 * @return string Returns the file path of the profile picture associated with the specified user ID,
 *                or the file path of the default profile picture if no profile picture is found.
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
 * Checks if a profile picture exists for a given user ID in the database.
 *
 * This function queries the database to check if a profile picture exists
 * for the specified user ID in the 'profile_pictures' table.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user for which to check the existence of a profile picture.
 *
 * @return bool Returns true if a profile picture exists for the specified user ID, false otherwise.
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


/**
 * Inserts a profile picture record into the database for a given user ID.
 *
 * This function inserts a record into the 'profile_pictures' table with the provided
 * user ID and profile picture file ID.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user for which to insert the profile picture.
 * @param string $profileFileId The ID of the profile picture file to be associated with the user.
 *
 * @return bool Returns true if the profile picture record is successfully inserted into the database, false otherwise.
 */
function insertProfilePicture($conn, $userId, $profileFileId)
{
    $sql = "INSERT INTO profile_pictures (user_id, profile_file_id) VALUES (?, ?)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userId, $profileFileId);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Updates the profile picture record in the database for a given user ID.
 *
 * This function updates the profile picture record in the 'profile_pictures' table
 * with the provided profile picture file ID for the specified user ID.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user for which to update the profile picture.
 * @param string $profileFileId The new profile picture file ID to be associated with the user.
 *
 * @return bool Returns true if the profile picture record is successfully updated in the database, false otherwise.
 */
function updateProfilePicture($conn, $userId, $profileFileId)
{
    $sql = "UPDATE profile_pictures SET profile_file_id = ? WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $profileFileId, $userId);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


/**
 * Creates a new post in the database.
 *
 * This function inserts a new post into the 'posts' table with the provided
 * user ID, post details, and optional media file information.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $user_id The ID of the user creating the post.
 * @param bool $anon_post Flag indicating whether the post is anonymous (true) or not (false).
 * @param string $post_text The text content of the post.
 * @param string|null $media_file_id The ID of the media file associated with the post, if any.
 * @param string|null $media_file_ext The file extension of the media file associated with the post, if any.
 * @param string|null $expire_at The expiration date/time of the post, if any.
 *
 * @return bool Returns true if the post is successfully created in the database, false otherwise.
 */
function createPost($conn, $user_id, $anon_post, $post_text, $media_file_id = null, $media_file_ext = null, $expire_at = null)
{
    $post_id = genUUID();
    $query = "INSERT INTO posts (post_id, user_id, anon_post, post_text, media_file_id, media_file_ext, expire_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissss", $post_id, $user_id, $anon_post, $post_text, $media_file_id, $media_file_ext, $expire_at);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


function getPosts($conn, $limit = 50)
{
    $sql = "SELECT 
                p.post_id, 
                p.user_id, 
                u.username AS username, 
                p.anon_post, 
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

function getUserPosts($conn, $userId, $limit = 50)
{
    $sql = "SELECT 
                p.post_id, 
                p.user_id, 
                u.username AS username, 
                p.anon_post, 
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
            WHERE 
                p.user_id = ? AND 
                p.anon_post = 0 
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

function getPostsWithVotes($conn, $voterUserId, $limit = 50)
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
    $stmt->bind_param("si", $voterUserId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = fetchPosts($result);

    $stmt->close();
    return $posts;
}

function getUserPostsWithVotes($conn, $voterUserId, $userId, $limit = 50)
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
            WHERE 
                p.user_id = ? AND 
                p.anon_post = 0 
            ORDER BY 
                p.id DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $voterUserId, $userId, $limit);
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
        if ($row['anon_post']) {
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

function fetchRows($result)
{
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function getFollowees($conn, $userId, $limit = 50)
{
    $sql = "SELECT 
                f.followee_id, 
                u.username AS username, 
                pp.profile_file_id AS profile_file_id 
            FROM 
                followers f 
            LEFT JOIN 
                users u ON f.followee_id = u.user_id 
            LEFT JOIN 
                profile_pictures pp ON f.followee_id = pp.user_id 
            WHERE 
                f.follower_id = ? 
            ORDER BY 
                f.id DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $userId, $limit);
    $stmt->execute();
    $stmt->execute(); 
    $result = $stmt->get_result();

    $followees = fetchRows($result);

    $stmt->close();
    return $followees;
}

function getFollowers($conn, $userId, $limit = 50)
{
    $sql = "SELECT 
                f.follower_id, 
                u.username AS username, 
                pp.profile_file_id AS profile_file_id 
            FROM 
                followers f 
            LEFT JOIN 
                users u ON f.follower_id = u.user_id 
            LEFT JOIN 
                profile_pictures pp ON f.follower_id = pp.user_id 
            WHERE 
                f.followee_id = ? 
            ORDER BY 
                f.id DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $userId, $limit);
    $stmt->execute();
    $stmt->execute(); 
    $result = $stmt->get_result();

    $followers = fetchRows($result);

    $stmt->close();
    return $followers;
}


/**
 * Updates the vote count of a post in the database.
 *
 * This function increments or decrements the vote count of a post in the 'posts' table
 * by the specified amount.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $postId The ID of the post for which to update the vote count.
 * @param int $voteIncrement The amount by which to increment or decrement the vote count.
 *
 * @return bool Returns true if the vote count of the post is successfully updated in the database, false otherwise.
 */
function updatePostVotes($conn, $postId, $voteIncrement)
{
    $sql = "UPDATE posts SET vote_count = vote_count + ? WHERE post_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $voteIncrement, $postId);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Creates or updates a vote record for a user on a post in the database.
 *
 * This function inserts a new vote record into the 'votes' table with the provided
 * user ID, post ID, and vote type. If a vote record already exists for the user
 * on the specified post, it updates the existing vote record with the new vote type.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user casting the vote.
 * @param string $postId The ID of the post on which the vote is being cast.
 * @param string $voteType The type of vote ('upvote' or 'downvote').
 *
 * @return bool Returns true if the vote record is successfully created or updated in the database, false otherwise.
 */
function createVote($conn, $userId, $postId, $voteType)
{
    $sql = "INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE vote_type = VALUES(vote_type)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $userId, $postId, $voteType);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Retrieves the type of vote cast by a user on a specific post from the database.
 *
 * This function queries the 'votes' table to retrieve the type of vote cast by the specified
 * user on the specified post.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user whose vote type is being retrieved.
 * @param string $postId The ID of the post for which the user's vote type is being retrieved.
 *
 * @return string|null Returns the type of vote cast by the user on the post ('upvote', 'downvote'),
 *                     or null if the user has not cast a vote on the post.
 */
function getUserVoteType($conn, $userId, $postId)
{
    $sql = "SELECT vote_type FROM votes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['vote_type'];
    }

    return null;
}

/**
 * Removes a vote record cast by a user on a specific post from the database.
 *
 * This function deletes the vote record from the 'votes' table where the user
 * with the specified ID has cast a vote on the specified post.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user whose vote record is being removed.
 * @param string $postId The ID of the post for which the user's vote record is being removed.
 *
 * @return bool Returns true if the vote record is successfully removed from the database, false otherwise.
 */
function removeUserVote($conn, $userId, $postId)
{
    $sql = "DELETE FROM votes WHERE user_id = ? AND post_id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userId, $postId);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Handles the voting process for a user on a specific post.
 *
 * This function manages the process of handling votes cast by a user on a post,
 * including updating the post's vote count and creating or removing the user's vote record.
 *
 * @param mysqli $conn The MySQLi connection object.
 * @param string $userId The ID of the user casting the vote.
 * @param string $postId The ID of the post on which the vote is being cast.
 * @param string $voteType The type of vote ('upvote' or 'downvote') being cast by the user.
 *
 * @return bool Returns true if the voting process is successfully handled, false otherwise.
 */
function handleVote($conn, $userId, $postId, $voteType)
{
    // Turn off autocommit
    $conn->autocommit(false);

    // Get the current vote type given by the user for the post
    $currentVoteType = getUserVoteType($conn, $userId, $postId);

    try {
        // Check if the current vote type matches the requested vote type
        if ($currentVoteType === $voteType) {
            // If they match, remove the user's vote
            if (!removeUserVote($conn, $userId, $postId) || !updatePostVotes($conn, $postId, ($voteType === 'upvote' ? -1 : 1))) {
                throw new Exception("Failed to remove user vote or update post votes");
            }
        } else {
            // If they don't match, remove the user's current vote and add new vote
            if ($currentVoteType !== null) {
                if (!removeUserVote($conn, $userId, $postId) || !updatePostVotes($conn, $postId, ($currentVoteType === 'upvote' ? -1 : 1))) {
                    throw new Exception("Failed to remove user vote or update post votes");
                }
            }

            if (!updatePostVotes($conn, $postId, ($voteType === 'upvote' ? 1 : -1)) || !createVote($conn, $userId, $postId, $voteType)) {
                throw new Exception("Failed to update post votes or create new vote");
            }
        }

        // Commit changes
        $conn->commit();

        // Turn on autocommit
        $conn->autocommit(true);

        return true; // All operations succeeded
    } catch (Exception $e) {
        // Rollback changes
        $conn->rollback();

        // Turn on autocommit
        $conn->autocommit(true);

        error_log($e->getMessage());
        return false; // Return false indicating failure
    }
}
