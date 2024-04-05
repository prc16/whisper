<?php

include_once '../php/all.php';
include_once '../database/config.php';

/**
 * Establishes a database connection using the provided credentials and returns the connection object.
 *
 * Redirects to maintenance page on error
 */
function getConnection()
{
    $hostname = DATABASE_HOSTNAME;
    $username = DATABASE_USERNAME;
    $password = DATABASE_PASSWORD;
    $database = DATABASE_NAME;
    try {
        $conn = new mysqli($hostname, $username, $password, $database);


        if ($conn->connect_error) {
            throw new Exception("Failed to connect to Database: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        error_log($e);
        header('Location: ../maintenance/');
        exit();
    }

    return $conn;
}


/**
 * Checks if a username already exists in the users table.
 *
 * This function queries the users table to check if the provided username already exists.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 * @param string $username - The username to check for existence.
 * @return bool - Returns true if the username exists, false otherwise.
 */
function usernameExists($conn, $username)
{
    $checkSql = "SELECT 1 FROM users WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $username); // Corrected from $userId to $username
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
}


/**
 * Inserts a new user into the users table.
 *
 * This function inserts a new user into the users table with the provided user_id, username, and password_hash.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 * @param string $user_id - The user ID of the new user.
 * @param string $username - The username of the new user.
 * @param string $password_hash - The hashed password of the new user.
 * @return bool - Returns true if the user was successfully inserted, false otherwise.
 */
function insertUser($conn, $user_id, $username, $password_hash)
{
    $query = "INSERT INTO users (user_id, username, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $user_id, $username, $password_hash);
    return $stmt->execute();
}


/**
 * Verifies if the provided password matches the stored password hash for the given username.
 *
 * This function retrieves the stored password hash for the provided username and verifies
 * if it matches the provided password hash after hashing it using password_verify().
 *
 * @param mysqli $conn - The MySQLi database connection object.
 * @param string $username - The username for which to verify the password.
 * @param string $password_hash - The hashed password to verify.
 * @return bool - Returns true if the password matches the stored hash, false otherwise.
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
 * Retrieves the user ID for the given username from the users table.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 * @param string $username - The username for which to retrieve the user ID.
 * @return string|false - Returns the user ID if the username exists, or false if not found.
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
