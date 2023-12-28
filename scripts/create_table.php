<?php

// Read the contents of the JSON file
$configFile = 'admin_config.json';
$configData = file_get_contents($configFile);

if ($configData === false) {
    die('Error reading configuration file');
}

// Decode the JSON data into an associative array
$config = json_decode($configData, true);

if ($config === null) {
    die('Error decoding JSON data');
}

// Database connection details
$host = $config['database']['host'];
$username = $config['database']['username'];
$password = $config['database']['password'];
$dbname = $config['database']['dbname'];

// Table details
$tableName = $config['table']['name'];
$columns = $config['table']['columns'];

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Create table
$createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (";
foreach ($columns as $columnName => $columnType) {
    $createTableQuery .= "$columnName $columnType, ";
}
$createTableQuery = rtrim($createTableQuery, ", ");
$createTableQuery .= ")";

if ($conn->query($createTableQuery) === TRUE) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}


// Close the connection
$conn->close();
?>
