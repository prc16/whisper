<?php

include '../database/functions.php';

try {
    $conn = getConnection();
} catch (Exception $e) {
    handleException($e);
    exit();
}

session_start();

// Set uploads directory
$uploadsDirectory = '../uploads/';

$response = array();

// Validate session and action
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    $response['success'] = false;
    $response['message'] = 'Unauthorized Request';
    echo json_encode($response);
    $conn->close();
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    
    $file = $_FILES['profile_picture'];

    // Check if file is an image
    $imageType = exif_imagetype($file['tmp_name']);
    if ($imageType === false) {
        $response['success'] = false;
        $response['message'] = 'Uploaded file is not an image.';
        echo json_encode($response);
        exit();
    }

    // Move uploaded file to destination directory
    $fileName = $uploadsDirectory . genUUID() . '.jpg';
    if (move_uploaded_file($file['tmp_name'], $fileName)) {
        $response['success'] = true;
        $response['message'] = 'Image uploaded successfully.';
        echo json_encode($response);
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to upload image.';
        echo json_encode($response);
    }
} else {
    $response['success'] = false;
    $response['message'] = 'No image uploaded.';
    echo json_encode($response);
}

$conn->close();
