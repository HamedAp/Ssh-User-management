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
      
       response(NULL, NULL, 200,"No Record Found");

      }


}

//edit user 

if(isset($_POST['method'] ) || $_POST['method'] == "edituser"){

}

//change plan 


// delete user 

if(isset($_POST['method'] ) || $_POST['method'] == "deleteuser"){

}

// suspend user 

if(isset($_POST['method'] ) || $_POST['method'] == "suspend user"){

}

// unsuspenduser

if(isset($_POST['method'] ) || $_POST['method'] == "unsuspenduser"){

}


// get user info 

if(isset($_POST['method'] ) || $_POST['method'] == "userinfo"){

}


// get all users 

if(isset($_POST['method'] ) || $_POST['method'] == "alluser"){

}

// change user password 

if(isset($_POST['method'] ) || $_POST['method'] == "changepassword"){

}

/////// server


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