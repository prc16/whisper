<?php

include_once '../php/all.php';

function errorResponse($code, $message)
{
    http_response_code($code);
    if($code >= 500) {
        error_log('ERROR_LOG:' . $message);
        $response['message'] = 'Internal Server Error, please try again later.';
        echo json_encode($response);
    }
    $response['message'] = $message;
    echo json_encode($response);
    exit();
}
