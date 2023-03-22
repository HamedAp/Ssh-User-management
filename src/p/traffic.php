<?php 
include('header.php'); 
include('menu.php');

$out = shell_exec('cat 11202319.log');
$trafficlog = preg_split("/\r\n|\n|\r/", $out);


foreach($trafficlog as $logline){
	
	$log = explode(" ",$logline);
	$userlog = substr($log[1], 0, strpos($log[1], "/"));
	$log = preg_split('/\s+/', $log[1]);
	$usertraffic = round($log[2], 2);

	echo $userlog ."------".$usertraffic;
echo "<br>";
}