<?php

require 'zhaket-api.class.php';

$license_token = 'LICENSE_TOKEN'; // Your license token
$produc_token = 'PRODUCT_TOKEN'; // Your product token

/*
	@param1 : license_token
	@param2 : product_token
	replace it with your own license token and product token :)
*/
$result = Zhaket_License::install($license_token, $produc_token);

if ($result->status=='successful') {
    echo $result->message; // License installed successful
} else {
    // License not installed / show message
    if (!is_object($result->message)) {// License is Invalid
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