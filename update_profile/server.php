<?php

include_once '../database/functions.php';

// Set uploads directory with a trailing slash for consistency
$uploadsDirectory = PROFILES_DIRECTORY;
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

// Check if the file has been uploaded successfully
if (!isset($_FILES['profile_picture'])) {
    badRequestResponse('No file uploaded.');
}

$file = $_FILES['profile_picture'];

// Check if file is an image and validate extension (replace with your allowed extensions)
$allowedExtensions = ['jpg', 'jpeg'];
$fileInfo = pathinfo($file['name']);
if (!in_array(strtolower($fileInfo['extension']), $allowedExtensions) || exif_imagetype($file['tmp_name']) === false) {
    badRequestResponse('Uploaded file is not a valid image.');
}

// get Database Connection
$conn = getConnection();
$userId = $_SESSION['user_id'];
$fileId = genUUID();
$fileName = $uploadsDirectory . $fileId . '.jpg';

// move the uploaded file to the uploads directory
if (move_uploaded_file($file['tmp_name'], $fileName) === false) {
    $conn->close();
    error_log("Error: Failed to move uploaded file to uploads directory");
    errorResponse('Internal Server Error', 500);
}

// Disable autocommit to start a new transaction
$conn->autocommit(false);

try {
    if(profilePictureExists($conn, $userId)) {
        $oldFileId = getProfilePictureId($conn, $userId);
        $oldFileName = $uploadsDirectory . $oldFileId . '.jpg';

        updateProfilePicture($conn, $userId, $fileId);

        if(unlink($uploadsDirectory. $oldFileId. '.jpg') === false) {
            error_log('Error: Failed to delete ' . $oldFileName);
        }
    } else {
        insertProfilePicture($conn, $userId, $fileId);
    }

    // Commit the transaction if no exceptions occur
    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Profile picture updated successfully.';
    echo json_encode($response);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    $response['success'] = false;
    $response['message'] = 'Internal Server Error';
    echo json_encode($response);
    if(unlink($uploadsDirectory. $fileId. '.jpg') === false) {
        error_log('Error: Failed to delete ' . $fileName);
    }
    handleException($e);
} finally {
    // Re-enable autocommit
    $conn->autocommit(true);
    $conn->close();
}
