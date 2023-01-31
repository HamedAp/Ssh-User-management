<?php 
global $list; 
$list = shell_exec("sudo netstat -atnp | grep ':5829 '  | grep ESTABLISHED | grep -v accep | grep -v root"); ?>
<body class="fix-header">
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part" style="width: 200px !important;">
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
					<li><a href="index.php" class="waves-effect"><i class="fa fa-home"></i>  <span class="hide-menu" style="margin-right: 5px !important;">صفحه اصلی</span></a></li>
                    <li><a href="online.php" class="waves-effect"><i class="fa fa-user"></i>  <span class="hide-menu" style="margin-right: 5px !important;">لیست کاربران آنلاین</span></a></li>
					<li><a href="backup.php" class="waves-effect"><i class="fa fa-cloud-download"></i>  <span class="hide-menu" style="margin-right: 5px !important;">بکاپ کاربران</span></a></li>
					<li><a href="restore.php" class="waves-effect"><i class="fa fa-cloud-upload"></i>  <span class="hide-menu" style="margin-right: 5px !important;">بازگردانی کاربران</span></a></li>
					<li><a href="update.php" class="waves-effect"><i class="fa fa-refresh"></i>  <span class="hide-menu text-danger" style="margin-right: 5px !important;"> آپدیت پنل</span></a></li>
					<li><a href="setting.php" class="waves-effect"><i class="fa fa-cog"></i>  <span class="hide-menu" style="margin-right: 5px !important;">تنظیمات</span></a></li>
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
                                        <li class="text-right"><span class="counter"><?php $output = shell_exec('wc -l < tarikh 2>&1'); echo $output; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران آنلاین</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-purple"></i></li>
                                        <li class="text-right"><span class="counter"><?php echo substr_count( $list, "\n" ); ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران فعال</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class=""><?php $output = shell_exec('wc -l < tarikh 2>&1'); echo $output; ?></span></li>
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