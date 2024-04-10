<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the home page
header('Location: ' . $_SERVER['DOCUMENT_ROOT'] . '/home');
exit;
