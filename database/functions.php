<?php

function genUUID($length = 16) {
    $uuid = uniqid('', true); // Generate UUID
    $uuid = str_replace('.', '', $uuid); // Remove periods

    // Truncate or pad to achieve fixed length
    if(strlen($uuid) > $length) {
        $uuid = substr($uuid, 0, $length);
    } else {
        $uuid = str_pad($uuid, $length, '0');
    }

    return $uuid;
}
