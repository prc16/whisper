<?php

$jsonData = file_get_contents('../database/config.json');

$config = json_decode($jsonData, true);

// Define variables based on JSON data
define("DATABASE_HOSTNAME", $config['database_hostname']);
define("DATABASE_USERNAME", $config['database_username']);
define("DATABASE_PASSWORD", $config['database_password']);
define("DATABASE_NAME", $config['database_name']);

define("PROFILE_IMAGES_DIRECTORY", $config['profile_images_directory']);
define("POST_IMAGES_DIRECTORY", $config['post_images_directory']);
define("DEFAULT_PROFILE", $config['default_profile']);
