<?php
include("token.php");
$list = shell_exec('sudo lsof -i  -n | grep " 4u" | grep -v root | grep ESTABLISHED');
$onlineuserlist = preg_split("/\r\n|\n|\r/", $list);
foreach($onlineuserlist as $user){
$user = preg_replace('/\s+/', ' ', $user);
$userarray = explode(" ",$user);
$onlinelist[] = $userarray[2];
}
$onlinecount = array_count_values($onlinelist);
foreach ($onlinelist as $online){
    if ($userLimit !== "0" && $onlinecount[$online] > $userLimit){
        $out = shell_exec('sudo killall -u '. $online );
        }
}
?>