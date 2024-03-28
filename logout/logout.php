<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the home page
header('Location: ../home/');
exit;
