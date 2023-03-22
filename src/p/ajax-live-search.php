<?php
include('config.php'); 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
  if (isset($_POST['query'])) {
      $query = "SELECT * FROM users WHERE username LIKE '{$_POST['query']}%' LIMIT 10";
      $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($res = mysqli_fetch_array($result)) {
			echo "<a href='/p/newuser.php?edituser=".$res['username']."' ><div class='alert mt-3 text-center' role='alert' style='border: 1px solid #dddddd;background-color: #e9ffe9;margin-bottom: 1px !important;'>
         ".$res['username']."
      </div></a>
		";
		
      }
    } else {
      echo "
      <div class='alert alert-danger mt-3 text-center' role='alert'>
          نام کاربری یافت نشد .
      </div>
      ";
    }
  }
?>