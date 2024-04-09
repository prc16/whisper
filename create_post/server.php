<?php

include_once '../php/all.php';
include_once '../php/uuid.php';
include_once '../php/database.php';
include_once '../php/errors.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'You need to log in to create post');
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

$content = trim($_POST['post_text']);

// Check if a media file is uploaded
if (isset($_FILES['media_file'])) {

    // Handle Media File Upload
    $target_dir = POSTS_DIRECTORY; // Directory where media files will be saved

    // Generate a unique filename using genUUID() function
    $media_file_id = genUUID();
    $media_file_ext = "jpeg";
    $target_file = $target_dir . $media_file_id . '.' . $media_file_ext;

    $imageFileType = strtolower(pathinfo($_FILES["media_file"]["name"], PATHINFO_EXTENSION));

    // Check file size (you can adjust the size limit)
    if ($_FILES["media_file"]["size"] > 52428800) {
        errorResponse(400, 'Sorry, your file is too large.');
    }

    // Allow certain file formats
    $allowedExtensions = array("jpg", "jpeg", "png");

    if (!in_array($imageFileType, $allowedExtensions)) {
        errorResponse(400, 'File type not supported.');
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        errorResponse(500, 'Error when creating post:' . $target_file . ' already exists.');
    }

    // Convert the image to JPEG format before moving it
    $image = null;
    switch ($imageFileType) {
        case "jpg":
        case "jpeg":
            $image = imagecreatefromjpeg($_FILES["media_file"]["tmp_name"]);
            break;
        case "png":
            $image = imagecreatefrompng($_FILES["media_file"]["tmp_name"]);
            break;
        // case "gif":
        //     $image = imagecreatefromgif($_FILES["media_file"]["tmp_name"]);
        //     break;
        // case "webp":
        //     $image = imagecreatefromwebp($_FILES["media_file"]["tmp_name"]);
        //     break;
    }

    if ($image !== null) {
        // Save the image as jpeg
        imagejpeg($image, $target_file);
        imagedestroy($image);
    } else {
        errorResponse(500, 'Failed to process the image.');
    }
} else {
    // Check if the post text and media are not empty together
    if (empty($content)) {
        errorResponse(400, 'Empty post not allowed.');
    }
    $media_file_id = null;
    $media_file_ext = null;
    $target_file = null;
}

// get Database Connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

$userId = $_SESSION['user_id'];

if (!createPost($conn, $userId, $content, $media_file_id, $media_file_ext)) {
    if ($target_file) {
        if (unlink($target_file) === false) {
            error_log('Error: Failed to delete ' . $target_file);
        }
    }
    $conn->close();
    errorResponse(500, 'Error when creating post');
}

$conn->close();
