<?php

include_once '../database/functions.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'User not logged in');
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the username is provided
if (!isset($_POST['post_text'])) {
    errorResponse(400, 'No post text provided.');
}

// get Database Connection
$conn = getConnection();
$userId = $_SESSION['user_id'];
$post_text = $_POST['post_text'];

// Check if a media file is uploaded
if (isset($_FILES['media_file'])) {

    // Handle Media File Upload
    $target_dir = POSTS_DIRECTORY; // Directory where media files will be saved
    
    // Generate a unique filename using genUUID() function
    $media_file_name = genUUID() . '.' . pathinfo($_FILES["media_file"]["name"], PATHINFO_EXTENSION);
    
    $target_file = $target_dir . $media_file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        errorResponse(500, 'Sorry, file already exists.');
    }

    // Check file size (you can adjust the size limit)
    if ($_FILES["media_file"]["size"] > 50000000) {
        errorResponse(400, 'Sorry, your file is too large.');
    }

    // Allow certain file formats (you can adjust allowed formats)
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4" && $imageFileType != "webm"
    ) {
        errorResponse(400, 'File type not supported.');
    }

    if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file) === false) {
        errorResponse(500, 'Sorry, there was an error uploading your file.');
    }
} else {
    $media_file_name = null;
}

try {
    createPost($conn, $userId, $post_text, $media_file_name);
} catch (Exception $e) {
    handleException($e);
    errorResponse(500, 'There was an error creating your post.');
} finally {
    $conn->close();
}
