<?php

// Function to establish a database connection
function connectToDatabase($hostname, $username, $password)
{
    try {
        // Create a new MySQLi connection
        $conn = new mysqli($hostname, $username, $password);

        // Check for connection errors
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    } catch (Exception $e) {
        // Log the exception or handle it appropriately
        die("Error: " . $e->getMessage());
    }
}


function databaseExists($conn, $database_name)
{
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $database_name . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function userExists($conn, $hostname, $username)
{
    $query = "SELECT 1 FROM mysql.user WHERE user = '" . $username . "' AND host = '" . $hostname . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function tableExists($conn, $database_name, $tableName)
{
    // Select database
    $conn->select_db($database_name);

    $query = "SHOW TABLES LIKE '" . $tableName . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to create a database
function createDatabase($conn, $database_name)
{
    if (!databaseExists($conn, $database_name)) {
        $query = "CREATE DATABASE " . $database_name;
        $result = $conn->query($query);

        if ($result === TRUE) {
            echo "Database created successfully.<br>";
        } else {
            echo "Error creating database: " . $conn->error . "<br>";
        }
    } else {
        echo "Database already exists.<br>";
    }
}

// Function to delete a database
function deleteDatabase($conn, $database_name)
{
    // Check if the database exists
    if (databaseExists($conn, $database_name)) {
        // Database exists; delete it
        $dropQuery = "DROP DATABASE " . $database_name;
        $dropResult = $conn->query($dropQuery);
        if ($dropResult === TRUE) {
            echo "Database deleted successfully.<br>";
        } else {
            echo "Error deleting database: " . $conn->error . "<br>";
        }
    } else {
        // Database does not exist
        echo "Database does not exist.<br>";
    }
}

function grantPrivileges($conn, $hostname, $username, $database_name, $privileges)
{
    $grantQuery = "GRANT " . $privileges . " ON " . $database_name . ".* TO '" . $username . "'@'" . $hostname . "'";
    $grantResult = $conn->query($grantQuery);
    if ($grantResult === TRUE) {
        echo "Privileges granted successfully.<br>";
    } else {
        echo "Error granting privileges: " . $conn->error . "<br>";
    }
}

function revokePrivileges($conn, $hostname, $username, $database_name)
{
    $revokeQuery = "REVOKE ALL PRIVILEGES ON " . $database_name . ".* FROM '" . $username . "'@'" . $hostname . "'";
    $revokeResult = $conn->query($revokeQuery);
    if ($revokeResult === TRUE) {
        echo "Privileges revoked successfully.<br>";
    } else {
        echo "Error revoking privileges: " . $conn->error . "<br>";
    }
}

function flushPrivileges($conn)
{
    $query = "FLUSH PRIVILEGES";
    $result = $conn->query($query);

    if ($result === TRUE) {
        echo "Privileges flushed successfully.<br>";
    } else {
        echo "Error flushing privileges: " . $conn->error . "<br>";
    }
}

// Function to create a user
function createUser($conn, $hostname, $username, $password, $database_name, $privileges)
{
    if (!userExists($conn, $hostname, $username)) {
        $query = "CREATE USER '" . $username . "'@'" . $hostname . "' IDENTIFIED BY '" . $password . "'";
        $result = $conn->query($query);

        if ($result === TRUE) {
            echo "User created successfully.<br>";
            grantPrivileges($conn, $hostname, $username, $database_name, $privileges);
            flushPrivileges($conn);
        } else {
            echo "Error creating user: " . $conn->error . "<br>";
        }
    } else {
        echo "User already exists.<br>";
    }
}

// Function to delete a user
function deleteUser($conn, $hostname, $username, $database_name)
{
    // Check if the user exists
    if (userExists($conn, $hostname, $username)) {
        // Revoke privileges
        revokePrivileges($conn, $hostname, $username, $database_name);
        flushPrivileges($conn);

        // Delete the user
        $dropQuery = "DROP USER '" . $username . "'@'" . $hostname . "'";
        $dropResult = $conn->query($dropQuery);
        if ($dropResult === TRUE) {
            echo "User deleted successfully.<br>";
        } else {
            echo "Error deleting user: " . $conn->error . "<br>";
        }
    } else {
        // User does not exist
        echo "User does not exist.<br>";
    }
}


// Function to create a table
function createTable($conn, $database_name, $table_name, $columns)
{
    // Check if the database exists
    if (databaseExists($conn, $database_name)) {
        // Database exists; select it
        $conn->select_db($database_name);
        if (!tableExists($conn, $database_name, $table_name)) {
            $query = "CREATE TABLE " . $table_name . " (" . $columns . ")";
            $result = $conn->query($query);

            if ($result === TRUE) {
                echo "Table created successfully.<br>";
            } else {
                echo "Error creating table: " . $conn->error . "<br>";
            }
        } else {
            echo "Unable to create table, table '" . $table_name . "' already exists.<br>";
        }
    } else {
        // Database does not exist
        echo "Unable to create table, database '" . $database_name . "' does not exist.<br>";
    }
}

// Function to delete a table
function deleteTable($conn, $database_name, $table_name)
{
    // Check if the database exists
    if (databaseExists($conn, $database_name)) {
        // Database exists; select it
        $conn->select_db($database_name);
        // Check if the table exists
        if (tableExists($conn, $database_name, $table_name)) {
            // Table exists; delete it
            $dropQuery = "DROP TABLE " . $table_name;
            $dropResult = $conn->query($dropQuery);
            if ($dropResult === TRUE) {
                echo "Table deleted successfully.<br>";
            } else {
                echo "Error deleting table: " . $conn->error . "<br>";
            }
        } else {
            // Table does not exist
            echo "Unable to delete table, table '" . $table_name . "' does not exist.<br>";
        }
    } else {
        // Database does not exist
        echo "Unable to delete table, database '" . $database_name . "' does not exist.<br>";
    }
}
