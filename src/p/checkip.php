<?php
include('header.php'); 
include('menu.php');
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
در صورتی که بقیه کشور ها آنلاین باشند و ایران فیلتر باشد به معنای فیلتر شدن سرور ها در ایران میباشد . 
</div>';
$serverip = $_SERVER['SERVER_ADDR'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://check-host.net/check-tcp?host=".$serverip.":".$port."&max_nodes=50");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$vars); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = [
    'Accept: application/json',
    'Cache-Control: no-cache',
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec ($ch);
curl_close ($ch);
$array = json_decode( $response, true );
$resultlink = "https://check-host.net/check-result/" . $array['request_id'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$resultlink);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$vars); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = [
    'Accept: application/json',
    'Cache-Control: no-cache',
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
sleep(3);
$server_output = curl_exec ($ch);
curl_close ($ch);
$array2 = json_decode( $server_output, true);
echo '<div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">وضعیت فیلترینگ در ایران</div>
						'.$msg.'
						<div class="table-responsive" >
                        <div class="col-md-6">
							<div class="white-box">
								<div class="row">
									<div class="col-sm-12 col-xs-12"><table class="checkip" style="width:100%"><tr><th class="checkip">سرور</th><th class="checkip">وضعیت</th></tr>';
 foreach($array2 as $key => $value) {
	$flag = str_replace(".node.check-host.net","",$key);
	$flag = preg_replace('/[0-9]+/', '', $flag);
	if ($flag == "ir" || $flag == "us" || $flag == "fr" || $flag == "de" ){
  $img = "<img src='flags/".$flag.".png' >";
  if ( is_numeric($value[0]["time"]) ) {$status = 'Online';}else{$status = 'فیلتر شده';}
  echo '<tr><td class="checkip">'.$img.'</td><td class="checkip">'.$status.'</td></tr>';
	}
}
 echo "</table></div></div></div></div></div></div></div></div>";
include('footer.php');
?>