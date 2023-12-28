<?php

// Read the contents of the JSON file
$configFile = 'config.json';
$configData = file_get_contents($configFile);

if ($configData === false) {
    die('Error reading configuration file');
}

// Decode the JSON data into an associative array
$config = json_decode($configData, true);

if ($config === null) {
    die('Error decoding JSON data');
}

// Access the variables from the loaded configuration
$databaseHost = $config['database_host'];
$databaseUser = $config['database_user'];
$databasePassword = $config['database_password'];
$databaseName = $config['database_name'];
$tableName = $config['table_name'];


// Create connection
$conn = new mysqli($databaseHost, $databaseUser, $databasePassword, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
