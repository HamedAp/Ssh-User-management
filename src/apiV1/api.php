<?php
$token = $_GET['token'];

include("/var/www/html/p/config.php");
include("/var/www/html/p/function.php");
header("Content-Type:application/json");

// checl api 
if (isset($_POST['method'])) {
    $query = "SELECT * FROM ApiToken WHERE Token ='".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    $res = mysqli_fetch_array($result);
  if (!isset($result) || $res['Token'] != $token ) {
    die('api is not valid ');
  } elseif(isset($result) || $res['token'] == $token ) {
    // api is ok 
  }else{
    die('moshkel');
  }

  // check ip 
    if ($_SERVER['REMOTE_ADDR'] !== $res['Allowips'] && $res['Allowips'] !== "0.0.0.0/0" ) {
      die('ip not alloed ');
    }
  //////// user
  // add user
  if(isset($_POST['method'] ) && $_POST['method'] == "adduser"){
      $checkuserQRY = "SELECT * FROM users where username='".$_POST['user']."'" ;
      $rs = mysqli_query($conn,$checkuserQRY);
      while($row = mysqli_fetch_array($rs)){ 
       if(isset($row)){
        return response($row, 300,"user exist");
       }
      }
      $adduser = "INSERT INTO `users` (
          `username`,
          `password`,
          `email`,
          `mobile`,
          `multiuser` ,
          `startdate`,
          `finishdate`,
          `enable`,
          `traffic`,
          `referral` ) VALUES (
          '".$_POST['user']."',
          '".$_POST['password']."',
          '".$_POST['email']."',
          '".$_POST['mobile']."',
          '".$_POST['multiuser']."',
          '".date("Y-m-d")."',
          '".$_POST['finishdate']."',
          'true',
          '".$_POST['traffic']."',
          '".$_POST['referral']."');";
        if ($userdb =$conn->query($adduser) === TRUE) {
          $out = shell_exec('bash /var/www/html/p/adduser '.$_POST['user'].' '.$_POST['password']);
          response($userdb,200,"User Created Success");
        }
       
  }
  
  //edit user 
  if(isset($_POST['method'] ) && $_POST['method'] == 'edituser'){
      $strSQL = "SELECT * FROM users where username='".$_GET['edituser']."'" ;
      $rs = mysqli_query($conn,$strSQL);
      while($row = mysqli_fetch_array($rs)){ 
        if(isset($row)){
          $sql = "UPDATE users SET password='".$_POST['password']."',email='".$_POST['email']."',mobile='".$_POST['mobile']."',multiuser='".$_POST['multiuser']."',finishdate='".$_POST['finishdate']."',traffic='".$_POST['traffic']."',referral='".$_POST['referral']."' where username='".$row['username']."'" ;
            if($conn->query($sql) === true){
          
            $out = shell_exec('bash /var/www/html/p/ch '.$row['username'].' '.$_POST['password']);
            response(true, 200,"User Edited Success");
            }
        }else{
          response($userdb,300,"User does not exist");
        }
       }
  }
     
  // delete user 
  if(isset($_POST['method'] ) && $_POST['method'] == "deleteuser"){
      $sql = "delete FROM users where username='".$_GET['deleteuser']."'";
      if($conn->query($sql) === true){
      $out = shell_exec('bash /var/www/html/p/delete '.$_GET['deleteuser']);
      response(true, 200,"User deleted Success");
      }
  }
  // suspend user 
  if(isset($_POST['method'] ) && $_POST['method'] == "suspenduser"){
      $sql = "UPDATE users SET enable='false' where username='".$_GET['suspenduser']."'" ;
      if($conn->query($sql) === true){
      $out = shell_exec('bash /var/www/html/p/delete '.$_GET['suspenduser']);
      response(NULL, 200,"User Suspend Success");
      }
  }
  // unsuspenduser
  if(isset($_POST['method'] ) &&  $_POST['method'] == "unsuspenduser"){
      $sql = "UPDATE users SET enable='true' where username='".$_GET['unsuspenduser']."'" ;
      if($conn->query($sql) === true){
        $strSQL = "SELECT * FROM users where username='".$_GET['unsuspenduser']."'" ;
        $rs = mysqli_query($conn,$strSQL);
        while($row = mysqli_fetch_array($rs)){ 
          $out = shell_exec('bash /var/www/html/p/adduser '.$row['username'].' '.$row['password']);
          response(NULL, 200,"User unsuspend Success");
        }
    
      }
  }

  // get user info 
  if(isset($_POST['method'] ) && $_POST['method'] == "userinfo"){
       $strSQL = "SELECT * FROM users where username='".$_GET['username']."'" ;
        $rs = mysqli_query($conn,$strSQL);
        while($row = mysqli_fetch_array($rs)){ 
          response($row, 200,"user info retuned");
        }
  }
  // get all users 
  if(isset($_POST['method'] ) && $_POST['method'] == "alluser"){
    $sqlriz = "Select * FROM `users`";
    $Rslt = mysqli_query($conn,$sqlriz);
    while($r=mysqli_fetch_object($Rslt))
    {
        $res[]=$r;
    }

     response($res, 200,"all users");
  
   
  }
  // change user password 
  if(isset($_POST['method'] ) && $_POST['method'] == "changepassword"){
    if(!empty($_POST['username'])){
    $sql = "UPDATE users SET password='".$_POST['password']."' where username='".$_POST['username']."'" ;
    if($conn->query($sql) === true){
    $out = shell_exec('bash /var/www/html/p/ch '.$_POST['useruser'].' '.$_POST['password']);
    response($res, 200,"password changed");
    }
  }
  }

  // get server list 
  if(isset($_POST['method'] ) && $_POST['method'] == "serverlist"){
    $sqlriz = "Select * FROM `servers`";
    $Rslt = mysqli_query($conn,$sqlriz);
    while($r=mysqli_fetch_object($Rslt))
    {
        $res[]=$r;
    }

     response($res, 200,"all servers");
  }
  // reset traffic 
  if(isset($_POST['method'] ) && $_POST['method'] == "resettraffic "){
  }
  if(isset($_POST['method'] ) && $_POST['method'] == null){
      response(NULL, 400,"Invalid Request");
  }
}
?>
