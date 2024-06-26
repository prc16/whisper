<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'You must log in first to create a post');
    exit;
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
    exit;
}

$post_text = trim($_POST['post_text']);

// Check if a media file is uploaded
if (isset($_FILES['media_file'])) {

    // Handle Media File Upload
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . POSTS_DIRECTORY; // Directory where media files will be saved

    // Generate a unique filename using genUUID() function
    $media_file_id = genUUID();
    $media_file_ext = strtolower(pathinfo($_FILES["media_file"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $media_file_id . '.' . $media_file_ext;

    // Check file size (you can adjust the size limit)
    if ($_FILES["media_file"]["size"] > 52428800) {
        errorResponse(400, 'Sorry, your file is too large.');
        exit;
    }

    // Allow certain file formats
    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "webp");

    if (!in_array($media_file_ext, $allowedExtensions)) {
        errorResponse(400, 'File type not supported.');
        exit;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        errorResponse(500, 'Error when creating post:' . $target_file . ' already exists.');
        exit;
    }

    if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file) === false) {
        errorResponse(500, 'Error when Moving to uploads directory: ' . $target_file);
        exit;
    }

} else {
    // Check if the post text and media are not empty together
    if (empty($post_text)) {
        errorResponse(400, 'Empty post not allowed.');
        exit;
    }
    $media_file_id = null;
    $media_file_ext = null;
    $target_file = null;
}

if (!isset($_POST['anon_post'])) {
    errorResponse(400, 'Bad Request');
    exit;
}

$anon_post = $_POST['anon_post'] == 'true' ? 1 : 0;
$expire_at = null;

if (isset($_POST['expire_at']) && $_POST['expire_at'] > 0) {
    $expire_at = $_POST['expire_at'];
} 

// get Database Connection
$conn = getConnection();
if (!$conn) {
    serverMaintenanceResponse();
    exit;
}

$user_id = $_SESSION['user_id'];

if (!createPost($conn, $user_id, $anon_post, $post_text, $media_file_id, $media_file_ext, $expire_at)) {
    if ($target_file) {
        if (unlink($target_file) === false) {
            error_log('Error: Failed to delete untracked file: ' . $target_file);
        }
    }
    $conn->close();
    errorResponse(500, 'Error when creating post');
    exit;
}

$conn->close();
