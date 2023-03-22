<?php
include("config.php");
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$strSQL = "SELECT * FROM users" ;
$rs = mysqli_query($conn,$strSQL);
$list = shell_exec("sudo lsof -i :".$port." -n | grep -v root | grep ESTABLISHED");
$onlineuserlist = preg_split("/\r\n|\n|\r/", $list);
foreach($onlineuserlist as $user){
$user = preg_replace('/\s+/', ' ', $user);
$userarray = explode(" ",$user);
$onlinelist[] = $userarray[2];
}
$onlinecount = array_count_values($onlinelist);
while($row = mysqli_fetch_array($rs)){
$limitation = $row['multiuser'];
$username = $row['username'];
if (empty($limitation)){$limitation= "0";}
$userlist[$username] =  $limitation;
if ($limitation !== "0" && $onlinecount[$username] > $limitation){
$out = shell_exec('sudo killall -u '. $username );
}
}
$conn->close();
?>