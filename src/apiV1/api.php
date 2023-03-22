<?php
$token = "test";
header("Content-Type:application/json");

// checl api 





// check ip 



// check method 

// https://Serverip/apiV1/


//////// user


// add user


//edit user 


//change plan 


// delete user 


// suspend user 


// get user info 


// get all users 


// change user password 


/////// server


// add server 

// get server info 

// get server list 


////// traffic 

// reset user traffic 

// add traffic for user 

// get traffic info 



if (isset($token) {
	include('db.php');
	$order_id = $_GET['order_id'];
	$result = mysqli_query(
	$con,
	"SELECT * FROM `transactions` WHERE order_id=$order_id");
	if(mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$amount = $row['amount'];
	$response_code = $row['response_code'];
	$response_desc = $row['response_desc'];
	response($order_id, $amount, $response_code,$response_desc);
	mysqli_close($con);
	}else{
		response(NULL, NULL, 200,"No Record Found");
		}
}else{
	response(NULL, NULL, 400,"Invalid Request");
	}


function response($order_id,$amount,$response_code,$response_desc){
	$response['order_id'] = $order_id;
	$response['amount'] = $amount;
	$response['response_code'] = $response_code;
	$response['response_desc'] = $response_desc;
	
	$json_response = json_encode($response);
	echo $json_response;
}
?>