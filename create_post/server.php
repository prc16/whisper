<?php

include_once '../database/functions.php';

$response = array();

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    unauthorizedResponse();
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    badRequestResponse();
}

// Check if the username is provided
if (!isset($_POST['post_text'])) {
    badRequestResponse('No post_text provided.');
}

// get Database Connection
$conn = getConnection();
$userId = $_SESSION['user_id'];
$post_text = $_POST['post_text'];

// Check if a media file is uploaded
if (isset($_FILES['media_file'])) {

    // Handle Media File Upload
    $target_dir = POSTS_DIRECTORY; // Directory where media files will be saved
    $media_file_name = basename($_FILES["media_file"]["name"]);
    $target_file = $target_dir . $media_file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        badRequestResponse('Sorry, file already exists.');
        $uploadOk = 0;
    }

    // Check file size (you can adjust the size limit)
    if ($_FILES["media_file"]["size"] > 5000000) {
        badRequestResponse('Sorry, your file is too large.');
        $uploadOk = 0;
    }

    // Allow certain file formats (you can adjust allowed formats)
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp4" && $imageFileType != "avi"
    ) {
        badRequestResponse('Sorry, only JPG, JPEG, PNG, GIF, MP4, and AVI files are allowed.');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        badRequestResponse('Sorry, your file was not uploaded.');
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file)) {
            // Media file uploaded successfully
        } else {
            badRequestResponse('Sorry, there was an error uploading your file.');
        }
    }
} else {
    $media_file_name = null;
}

try {
    // Update username
    createPost($conn, $userId, $post_text, $media_file_name);

    // Success response
    $response['success'] = true;
    $response['message'] = 'Post uploaded successfully.';
    echo json_encode($response);
} catch (Exception $e) {
    handleException($e);
    errorResponse('Internal Server Error', 500);
} finally {
    $conn->close();
}
