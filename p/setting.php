<?php 
global $adminuser;
$list = shell_exec('cat /etc/apache2/.htpasswd');
$adminuser = explode(":",$list);
$adminuser = $adminuser[0];
if(!empty($_POST['changepassword'])){ shell_exec('sudo htpasswd -b -c /etc/apache2/.htpasswd '.$adminuser.' '.$_POST['password']);	}
global $serverport;
$serverport = shell_exec('cat /etc/ssh/sshd_config | grep "^Port"');
$serverport = str_replace(array("\n", "\r","Port "), '', $serverport);
if(!empty($_POST['changeport'])){ 
shell_exec("sudo sed -i 's@Port ".$serverport."@Port ".$_POST['port']."@g' /etc/ssh/sshd_config");
shell_exec("sudo systemctl restart sshd");
}
include('header.php');
include('menu.php'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">تنظیمات</div>
						<div class="table-responsive" style="padding: 20px;">
							<ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#Tab1" aria-controls="Tab1" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">رمز عبور</span></a></li>
                                <li role="presentation" class=""><a href="#Tab2" aria-controls="Tab2" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">پورت SSH</span></a></li>
                            </ul>
							<div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="Tab1">
                                    <div class="col-md-6">
                                        <h3>تغییر رمز عبور مدیر : </h3>
										<form action="setting.php" method="post">
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری : </label>
												<input name="username" type="text" class="form-control" id="exampleInputEmail1" placeholder="<?php echo $adminuser; ?>" disabled>
											</div>
											<div class="form-group">
												<label for="exampleInputPassword1">رمز جدید : </label>
												<input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
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
												<input name="port" type="number" class="form-control" id="exampleInputPassword1" placeholder="<?php echo $serverport ; ?>">
											</div>
											<button name="changeport" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="changeport" >ثبت</button>
										</form>
									</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
<?php include('footer.php') ?>