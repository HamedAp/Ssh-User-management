<?php
global $list; 
$list = shell_exec("sudo lsof -i :".$port." -n | grep -v root | grep ESTABLISHED");
$sql = "SELECT * FROM users" ;
if ($result = mysqli_query($conn, $sql)) {
    $usertotal = mysqli_num_rows( $result );
}
$sql = "SELECT * FROM users where enable='true'" ;
if ($result = mysqli_query($conn, $sql)) {
    $useractive = mysqli_num_rows( $result );
}
$sql = "SELECT * FROM users where enable='false'" ;
if ($result = mysqli_query($conn, $sql)) {
    $userdeactive = mysqli_num_rows( $result );
}
///////////////////////// SERVER INFO  ///////////////////
$free = shell_exec('free');
$free = (string)trim($free);
$free_arr = explode("\n", $free);
$mem = explode(" ", $free_arr[1]);
$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); });
$mem = array_merge($mem); 
$memtotal = round($mem[1] / 1000000,2);
$memused = round($mem[2] / 1000000,2);
$memfree = round($mem[3] / 1000000,2);
$memtotal = str_replace(" GB","",$memtotal);
$memused = str_replace(" GB","",$memused);
$memfree = str_replace(" GB","",$memfree);
$memtotal = str_replace(" MB","",$memtotal);
$memused = str_replace(" MB","",$memused);
$memfree = str_replace(" MB","",$memfree);
$usedperc = ( 100 / $memtotal ) * $memused ;
//////////////////////////////////////////////////
$exec_loads = sys_getloadavg();
$exec_cores = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
$cpu = round($exec_loads[1]/($exec_cores + 1)*100, 0) ;
//////////////////////////////////////////////////
$diskfree = round(disk_free_space(".") / 1000000000);
$disktotal = round(disk_total_space(".") / 1000000000);
$diskused = round($disktotal - $diskfree);
$diskusage = round($diskused/$disktotal*100);
//////////////////////////////////////////////////


$traffic_rx = shell_exec('sudo netstat -e -n -i |  grep "RX packets" | grep -v "RX packets 0" | grep -v " B)"');
$traffic_tx = shell_exec('sudo netstat -e -n -i |  grep "TX packets" | grep -v "TX packets 0" | grep -v " B)"');
$res = preg_split("/\r\n|\n|\r/", $traffic_rx);
foreach($res as $resline){
$resarray = explode(" ",$resline);
$download+= $resarray[13];
}
$res = preg_split("/\r\n|\n|\r/", $traffic_tx);
foreach($res as $resline){
$resarray = explode(" ",$resline);
$upload+= $resarray[13];
}
$total = $download + $upload;

 ?>
<body class="fix-header">
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
				<ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i class="ti-close ti-menu"></i></a></li>
                </ul>
                <div class="top-left-part" style="width: 200px !important;">
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
					<li><a href="setting.php" class="waves-effect"><i class="fa fa-cog"></i>  <span class="hide-menu" style="margin-right: 5px !important;">تنظیمات</span></a></li>
					<li><a href="#" class="waves-effect"><i class="fa fa-book"></i>  <span class="hide-menu" style="margin-right: 5px !important;">آموزش و مستندات</span></a></li>
					<li><a href="checkip.php" class="waves-effect"><i class="fa fa-lock"></i>  <span class="hide-menu" style="margin-right: 5px !important;">وضعیت فیلترینگ</span></a></li>
                	<li><a href="https://t.me/ShaHaNPanel" class="waves-effect"><i class="fa fa-group"></i>  <span class="hide-menu text-success" style="margin-right: 5px !important;">گروه تلگرام</span></a></li>
					<li><a href="changelog.php" class="waves-effect"><i class="fa fa-refresh"></i>  <span class="hide-menu" style="margin-right: 5px !important;">تغییرات آپدیت</span></a></li>
               </ul>
            </div>
        </div>
		<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 10px !important;" >
							
							<div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="padding: 7px !important;">
                                    <a style="padding-left: 80px;padding-right: 15px;">مصرف رم : </a>
									<div class="pie animate no-round" style="--p:<?php echo round($usedperc); ?>;--c:orange;"> <?php echo round($usedperc); ?>%</div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="padding: 7px !important;">
                                    <a style="padding-left: 35px;padding-right: 15px;">مصرف سی پی یو : </a>
									<div class="pie animate no-round" style="--p:<?php echo round($cpu); ?>;--c:orange;"> <?php echo round($cpu); ?>%</div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="padding: 7px !important;">
                                    <a style="padding-left: 60px;padding-right: 15px;">مصرف هارد : </a>
									<div class="pie animate no-round" style="--p:<?php echo round($diskusage); ?>;--c:orange;"> <?php echo round($diskusage); ?>%</div>
                                </div>
                            </div>
							
							<div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="padding:35px;padding-bottom: 32px;">
                                    <h3 class="box-title">ترافیک مصرف شده</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-globe text-info"></i></li>
                                        <li class="text-right"><span class="counter" style="font-size: 25px;"><?php  echo formatBytes($total); ?></span></li>
                                    </ul>
                                </div>
                            </div>
							
							
                            <div class="col-lg-3 col-sm-6 col-xs-12">
							<a href="index.php">
                                <div class="white-box">
                                    <h3 class="box-title">کل کاربران</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-info"></i></li>
                                        <li class="text-right"><span class="counter"><?php  echo $usertotal; ?></span></li>
                                    </ul>
                                </div>
							</a>
                            </div>
							
							
                            <div class="col-lg-3 col-sm-6 col-xs-12">
							<a href="online.php">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران آنلاین</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-warning"></i></li>
                                        <li class="text-right"><span class="counter"><?php echo substr_count( $list, "\n" ); ?></span></li>
                                    </ul>
                                </div>
								</a>
                            </div>
							
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران فعال</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-success"></i></li>
                                        <li class="text-right"><span class=""><?php  echo $useractive; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">کاربران غیرفعال</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-danger"></i></li>
                                        <li class="text-right"><span class=""><?php echo $userdeactive; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>