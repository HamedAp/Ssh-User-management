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
if(!empty($_POST['delete'])){ 
	$output = shell_exec('bash /var/www/html/p/delete '.$_POST['delete']);
							}
if(!empty($_POST['username'])){ 
	$output = shell_exec('bash /var/www/html/p/ch '.$_POST['username'].' '.$_POST['qq'].' '.$_POST['password']);
							}
?>
<body class="fix-header">
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
					<li><a href="update.php" class="waves-effect"><i class="fa fa-cloud-download text-danger"></i> <span class="hide-menu"> آپدیت پنل</span></a></li>
                    <li><a href="logout.php" class="waves-effect"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu">خروج</span></a></li>
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
                                        <li class="text-right"><span class="">Soon</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">مدیریت کاربران</div>
						<a href="newuser.php" class="btn btn-info btn-outline  btn-circle btn-lg m-r-5"  style="background-color: blue;color: white;margin: 10px !important;float: left;display: inline-block;"><i class="ti-plus"></i></a>
						<div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
								 <tr>
                                        <th width="70" class="text-center">#</th>
                                        <th>نام کاربری</th>
                                        <th>رمز عبور</th>
                                        <th>تاریخ ثبت</th>
                                        <th>تاریخ انقضا</th>
                                        <th width="250"></th>
                                        <th width="300">مدیریت کردن</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
								$output = shell_exec('cat /var/www/html/p/tarikh 2>&1'); 
								$m=1;
								$userlist = preg_split("/\r\n|\n|\r/", $output);
								foreach($userlist as $user){
									$userarray = explode(":",$user);
									if (!empty($userarray[0])) {
                                    echo '
									<form action="index.php" method="post">
									<input type="hidden" name="qq" value="'.$userarray[1].'" >
									<tr>
                                        <td class="text-center">'.$m.'</td>
                                        <td><span class="font-medium">'.$userarray[0].'</span></td>
                                        <td><input name="password" style="display: inline !important;width: 50% !important;" type="text" class="form-control" value="'.$userarray[1].'" > <button style="margin-right: 8px !important;" name="username" type="submit" class="btn btn-info btn-outline btn-circle btn-lg m-r-5" value="'.$userarray[0].'" ><i class="ti-key"></i></button></td>
                                        <td>'.$userarray[2].'</td>
                                        <td>'.date('Y-m-d', strtotime($userarray[2]. ' + '.$userarray[3].' month')) .'</td>
                                        <td>
                                        </td>
                                        <td>
										<button name="delete" type="submit" class="btn btn-info btn-outline btn-circle btn-lg m-r-5"  value="'.$userarray[0].'" ><i class="ti-trash"></i></button>
                                        </td>
                                    </tr></form>';
									$m++;
								}}
									?>
                                </tbody>
                            </table>
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