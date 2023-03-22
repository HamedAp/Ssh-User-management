<?php 
include('header.php'); 
$editusername = "";
$editpassword = "";
$editemail = "";
$editmobile = "";
$editmultiuser = "0";
$editfinishdate ="";
$edittraffic = "0";
$editreferral = "";
if(!empty($_POST['submitnewuser']) && !empty($_POST['user']) && !empty($_POST['password'])){	
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
header("Location: index.php");

	}
if(isset($_GET['edituser'])) {
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
}
include('menu.php');
 ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">ثبت کاربر جدید</div>
						<a href="bulkuser.php" class="btn btn-danger m-t-10 btn-rounded">کاربر تعدادی</a>
						<div class="table-responsive" >
						<form action="newuser.php" method="post">
                        <div class="col-md-6">
							<div class="white-box">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری (الزامی) : </label>
												<input name="user" type="text" class="form-control" id="exampleInputEmail1" placeholder="Username" value="<?php echo $editusername; ?>" <?php if(!empty($editusername)){echo "disabled";} ?>>
												<input name="useruser" type="hidden" value="<?php echo $_GET['edituser']; ?>" >
											
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1" style="display: block;">پسورد (الزامی) : </label>
												<input style="width: 92%;display: inline-block;" name="password" type="text" class="form-control" id="exampleInputPassword1" placeholder="Password" value="<?php echo $editpassword; ?>">
												<button alt="Generate Random Password" style="width: 7%;" type="button" class="randompassword btn waves-effect waves-light "><i class="fa fa-refresh"></i></button>
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">ایمیل : </label>
												<input name="email" type="text" class="form-control" id="exampleInputPassword1" placeholder="Email" value="<?php echo $editemail; ?>">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">موبایل : </label>
												<input name="mobile" type="text" class="form-control" id="exampleInputPassword1" placeholder="Mobile" value="<?php echo $editmobile; ?>">
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
												<input name="multiuser" type="text" class="form-control" id="exampleInputPassword1" placeholder="Multi User Count" value="<?php if(empty($editmultiuser)){echo "1";}else{echo $editmultiuser;} ?>">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">تاریخ انقضا : </label>
												<input name="finishdate" type="date" class="form-control" id="exampleInputPassword1" placeholder="Expire Date" value="<?php echo $editfinishdate; ?>">
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">ترافیک : </label>
												<input name="traffic" type="text" class="form-control" id="exampleInputPassword1" placeholder="Traffic Usage" value="<?php echo $edittraffic; ?>" disabled>
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">معرف : </label>
												<input name="referral" type="text" class="form-control" id="exampleInputPassword1" placeholder="Referral" value="<?php echo $editreferral; ?>">
											</div>
											<div class="form-group" style="float: left;">
											<?php 
											if (!empty($editusername)){
											echo'<button name="editnewuser" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="editnewuser" >ویرایش کاربر</button>';
											}else{
											echo'<button name="submitnewuser" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="submitnewuser" >ثبت</button>';
											}
											?>
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