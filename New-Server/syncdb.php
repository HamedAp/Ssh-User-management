<?php
date_default_timezone_set("Asia/Tehran");
$servername = "serverip";
$username = "adminuser";
$password = "adminpassword";
$dbname = "ShaHaN";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$output = shell_exec('cat /etc/passwd | grep "/home/" | grep -v "/home/syslog"');
$userlist = preg_split("/\r\n|\n|\r/", $output);
foreach($userlist as $user){
$userarray = explode(":",$user);
if (!empty($userarray[0])) {
$out = shell_exec('bash delete '.$userarray[0]);
echo $userarray[0] . " Removed  <br>";
}}
$strSQL = "SELECT * FROM users" ;
$rs = mysqli_query($conn,$strSQL);
while($row = mysqli_fetch_array($rs)){ 
$out = shell_exec('bash adduser '.$row['username'].' '.$row['password']);
echo $row['username'] . " Added <br>";
}
?>