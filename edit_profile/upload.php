<?php

include '../database/functions.php';

// Set uploads directory
$uploadsDirectory = '../uploads/';

// Check if the directory exists or create it
if (!file_exists($uploadsDirectory)) {
    mkdir($uploadsDirectory, 0777, true);
}

$response = array();

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
?>
