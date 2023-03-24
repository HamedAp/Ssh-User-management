<?php
global $port;
$port = "22";
$servername = "localhost";
$username = "adminuser";
$password = "adminpass";
$dbname = "ShaHaN";
date_default_timezone_set("Asia/Tehran");
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

// load lang file 
$lang = "fa";
$langsql = "select * from setting";
$rslang = mysqli_query($conn,$langsql);
while($rowlang = mysqli_fetch_array($rslang)){
    include('/lang/'.$rslang['language'].'.php');
}

?>