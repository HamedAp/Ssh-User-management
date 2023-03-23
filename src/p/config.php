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
?>