<?php

require 'zhaket-api.class.php';

$license_token = 'LICENSE_TOKEN'; // Your license token
/*
 * @param : license token
 * replace it with your own license token :)
 */
$result = Zhaket_License::isValid($license_token);

if ($result->status=='successful') {
    echo $result->message; // License is valid
} else {
    // License not valid / show message
    if (!is_object($result->message)) { // License is Invalid
        echo $result->message;
    } else {
        foreach ($result->message as $message) {
            foreach ($message as $msg) {
                echo $msg.'<br>';
            }
        }
    }
}

?>