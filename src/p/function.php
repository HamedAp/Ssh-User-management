<?php
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
function gen_token() {
	$length = 16;
    $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
function randomPassword($char_count,$type) {
    $alphabet = $type;
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $char_count; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
function isAllowed($ip , $whitelist ){
    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
        return true;
    }
    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");
        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            return true;
        }
    }
    return false;
}
function response($Res,$response_code,$response_desc){
	$response['red_data'] = $Res;
	$response['response_code'] = $response_code;
	$response['response_desc'] = $response_desc;

	$json_response = json_encode($response);
	echo $json_response;
}
function formatBytes($bytes) {
    if ($bytes > 0) {
        $i = floor(log($bytes) / log(1024));
        $sizes = array(' بایت', ' کیلوبایت', ' مگ', ' گیگ', ' ترا', 'PB', 'EB', 'ZB', 'YB');
        return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
    } else {
        return 0;
    }
}
?>