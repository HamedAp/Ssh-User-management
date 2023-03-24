<?php 
include('header.php');
include('menu.php');
// require('function.php');
global $active;
global $msg;
$serverip = $_SERVER['SERVER_NAME'];
$strSQL = "SELECT * FROM setting" ;
$rs = mysqli_query($conn,$strSQL);
while($row = mysqli_fetch_array($rs)){ 
$TGToken = $row['tgtoken']; 
$TGID = $row['tgid']; 
}
$strSQL = "SELECT * FROM tgmessage" ;
$rs = mysqli_query($conn,$strSQL);
while($row = mysqli_fetch_array($rs)){ 
$msg1 = $row['account1m']; 
$msg2 = $row['account2m']; 
$msg3 = $row['account3m']; 
$msg4 = $row['account6m']; 
$msg5 = $row['account12m']; 
$msg6 = $row['contactadmin']; 
$msg7 = $row['rahnama']; 
$msg8 = $row['tamdid']; 
}
///////////////////// Telegram Message Setting ////////////////////////
if(!empty($_POST['changetelegrammessages'])){
$sql = "UPDATE tgmessage SET account1m='".$_POST['account1m']."',account2m='".$_POST['account2m']."',account3m='".$_POST['account3m']."',account6m='".$_POST['account6m']."',account12m='".$_POST['account12m']."',contactadmin='".$_POST['contactadmin']."',rahnama='".$_POST['rahnama']."',tamdid='".$_POST['tamdid']."'" ;
if($conn->query($sql) === true){}
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
پیام های ربات تلگرام ذخیره شد . 
</div>';
}
///////////////////// Telegram Bot Setting ////////////////////////
if(!empty($_POST['changetgsetting'])){
$sql = "ALTER TABLE `setting` MODIFY `tgtoken` LONGTEXT;";
if($conn->query($sql) === true){}
$sql = "UPDATE setting SET tgtoken='".$_POST['TGToken']."'" ;
if($conn->query($sql) === true){}
$sql = "UPDATE setting SET tgid='".$_POST['TGID']."'" ;
if($conn->query($sql) === true){}
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
تغییرات تلگرام انجام شد .
</div>';
if (!empty($TGToken) && !empty($TGID)){
	 file_get_contents("https://api.telegram.org/bot".$TGToken."/setWebhook?url=https://".$serverip."/tgbot.php");
}
}
///////////////////// Change Admin Password ////////////////////////
if(!empty($_POST['changepassword'])){
if(!empty($_POST['password'])){
$sql = "SET PASSWORD FOR '".$username."'@'localhost' = PASSWORD('".$_POST['password']."');" ;
if($conn->query($sql) === true){}
$sql = "FLUSH PRIVILEGES;" ;
if($conn->query($sql) === true){}
$sql = "GRANT ALL ON *.* TO '".$username."'@'localhost';" ;
if($conn->query($sql) === true){}
$sql = "UPDATE setting SET adminpassword='".$_POST['password']."'" ;
if($conn->query($sql) === true){}
file_put_contents("/var/www/html/p/config.php", str_replace('$password = "'.$password, '$password = "'.$_POST['password'], file_get_contents("/var/www/html/p/config.php")));
$out = shell_exec('sudo htpasswd -b -c /etc/apache2/.htpasswd '.$username.' '.$_POST['password']);
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
رمز با موفقیت تغییر یافت.
</div>';
}else {
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
رمزعبور نمیتواند خالی باشد .
</div>';
}}
///////////////////// Change Port ////////////////////////
if(!empty($_POST['changeport'])){
if(!empty($_POST['port'])){
$out = shell_exec("sudo sed -i 's@Port ".$port."@Port ".$_POST['port']."@g' /etc/ssh/sshd_config");
$out = shell_exec("sudo reboot");
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
پورت با موفقیت تغییر یافت. لطفا تا ریستارت شدن سرور صبر نمایید . ( تقریبا 2 دقیقه )
</div>';
$sql = "UPDATE setting SET sshport='".$_POST['port']."'" ;
if($conn->query($sql) === true){}
$file_contents = file_get_contents("config.php");
$file_contents = str_replace($port, $_POST['port'], $file_contents);
file_put_contents("config.php", $file_contents);
}else{
	$msg = '<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
پورت نمیتواند خالی باشد .
</div>';
}
}
///////////////////// User Limit Count ////////////////////////
if(!empty($_POST['limitusers'])){
$limit = shell_exec('(crontab -l ; echo "* * * * * bash /var/www/html/p/killusers.sh >/dev/null 2>&1") | crontab -');
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
محدودیت کاربر فعال شد . 
</div>';
$active = "فعال";
}
if(!empty($_POST['notlimitusers'])){
$notlimit = shell_exec("crontab -l | grep -v '/var/www/html/p/killusers.sh'  | crontab  -");
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
محدودیت کاربر غیرفعال شد . لطفا به مدت 1 دقیقه صبر کنید تا به صورت کامل غیرفعال شود . 
</div>';
$active = "غیرفعال";
}
$limitactive = shell_exec("crontab -l");
if (strpos($limitactive, "/var/www/html/p/killusers.sh") !== false) {
$active = "فعال";
}else {
$active = "غیرفعال";
}
///////////////////// Multi Server Config ////////////////////////
if(!empty($_POST['multiserver']) && !empty($_POST['serverip']) && !empty($_POST['serverusername']) && !empty($_POST['serverpassword'])){
$addserver = "INSERT INTO `servers` (
 `serverip`,
 `serverusername`,
 `serverpassword`) VALUES (
 '".$_POST['serverip']."',
 '".$_POST['serverusername']."',
 '".$_POST['serverpassword']."');";
if ($conn->query($addserver) === TRUE) {}
$out = shell_exec("bash addserver " .$_POST['serverusername']." ". $_POST['serverpassword']. " %" );
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
سرور اضافه شد 
</div>';
	}
if(!empty($_GET['removeserver'])){
$sql = "delete FROM servers where serverip='".$_GET['removeserver']."'";
if($conn->query($sql) === true){}
$out = shell_exec("bash removeserver " .$_GET['user']." %" );
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
سرور حذف شد .
</div>';
}
/////////////////////  Backup ////////////////////////
$date = date('Y-m-d-his');
if(!empty($_GET['backupuser'])){ $output = shell_exec("mysqldump -u '".$username."' --password='".$password."' ShaHaN users > /var/www/html/p/backup/".$date.".sql"); 
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
بکاپ با موفقیت انجام شد 
</div>';
}
if(!empty($_GET['delete'])){ 
$output = shell_exec('rm -fr /var/www/html/p/backup/'.$_GET["delete"]);
$msg = '<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
فایل حذف شد 
</div>';
}
/////////////////////  Restore ////////////////////////
if(!empty($_POST['uploadsql'])){
$target_file = "backup/" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if($imageFileType != "sql"  ) {
$msg = '<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
فقط فایل های SQL پشتیبانی میشود .
</div>';
  $uploadOk = 0;
}
 if ($uploadOk == 0) {
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
فایل با موفقیت آپلود شد .
</div>';
  } else {
    $msg = '<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
خطای آپلود .
</div>';
  }
}
 }
if(!empty($_GET['file'])){
if (strpos($_GET['file'], ".sql") !== false) {
$output = shell_exec("mysql -u '".$username."' --password='".$password."' ShaHaN < /var/www/html/p/backup/".$_GET['file']);
$sql = "SELECT * FROM users where enable='true'" ;
$rs = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($rs)){
$out = shell_exec('bash adduser '.$row["username"].' '.$row["password"]);
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
بازگردانی کاربران با موفقیت انجام شد
</div>';
 }
}else {
$msg = '<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
فقط فایل های SQL پشتیبانی میشود .
</div>';
}
 }
  /////////////////////  Token ////////////////////////
//  create token
 if(!empty($_POST['createtoken'])){
	$nettoken = gen_token();
	$addtoken = "INSERT INTO ApiToken (enable,Token,Description,Allowips) VALUES ('true','".$nettoken."','".$_POST['Description']."','".$_POST['Allowips']."');";
	if ($conn->query($addtoken) === TRUE) {
	   $msg = '<div class="alert alert-success alert-dismissable">
	   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			توکن اضافه شد
	   </div>';
	   }
 }
//  remove api token 
 if(!empty($_GET['remove_token'])){
	$sql = "delete FROM ApiToken where Token='".$_GET['remove_token']."'";
	if($conn->query($sql) === true){}
	$msg = '<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	توکن با موفقیت حذف شد
	</div>';
 }
//  revoke api token
 if(!empty($_GET['revoke_token'])){
   $nettoken = gen_token();
   $sql = "UPDATE ApiToken SET Token='".$nettoken."'WHERE Token='".$_GET['revoke_token']."'" ;
   if($conn->query($sql) === true){}
   $msg = '<div class="alert alert-success alert-dismissable">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   توکن جدید '.$nettoken.'
   </div>';
 }
 //////////////////
 ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">تنظیمات</div>
						<?php echo $msg; ?>
						<div class="table-responsive" style="padding: 20px;">
							<ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#Tab1" aria-controls="Tab1" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">رمز عبور</span></a></li>
                                <li role="presentation" class=""><a href="#Tab2" aria-controls="Tab2" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">پورت SSH</span></a></li>
								<li role="presentation" class=""><a href="#Tab3" aria-controls="Tab3" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">محدودیت کاربر</span></a></li>
								<li role="presentation" class=""><a href="#Tab4" aria-controls="Tab4" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">ربات تلگرام</span></a></li>
                            	<li role="presentation" class=""><a href="#Tab5" aria-controls="Tab5" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">مولتی سرور</span></a></li>
                            	<li role="presentation" class=""><a href="#Tab6" aria-controls="Tab6" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">بکاپ و ریستور</span></a></li>
								<li role="presentation" class=""><a href="#Tab7" aria-controls="Tab7" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">توکن API</span></a></li>
							</ul>
							<div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="Tab1">
                                    <div class="col-md-6">
                                        <h3>تغییر رمز عبور مدیر : </h3>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری : </label>
												<input name="username" type="text" class="form-control" id="exampleInputEmail1" placeholder="<?php echo $username; ?>" disabled>
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">رمز جدید : </label>
												<input name="password" type="text" class="form-control" id="exampleInputPassword1" placeholder="Password">
											</div>
											<button name="changepassword" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changepassword" >ثبت</button>
										</form>
									</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="Tab2">
                                    <div class="col-md-6">
                                        <h3>تغییر پورت SSH : </h3>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputPassword1">پورت جدید : </label>
												<input name="port" type="number" class="form-control" id="exampleInputPassword1" placeholder="<?php echo $port ; ?>">
											</div>
											<button name="changeport" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changeport" >ثبت</button>
										</form>
									</div>
                                    <div class="clearfix"></div>
                                </div>
								<div role="tabpanel" class="tab-pane" id="Tab3">
                                    <div class="col-md-6">
                                        <h3>محدودیت استفاده کاربران : </h3>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputPassword1">وضعیت محدودیت : </label>
												<input name="port" type="number" class="form-control" id="exampleInputPassword1" placeholder="<?php echo $active; ?>" disabled>
											</div>
											<button name="limitusers" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changeport" >فعال کردن</button>
											<button name="notlimitusers" type="submit" class="btn btn-danger waves-effect waves-light m-r-10" value="changeport" >غیرفعال کردن</button>
										</form>
									</div>
                                    <div class="clearfix"></div>
                                </div>								
								<div role="tabpanel" class="tab-pane" id="Tab4">
                                    <div class="col-md-6">
                                        <h3>تنظیمات ربات تلگرام : </h3>
										<p>مرحله اول : یک دامنه تهیه کرده و از طریق دستور گیت هاب SSL ثبت کنین .</p>
										<p><a href="https://github.com/HamedAp/Ssh-User-management">لینک گیت هاب برای تهیه SSL </a></p>
										<p>مرحله دوم : با دامنه وارد پنل شوید و در قسمت تنظیمات توکن تلگرام و آی دی مدیر را وارد کنید </p>
										<p>در صورتی که با توکن و آی دی تلگرام مدیر آشنایی ندارید در گروه تلگرامی سوال فرمایید . </p>
										<p>بعد از ثبت کردن توکن و ای دی به ربات خود پیام /start را داده و لذت ببرید . </p>
										<p>دونیت برای سازنده یادتون نره :) </p>
										<hr>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputPassword1">توکن ربات تلگرام : </label>
												<input name="TGToken" type="text" class="form-control" id="exampleInputPassword1" value="<?php echo $TGToken ; ?>" style="direction: ltr;">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">آی دی مدیر : ( به صورت عددی )</label>
												<input name="TGID" type="text" class="form-control" id="exampleInputPassword1" value="<?php echo $TGID ; ?>" style="direction: ltr;">
											</div>
											<button name="changetgsetting" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changetg" >ثبت</button>
										</form>
										<hr>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label class="col-md-12">توضیحات اکانت یک ماهه : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="account1m"><?php echo $msg1; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">توضیحات اکانت دو ماهه : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="account2m"><?php echo $msg2; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">توضیحات اکانت سه ماهه : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="account3m"><?php echo $msg3; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">توضیحات اکانت شش ماهه : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="account6m"><?php echo $msg4; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">توضیحات اکانت یکساله</label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="account12m"><?php echo $msg5; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">تماس با مدیر : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="contactadmin"><?php echo $msg6; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">راهنما : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="rahnama"><?php echo $msg7; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">تمدید اکانت : </label>
												<div class="col-md-12">
													<textarea class="form-control" rows="5" name="tamdid"><?php echo $msg8; ?></textarea>
												</div>
											</div>
											<button name="changetelegrammessages" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changetelegrammessages" >ثبت</button>
										</form>
									</div>
                                    <div class="clearfix"></div>
                                </div>
								<div role="tabpanel" class="tab-pane" id="Tab5">
                                    <div class="col-md-6">
                                        <h3>تنظیمات چند سرور</h3>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputEmail1">آی پی سرور : </label>
												<input name="serverip" type="text" class="form-control" id="exampleInputEmail1" >
											</div>
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری : </label>
												<input name="serverusername" type="text" class="form-control" id="exampleInputEmail1" >
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">رمز جدید : </label>
												<input name="serverpassword" type="text" class="form-control" id="exampleInputPassword1" >
											</div>
											<button name="multiserver" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="multiserver" >اضافه کردن</button>
										</form>
										<h3>لیست سرور ها : </h3>
<?php
$m=1;
$strSQL = "SELECT * FROM servers" ;
$rs = mysqli_query($conn,$strSQL);
echo '<div class="table-responsive" style="padding-bottom: 200px !important;overflow: inherit;">
                            <table class="table table-hover manage-u-table">
                                <thead>
								<tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>سرور</th>
									<th>نام کاربری</th>
                                    <th>رمز عبور</th>
									<th>حذف سرور</th>
                                </tr>
								</thead>
                                <tbody>';
while($row = mysqli_fetch_array($rs)){
echo '<tr>
		<td class="text-center">'.$m.'</td>
		<td>'.$row['serverip'].'</td>
		<td>'.$row['serverusername'].'</td>
		<td>'.$row['serverpassword'].'</td>
		<td><a href="setting.php?user='.$row['serverusername'].'&removeserver='.$row['serverip'].'" ><i class="fa fa-minus-circle text-danger" style="font-size:20px;"></i></a></td>';
$m++;
}
?>
</tbody>
</table>
</div>
									</div>
                                    <div class="clearfix"></div>
                                </div>
								<div role="tabpanel" class="tab-pane" id="Tab6">
                                    <div class="col-md-6">
									<h3>بکاپ و ریستور</h3>
										<a href="setting.php?backupuser=<?php echo $date; ?>" class="btn btn-primary m-t-10 btn-rounded" style="margin-right: 40px !important;">بکاپ جدید</a></br></br><hr>
										<form action="setting.php" method="post" style="display:block;" enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload" style="float: right !important;margin-left: 20px!important;display: inline !important;margin-right: 50px;">
						<button name="uploadsql" type="submit" class="btn-rounded btn btn-primary pull-right waves-effect waves-light" value="upload" style="float: right !important;margin-left: 40px!important;" >آپلود بکاپ</button>
						</form><hr>
						<table class="table table-hover manage-u-table">
                                <thead>
									<tr>
                                        <th width="70" class="text-center">#</th>
                                        <th>تاریخ بکاپ</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th width="250"></th>
                                        <th width="300">دریافت</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
										$m = 1 ;
										$output = shell_exec("ls /var/www/html/p/backup");
										$backuplist = preg_split("/\r\n|\n|\r/", $output);
										foreach($backuplist as $backup){
										if (!empty($backup)) {
										echo '
										<tr>
											<td class="text-center">'.$m.'</td>
											<td><span class="font-medium">'.$backup.'</span></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><a href="/p/backup/'.$backup.'"><span class="label label-success">Download</span></a>
											<a href="setting.php?delete='.$backup.'"><span class="label label-danger">Remove</span></a>
											<a href="setting.php?file='.$backup.'"><span class="label label-warning">Restore</span></a></td>
										</tr>'; 
										}
										$m++;
										}	?>
								</tbody>
                        </table>
									</div>
                                    <div class="clearfix"></div>
                                </div>
								<div role="tabpanel" class="tab-pane" id="Tab7">
                                    <div class="col-md-6">
									<h3>توکن API</h3>
									<form action="setting.php" method="post">
										<h3>ساخت توکن جدید</h3>
											<div class="form-group">
												<label for="exampleInputEmail1">توضیحات توکن</label>
												<input name="Description" type="text" class="form-control" id="exampleInputEmail1" >
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">آی پی های مجاز</label>
												<input name="Allowips" type="text" class="form-control" id="exampleInputPassword1" placeholder="Password" value="0.0.0.0/0">
											</div>
											<button name="createtoken" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="createtoken" >ثبت</button>
										</form>
										<hr>
										<h3>لیست توکن ها </h3>
										<?php
										$m=1;
										$strSQL = "SELECT * FROM ApiToken" ;
										$rs = mysqli_query($conn,$strSQL);
										echo '<div class="table-responsive" style="padding-bottom: 200px !important;overflow: inherit;">
																	<table class="table table-hover manage-u-table">
																		<thead>
																		<tr>
																			<th width="70" class="text-center">#</th>
																			<th>Token</th>
																			<th>توضیحات</th>
																			<th>آی پی های مجاز</th>
																			<th>نوسازی توکن</th>
																			<th>غیر فعال کردن</th>
																		</tr>
																		</thead>
																		<tbody>';
										while($row = mysqli_fetch_array($rs)){
										echo '<tr>
												<td class="text-center">'.$m.'</td>
												<td>'.$row['Token'].'</td>
												<td>'.$row['Description'].'</td>
												<td>'.$row['Allowips'].'</td>
												<td><a href="setting.php?revoke_token='.$row['Token'].'" ><i class="fa fa-refresh text-warning" style="font-size:20px;"></i></a></td>
												<td><a href="setting.php?remove_token='.$row['Token'].'" ><i class="fa fa-minus-circle text-danger" style="font-size:20px;"></i></a></td>';
												$m++;
										}
										?>
										</tbody>
										</table>
										<hr>
									</div>
                                    <div class="clearfix"></div>
                                </div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
<?php include('footer.php'); ?>