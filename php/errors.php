<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php/all.php';

function errorResponse($code, $errorMessage)
{
    $response = array();

    // Log error message for server errors (status code >= 500)
    if ($code >= 500) {
        error_log('ERROR_LOG: ' . $errorMessage);
        $response = array('message' => 'Internal Server Error, please try again later.');
    } else {
        $response = array('message' => $errorMessage);
    }

    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


function serverMaintenanceResponse() {
    $response = array('message' => 'Service is unavailable right now, please try again later.');
    http_response_code(503);
    header('Retry-After: 120');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
