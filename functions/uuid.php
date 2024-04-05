<?php

/**
 * Generates a Universally Unique Identifier (UUID) string.
 *
 * @param int $length The desired length of the UUID. Default is 16.
 * @return string The generated UUID string.
 */
function genUUID($length = 16)
{
    return substr(str_replace('.', '', uniqid('', true)), 0, $length);
}
