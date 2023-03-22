<?php 
include('header.php'); 
if($_POST['passtype'] == "number"){
$alphabet = '1234567890';
}
if($_POST['passtype'] == "alpha"){
$alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
}
function randomPassword($char_count,$type) {
    $alphabet = $type;
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $char_count; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
$startword = $_POST['startword'];
if(isset($_POST['submitbulkuser'])) {
if (!empty($_POST['count']) && $_POST['count'] !== "0" && !empty($_POST['numberstart'])){
$password = $_POST['password'];
$m = $_POST['numberstart'];
$count = $_POST['count'];
for ($i = 0; $i < $_POST['count']; $i++ ) {
	if ($m < $m+$count){
        $userlist[] =  $startword. $m;
		$m++;
	}
}
foreach($userlist as $user){
if(empty($password) || $password !== $_POST['password']){
$password = randomPassword($_POST['passwordnumber'],$alphabet);
}
$adduser = "INSERT INTO `users` (
 `username`,
 `password`,
 `multiuser` ,
 `finishdate`,
 `enable`) VALUES (
 '".$user."',
 '".$password."',
 '".$_POST['multiuser']."',
 '".$_POST['finishdate']."',
 'true');";
if ($conn->query($adduser) === TRUE) {}
$out = shell_exec('bash adduser '.$user.' '.$password);
$msg = '<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
لیست کاربران ایجاد شد .
</div>';
}
}
}
include('menu.php');
 ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">ثبت کاربر تعدادی</div>
						<?php echo $msg; ?>
						<div class="table-responsive" >
						<form action="bulkuser.php" method="post">
                        <div class="col-md-6">
							<div class="white-box">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
											<div class="form-group">
												<label for="exampleInputEmail1">تعداد ساخت یوزر : </label>
												<input name="count" type="number" class="form-control" id="exampleInputEmail1" value="2">
											</div>
											<div class="form-group">
												<label for="exampleInputEmail1">کلمه شروع : </label>
												<input name="startword" type="text" class="form-control" id="exampleInputEmail1" value="user">
											</div>
											<div class="form-group">
												<label for="exampleInputEmail1">عدد شروع : ( مثال : User1000 )</label>
												<input name="numberstart" type="number" class="form-control" id="exampleInputEmail1" value="1000">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1" style="display: block;">پسورد ( در صورت ثابت بودن ) - در صورت راندوم بودن پسورد فیلد زیر را خالی بزارید</label>
												<input name="password" type="text" class="form-control" id="exampleInputPassword1" >
											</div>
											<p>نوع ترکیب پسورد راندوم را انتخاب کنید : </p>
											<div class="radio radio-success">
												<input type="radio" name="passtype" id="radio4" value="number" checked>
												<label for="radio4">ترکیب اعداد</label>
											</div>
											<div class="radio radio-success">
												<input type="radio" name="passtype" id="radio6" value="alpha">
												<label for="radio6">ترکیب حروف و اعداد</label>
											</div>
											<br>
											<div class="form-group">
												<label for="exampleInputPassword1" style="display: block;">تعداد حروف پسورد : </label>
												<input name="passwordnumber" type="number" class="form-control" id="exampleInputPassword1" value="8">
											</div>
											
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="white-box">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
											<div class="form-group">
												<label for="exampleInputPassword1">کاربر همزمان : </label>
												<input name="multiuser" type="text" class="form-control" id="exampleInputPassword1" placeholder="Multi User Count" value="1">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">تاریخ انقضا : </label>
												<input name="finishdate" type="date" class="form-control" id="exampleInputPassword1" placeholder="Expire Date">
											</div>
											<div class="form-group" style="float: left;">
											<button name="submitbulkuser" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="submitnewuser" >ثبت</button>
											<a href="index.php" class="btn btn-inverse waves-effect waves-light">انصراف</a>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						
						
						
						
						</div>
                    </div>
                </div>
            </div>
			</div>
            <?php include('footer.php'); ?>