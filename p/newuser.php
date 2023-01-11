<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>SSH User Management By HamedAp</title>
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.png">
    <link href="../plugins/bower_components/bootstrap-rtl-master/dist/css/bootstrap-rtl.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
</head>
<?php 
	if(!empty($_POST['submitnewuser'])){ 
		$output = shell_exec('bash /var/www/html/p/adduser '.$_POST['username'].' '.$_POST['password'].' '.$_POST['account'].' 2>&1');
	}							
?>
<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="index.php" style="margin-right: 30px;">صفحه اصلی</a>
                </div>
            </div>
        </nav>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">منو</span></h3> 
				</div>
                <ul class="nav" id="side-menu">
					<li><a href="/update.php" class="waves-effect"><i class="fa fa-cloud-download text-danger"></i> <span class="hide-menu"> آپدیت پنل</span></a></li>
                    <li><a href="/logout.php" class="waves-effect"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu">خروج</span></a></li>
					
                </ul>
            </div>
        </div>
		<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 10px !important;" >
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کل کاربران</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter"><?php $output = shell_exec('wc -l < /var/www/html/p/tarikh 2>&1'); echo $output; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران آنلاین</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-purple"></i></li>
                                        <li class="text-right"><span class="counter"><?php $output = shell_exec('bash /var/www/html/p/online 2>&1'); $output = shell_exec('wc -l < /var/www/html/p/onlineusers 2>&1'); echo $output - 2; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران فعال</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class=""><?php $output = shell_exec('wc -l < /var/www/html/p/tarikh 2>&1'); echo $output; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران متخلف</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class="">2</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">مدیریت کاربران</div>
						<div class="table-responsive">
                        <div class="col-md-6">
							<div class="white-box">
								<h3 class="box-title m-b-0">ثبت کاربر جدید</h3>
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<form action="newuser.php" method="post">
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری</label>
												<input name="username" type="text" class="form-control" id="exampleInputEmail1" placeholder="Username"> </div>
											<div class="form-group">
												<label for="exampleInputPassword1">پسورد</label>
												<input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> </div>
											<div class="form-group">
												<label class="col-sm-12">مدت اکانت</label>
												<div class="col-sm-12" style="width: 30% !important;margin-left: 20px; ">
													<select name="account" class="form-control">
														<option value="1">یکماهه</option>
														<option value="2">دو ماهه</option>
														<option value="3">سه ماهه</option>
														<option value="6">شش ماهه</option>
														<option value="12">یک ساله</option>
													</select>
												</div>
											</div>
											<button name="submitnewuser" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="submitnewuser" >ثبت</button>
											<a href="index.php" class="btn btn-inverse waves-effect waves-light">انصراف</a>
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
            <footer class="footer text-center">SSH User Management By HamedAp 1.0</footer>
        </div>
    </div>
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-rtl-master/dist/js/bootstrap-rtl.min.js"></script>
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
	<script src="js/custom.min.js"></script>
    <script src="js/dashboard3.js"></script>
</body>
</html>