<?php
// Assume you have a database connection already established

// Fetch profile info from the database
// For simplicity, let's assume the data is fetched from the database
$profile_picture = '../uploads/Alphatester0----.jpg'; // This should be fetched from the database
$username = 'Alphatester'; // This should be fetched from the database

// Return profile info as JSON
echo json_encode(array('profile_picture' => $profile_picture, 'username' => $username));
?>
