<?php
date_default_timezone_set("Asia/Tehran");
$ip = "serverip";
$token = "servertoken";
$output = shell_exec('cat /etc/passwd | grep "/home/" | grep -v "/home/syslog"');
$userlist = preg_split("/\r\n|\n|\r/", $output);
foreach($userlist as $user){
$userarray = explode(":",$user);
if (!empty($userarray[0])) {
 $out = shell_exec('bash /var/www/html/delete '.$userarray[0]);
 echo $userarray[0] . " Removed  <br>";
}}
$postParameter = array(
    'method' => 'multiserver'
);
$curlHandle = curl_init('http://'.$ip.'/apiV1/api.php?token='.$token);
curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
$curlResponse = curl_exec($curlHandle);
curl_close($curlHandle);
$data = json_decode($curlResponse, true);
$data = $data['data'];
foreach ($data as $user){
	$out = shell_exec('bash /var/www/html/adduser '.$user['username'].' '.$user['password']);
}
?>
