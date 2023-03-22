<?php
$token = $_GET['token'];

include("../p/config.php");
include("../p/function.php");


header("Content-Type:application/json");
// checl api 
if (isset($_POST['method'])) {
    $query = "SELECT * FROM ApiToken WHERE Token ='".$_GET['token']."'";
    $result = mysqli_query($conn, $query);
    $res = mysqli_fetch_array($result);
  if (!isset($result) || $res['token'] != $token ) {
    die('api is not valid ');
     
  } elseif(isset($result) || $res['token'] == $token ) {

    // api is ok 
   
  }else{
    die('moshkel');
  }

// check ip 
if (! isAllowed($_SERVER['REMOTE_ADDR'] , $res['Allowips'])) {
   die('ip not alloed ');
}

//////// user

// add user

if(isset($_POST['method'] ) || $_POST['method'] == "adduser"){

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
       if ($conn->query($adduser) === TRUE) {}
       $out = shell_exec('bash adduser '.$_POST['user'].' '.$_POST['password']);
      
       response(NULL, 200,"User Created Success");

      


}

//edit user 

if(isset($_POST['method'] ) || $_POST['method'] == "edituser"){

    $strSQL = "SELECT * FROM users where username='".$_GET['edituser']."'" ;
    $rs = mysqli_query($conn,$strSQL);
    while($row = mysqli_fetch_array($rs)){ 
    $editusername = $row['username']; 
    $editpassword = $row['password']; 
    $editemail = $row['email'];
    $editmobile = $row['mobile'];
    $editmultiuser = $row['multiuser'];
    $editfinishdate = $row['finishdate'];
    $edittraffic = $row['traffic'];
    $editreferral = $row['referral'];
    $editenable = $row['enable'];
    }}
    if(!empty($_POST['editnewuser'])){
    $sql = "UPDATE users SET password='".$_POST['password']."',email='".$_POST['email']."',mobile='".$_POST['mobile']."',multiuser='".$_POST['multiuser']."',finishdate='".$_POST['finishdate']."',traffic='".$_POST['traffic']."',referral='".$_POST['referral']."' where username='".$_POST['useruser']."'" ;
    if($conn->query($sql) === true){}
    $out = shell_exec('bash ch '.$_POST['useruser'].' '.$_POST['password']);

    response(NULL, 200,"User Edited Success");


}

//change plan 


// delete user 

if(isset($_POST['method'] ) || $_POST['method'] == "deleteuser"){
    $sql = "delete FROM users where username='".$_GET['removeuser']."'";
    if($conn->query($sql) === true){}
    $out = shell_exec('bash delete '.$_GET['removeuser']);
    response(NULL, 200,"User deleted Success");
}

// suspend user 

if(isset($_POST['method'] ) || $_POST['method'] == "suspend user"){
    $sql = "UPDATE users SET enable='false' where username='".$_GET['deactiveuser']."'" ;
    if($conn->query($sql) === true){}
    $out = shell_exec('bash delete '.$_GET['deactiveuser']);
    response(NULL, 200,"User Suspend Success");
}

// unsuspenduser

if(isset($_POST['method'] ) || $_POST['method'] == "unsuspenduser"){
    $sql = "UPDATE users SET enable='true' where username='".$_GET['activeuser']."'" ;
    if($conn->query($sql) === true){}
    $out = shell_exec('bash adduser '.$_GET['activeuser'].' '.$_GET['password']);
    response(NULL, 200,"User unsuspend Success");
}


// get user info 

if(isset($_POST['method'] ) || $_POST['method'] == "userinfo"){

}


// get all users 

if(isset($_POST['method'] ) || $_POST['method'] == "alluser"){
    $strSQL = "SELECT * FROM users" ;
    response( $strSQL, 200,"get all users");
}

// change user password 

if(isset($_POST['method'] ) || $_POST['method'] == "changepassword"){



}


// add server 

if(isset($_POST['method'] ) || $_POST['method'] == "addserver"){

}

// get server info 

if(isset($_POST['method'] ) || $_POST['method'] == "serverinfo"){

}

// get server list 

if(isset($_POST['method'] ) || $_POST['method'] == "serverlist"){

}


// disable server

if(isset($_POST['method'] ) || $_POST['method'] == "disableserver"){

}

// enable server

if(isset($_POST['method'] ) || $_POST['method'] == "enableserver"){

}

// reset traffic 

if(isset($_POST['method'] ) || $_POST['method'] == "resettraffic "){

}

if(isset($_POST['method'] ) || $_POST['method'] == null){

    response(NULL, 400,"Invalid Request");
}

}





?>