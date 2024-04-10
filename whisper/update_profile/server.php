<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/uuid.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php/errors.php';

$response = array();

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    errorResponse(401, 'User not logged in');
}

// Validate the Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse(400, 'Bad Request');
}

// Check if the file has been uploaded successfully
if (!isset($_FILES['profile_picture'])) {
    errorResponse(400, 'No file uploaded.');
}

$file = $_FILES['profile_picture'];

// Check if file is an image and validate extension (replace with your allowed extensions)
$allowedExtensions = ['jpg', 'jpeg'];
$fileInfo = pathinfo($file['name']);
if (!in_array(strtolower($fileInfo['extension']), $allowedExtensions) || exif_imagetype($file['tmp_name']) === false) {
    errorResponse(400, 'Uploaded file is not a valid image.');
}

// get Database Connection
$conn = getConnection();
if(!$conn) {
    serverMaintenanceResponse();
    exit;
}
$userId = $_SESSION['user_id'];
$fileId = genUUID();
$filePath = PROFILES_DIRECTORY . $fileId . '.jpg';

// move the uploaded file to the uploads directory
if (move_uploaded_file($file['tmp_name'], $filePath) === false) {
    $conn->close();
    errorResponse(500, 'Internal Server Error: Failed to move uploaded file to uploads directory');
}

// Disable autocommit to start a new transaction
$conn->autocommit(false);

try {
    if($oldfileId = getProfilePictureId($conn, $userId)) {

        $oldfilePath = PROFILES_DIRECTORY . $oldfileId . '.jpg';

        if(!updateProfilePicture($conn, $userId, $fileId)) {
            throw new Exception("Failed to update profile picture: " . $conn->error);
        }

        if(unlink($oldfilePath) === false) {
            throw new Exception("Failed to delete old profile picture: " . $oldfilePath);
        }
    } else {
        if(!insertProfilePicture($conn, $userId, $fileId)) {
            throw new Exception("Failed to insert profile picture: " . $conn->error);
        }
    }

    // Commit the transaction if no exceptions occur
    $conn->commit();
    $response['message'] = 'Profile picture updated successfully.';
    echo json_encode($response);
} catch (Exception $e) {
    $conn->rollback();
    if(unlink($filePath) === false) {
        error_log('Error: Failed to delete ' . $filePath);
    }
    errorResponse(500, 'Execption thrown when updating profile: ' . $e->getMessage());
} finally {
    // Re-enable autocommit
    $conn->autocommit(true);
    $conn->close();
}
