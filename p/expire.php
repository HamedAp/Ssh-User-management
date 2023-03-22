<?php 
include('header.php'); 
include('menu.php'); 
$strSQL = "SELECT * FROM users" ;
$rs = mysqli_query($conn,$strSQL);
while($row = mysqli_fetch_array($rs)){
	if (!empty($row['finishdate'])){
	$expiredate = strtotime(date('Y-m-d', strtotime($row['finishdate']) ) );
	if( $expiredate < strtotime(date('Y-m-d'))) {
$sql = "UPDATE users SET enable='false' where username='".$row['username']."'" ;
if($conn->query($sql) === true){}
$out = shell_exec("bash /var/www/html/p/delete ".$row['username']);

	}
	}
}