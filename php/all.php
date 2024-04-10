<?php

// Set the default timezone to New York
date_default_timezone_set('Asia/Kolkata');

// Turn off displaying errors to users
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/error.log'); // Specify the path to your error log file
