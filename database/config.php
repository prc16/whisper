<?php

//Define database connection parameters
define('DATABASE_HOSTNAME', 'localhost');
define('DATABASE_USERNAME', 'root');
define('DATABASE_PASSWORD', '');
define('DATABASE_NAME', 'whisper_db');

// Define the document server root directory
define('DOCUMENT_SERVER_ROOT', '/opt/lampp/htdocs');

// Difine every other path ralative to document server root directory
define('PROFILES_DIRECTORY', '/uploads/profile_images/');
define('POSTS_DIRECTORY', '/uploads/post_media/');
define('DEFAULT_PROFILE', '/images/default_profile.jpg');
define('PHP_LOG_FILE', '/logs/error.log');
