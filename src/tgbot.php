<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Tehran");
include("p/config.php");
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM setting;";
$rs = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($rs)){
$API = 'https://api.telegram.org/bot'.$row['tgtoken'].'/';
$adminid = $row['tgid'];
}

$strSQL = "SELECT * FROM tgmessage" ;
$rs = mysqli_query($conn,$strSQL);
while($row = mysqli_fetch_array($rs)){ 
$info_1m = $row['account1m']; 
$info_2m = $row['account2m']; 
$info_3m = $row['account3m']; 
$info_6m = $row['account6m']; 
$info_12m = $row['account12m']; 
$contactadmin = $row['contactadmin']; 
$rahnama = $row['rahnama']; 
$tamdid = $row['tamdid']; 

}
//------------------------------------------------------------------------------------
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];
$chatfirst_name = $update["message"]["chat"]["first_name"];
$chatlast_name = $update["message"]["chat"]["last_name"];
$chattext = $update["message"]["text"];
$newline = urlencode("\n");
file_get_contents($API."sendChatAction?chat_id=".$chatID."&action=typing");
//--------------------------------Totall-Active-Deactive-Online Users-----------------
$list = shell_exec("sudo lsof -i :".$port." -n | grep -v root | grep ESTABLISHED");
$useronline =  substr_count( $list, "\n" );
$sql = "SELECT * FROM users" ;
if ($result = mysqli_query($conn, $sql)) {$usertotal = mysqli_num_rows( $result );}
$sql = "SELECT * FROM users where enable='true'" ;
if ($result = mysqli_query($conn, $sql)) {$useractive = mysqli_num_rows( $result );}
$sql = "SELECT * FROM users where enable='false'" ;
if ($result = mysqli_query($conn, $sql)) {$userdeactive = mysqli_num_rows( $result );}
//------------------------------------------------------------------------------------
if ($chatID == $adminid){
	$menu = array(
    'keyboard' => array(
      array("کل کاربران : ".$usertotal
            ,"کاربران آنلاین : ".$useronline 
            ,"کاربران فعال : ".$useractive),
      array("ساخت بکاپ",
	  "وضعیت فیلترینگ",
	  "کاربران غیرفعال : ".$userdeactive
),
array("🆕 کاربر جدید 🆕"
)
    ),
    'one_time_keyboard' => true,
    'resize_keyboard'=> true
);
$encodedmenu = json_encode($menu);
if (strpos($chattext, '/start') !== false) {
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=مدیریت محترم سلام");
}
if (strpos($chattext, 'کل کاربران : ') !== false) {
	$strSQL = "SELECT * FROM users" ;
	$rs = mysqli_query($conn,$strSQL);
	$userlist = array();
	while($row = mysqli_fetch_array($rs)){
	$userlist[] = "/user_".$row['username'];
	}
	$msg = implode($newline,$userlist);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=کل کاربران ( ".$usertotal." ) : ".$newline.$msg);
}
if (strpos($chattext, 'کاربران آنلاین : ') !== false) {
	$onlineuserlist = preg_split("/\r\n|\n|\r/", $list);
	foreach($onlineuserlist as $user){
		$user = preg_replace('/\s+/', ' ', $user);
		$userarray = explode(" ",$user);
		$userarray[8] = strstr($userarray[8],"->");
		$userarray[8] = str_replace("->","",$userarray[8]);
		$userip = substr($userarray[8], 0, strpos($userarray[8], ":"));
		if (!empty($userarray[2]) && $userarray[2] !== "sshd"){
		$onlinelist[] = "/online_".$userarray[2]." ( ".$userip." ) ";
		}
	}
	$msg = implode($newline,$onlinelist);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=کاربران آنلاین ( ".$useronline." ) : ".$newline.$msg);
}
if (strpos($chattext, 'کاربران فعال : ') !== false) {
	$strSQL = "SELECT * FROM users where enable='true'" ;
	$rs = mysqli_query($conn,$strSQL);
	$userlist = array();
	while($row = mysqli_fetch_array($rs)){
	$userlist[] = "/user_".$row['username'];
	}
	$msg = implode($newline,$userlist);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=کل کاربران ( ".$useractive." ) : ".$newline.$msg);
}
if (strpos($chattext, 'ساخت بکاپ') !== false) {
$date = date('Y-m-d-his');
$output = shell_exec('mysqldump -u '.$username.' --password='.$password.' ShaHaN users > /var/www/html/p/backup/'.$date.'.sql'); 
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=بکاپ با موفقیت ایجاد شد .");
$FILENAME = '/var/www/html/p/backup/'.$date.'.sql';
 $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API."sendDocument?chat_id=" . $chatID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $FILENAME);
    $cFile = new CURLFile($FILENAME, $finfo);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "document" => $cFile
    ]);
    $result = curl_exec($ch);
curl_close($ch);
}
if (strpos($chattext, 'وضعیت فیلترینگ') !== false) {
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
foreach($array2 as $key => $value) {
	$flag = str_replace(".node.check-host.net","",$key);
	$flag = preg_replace('/[0-9]+/', '', $flag);
	if ($flag == "ir"){$img = "🇮🇷";}
	if ($flag == "us"){$img = "🇺🇸";}
	if ($flag == "fr"){$img = "🇫🇷";}
	if ($flag == "de"){$img = "🇩🇪";}
    if ( is_numeric($value[0]["time"]) ) {$status = 'Online';}else{$status = 'فیلتر شده';}
	if ($flag == "ir" || $flag == "us" || $flag == "fr" || $flag == "de" ){
    $filterstatus[] = $img.' : '.$status;
	}
}
	$msg = implode($newline,$filterstatus);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=وضعیت فیلترینگ  : ".$newline.$msg);
}
if (strpos($chattext, 'کاربران غیرفعال : ') !== false) {
	$strSQL = "SELECT * FROM users where enable='false'" ;
	$rs = mysqli_query($conn,$strSQL);
	$userlist = array();
	while($row = mysqli_fetch_array($rs)){
	$userlist[] = "/user_".$row['username'];
	}
	$msg = implode($newline,$userlist);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=کل کاربران ( ".$userdeactive." ) : ".$newline.$msg);
}
if (strpos($chattext, '/user_') !== false) {
$chattext = str_replace("/user_","",$chattext);
$strSQL = "SELECT * FROM users where username='".$chattext."'" ;
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
}
if($editenable == "true"){$status = "فعال";}
if($editenable == "false"){$status = "غیرفعال";}
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=اطلاعات کاربر ".$editusername.$newline."وضعیت : ".$status.$newline."پسورد : ".$editpassword.$newline."ایمیل : ".$editemail.$newline."موبایل : ".$editmobile.$newline."چندکاربره : ".$editmultiuser.$newline."تاریخ انقضا : ".$editfinishdate.$newline."ترافیک : ".$edittraffic.$newline.$newline."ویرایش کاربر : /edit_".$editusername);
}
if (strpos($chattext, '/online_') !== false) {
if (strpos($chattext, '/online_yes_') !== false) {}else{
$chattext = str_replace("/online_","",$chattext);
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=آیا از کیل کردن یوزر ".$chattext." مطمئن هستین ؟".$newline."اگر مطمئن هستین لینک زیر را بزنید : ".$newline.$newline."/online_yes_".$chattext);
}}
if (strpos($chattext, '/online_yes_') !== false) {
$chattext = str_replace("/online_yes_","",$chattext);
$out = shell_exec('sudo killall -u '. $chattext );
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=".$chattext." کیل شد .");
}
if (strpos($chattext, '/edit_') !== false) {
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=به زودی اضافه خواهد شد .");
}
if (strpos($chattext, '🆕 کاربر جدید 🆕') !== false) {
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=به زودی اضافه خواهد شد .");
}
}else {
	
	$menu = array(
    'keyboard' => array(
      array('اطلاعات اکانت ها'
            ,"خرید اکانت"
			,'اضافه کردن اکانت'
            ),
			array("وضعیت فیلترینگ"
            ,"فایل های نصبی",
			"تغییر رمز"
           ),
		   array("تمدید اکانت"
            ,"راهنما",
			"ارتباط با مدیر"
           )
    ),
    'one_time_keyboard' => true,
    'resize_keyboard'=> true
);
$encodedmenu = json_encode($menu);

	$buymenu = array(
    'keyboard' => array(
    array('یکماهه',"دو ماهه",'سه ماهه'),
	array("شش ماهه","یکساله"),
	array("بازگشت")
    ),
    'one_time_keyboard' => true,
    'resize_keyboard'=> true
);
$encodedbuymenu = json_encode($buymenu);

if ($chattext == 'بازگشت' || $chattext == '/start') {
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=یکی از گزینه ها را انتخاب کنید");
}

///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'اطلاعات اکانت ها') {
$strSQL = "SELECT * FROM users where referral='".$chatID."' " ;
$rs = mysqli_query($conn,$strSQL);
$userlist = array();
while($row = mysqli_fetch_array($rs)){
if ($row['enable'] == "true"){$status = "فعال";}else{$status = "غیرفعال";}
 $userlist[] = "Server IP : ".$_SERVER['SERVER_NAME'].$newline . "Port : ".$port.$newline."UserName : " .$row['username'].$newline."Password : " .$row['password'].$newline."MultiUser : " .$row['multiuser'].$newline."Expire Date : " .$row['finishdate'].$newline."Status : " .$status.$newline."Traffic : " .$row['traffic'];
}
foreach($userlist as $acc ){
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=".$acc);
	}
}
///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'اضافه کردن اکانت') {
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=در صورتی که قبلا اکانت خریداری نموده اید ، نام کاربری و رمز عبور خود را به صورت زیر وارد کنین : ".$newline .$newline ."Username:Password");
}
if (strpos($chattext, ':') !== false) {
$us = explode(":",$chattext);
$clientusername = $us[0];
$clientpassword = $us[1];
$strSQL = "SELECT * FROM users where username='".$clientusername."' and password='".$clientpassword."'" ;
$rs = mysqli_query($conn,$strSQL);
$userlist = array();
while($row = mysqli_fetch_array($rs)){$userlist[] = $row['username'];}
$msg = implode($newline,$userlist);
if(empty($msg)){	
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=نام کاربری و رمز عبور اشتباه است");
}else{
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=نام کاربری و رمز عبور با آی دی شما ثبت شد .".$newline."آی دی شما : ".$chatID);
$sql = "UPDATE users SET referral='".$chatID."' where username='".$clientusername."'" ;
if($conn->query($sql) === true){}
}
}
///////////////////// Telegram Bot Setting ////////////////////////

if ($chattext == 'خرید اکانت') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=برای خرید اکانت یکی از اکانت های زیر را انتخاب کنید :");
}
if ($chattext == 'یکماهه') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=".$info_1m);
}
if ($chattext == 'دو ماهه') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=".$info_2m);
}
if ($chattext == 'سه ماهه') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=".$info_3m);
}
if ($chattext == 'شش ماهه') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=".$info_6m);
}
if ($chattext == 'یکساله') {
file_get_contents($API."sendMessage?reply_markup=".$encodedbuymenu."&chat_id=".$chatID."&text=".$info_12m);
}

///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'تغییر رمز')  {
$strSQL = "SELECT * FROM users where referral='".$chatID."' " ;
$rs = mysqli_query($conn,$strSQL);
$userlist = array();
while($row = mysqli_fetch_array($rs)){
 $userlist[] = "/changepassword_" .$row['username'];
}
$msg = implode($newline,$userlist);
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=اکانتی که میخواین تغییر رمز بزنید را کلیک کنید : ".$newline.$newline.$msg);
}
if (strpos($chattext, '/changepassword_') !== false) {
$chattext = str_replace("/changepassword_","",$chattext);
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=رمز جدید را به صورت زیر وارد کنید  : ".$newline.$newline."chpass_".$chattext."_NEWPASSWORD");
}
if (strpos($chattext, 'chpass_') !== false) {
$chattext = str_replace("chpass_","",$chattext);
$newpass_arr = explode("_",$chattext);
$list = shell_exec("bash /var/www/html/p/ch ".$newpass_arr[0]." ".$newpass_arr[1]);
$sql = "UPDATE users SET password='".$newpass_arr[1]."' where username='".$newpass_arr[0]."'" ;
if($conn->query($sql) === true){}
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=رمز شما با موفقیت تغییر یافت .");
}
///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'وضعیت فیلترینگ')  {
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
foreach($array2 as $key => $value) {
	$flag = str_replace(".node.check-host.net","",$key);
	$flag = preg_replace('/[0-9]+/', '', $flag);
	if ($flag == "ir"){$img = "🇮🇷";}
    if ( is_numeric($value[0]["time"]) ) {$status = 'Online';}else{$status = 'فیلتر شده';}
	if ($flag == "ir" ){
    $filterstatus[] = $img.' : '.$status;
	}
}
	$msg = implode($newline,$filterstatus);
	file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=وضعیت فیلترینگ  : ".$newline.$newline.$msg);
}
///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'فایل های نصبی')  {
// file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=به زودی اضافه خواهد شد .");

$FILENAME = '/var/www/html/h.apk';
 $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API."sendDocument?chat_id=" . $chatID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $FILENAME);
    $cFile = new CURLFile($FILENAME, $finfo);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "document" => $cFile,
		"caption" => "Http Injector",
		"reply_markup" => $encodedmenu
    ]);
    $result = curl_exec($ch);
curl_close($ch);

$FILENAME = '/var/www/html/n.apk';
 $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API."sendDocument?chat_id=" . $chatID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $FILENAME);
    $cFile = new CURLFile($FILENAME, $finfo);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "document" => $cFile,
		"caption" => "NapsternetV",
		"reply_markup" => $encodedmenu
    ]);
    $result = curl_exec($ch);
curl_close($ch);



}
///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'تمدید اکانت') {
	
$strSQL = "SELECT * FROM users where referral='".$chatID."' " ;
$rs = mysqli_query($conn,$strSQL);
$userlist = array();
while($row = mysqli_fetch_array($rs)){ $userlist[] = "/tamdid_" .$row['username'];}
$msg = implode($newline,$userlist);
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=جهت تمدید اکانت خود اکانت خود را انتخاب کنید  :".$newline.$newline.$msg);
}
if (strpos($chattext, '/tamdid_') !== false) {
$chattext = str_replace("/tamdid_","",$chattext);
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=" . $tamdid);
}


///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'راهنما') {
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=".$rahnama);
}
///////////////////// Telegram Bot Setting ////////////////////////
if ($chattext == 'ارتباط با مدیر') {
file_get_contents($API."sendMessage?reply_markup=".$encodedmenu."&chat_id=".$chatID."&text=".$contactadmin);
}


}
$conn->close();
?>