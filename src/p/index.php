<?php 
include('header.php'); 
require('function.php'); 

$strSQL = "SELECT * FROM users" ;
if(!empty($_GET['activeuser'])){
$sql = "UPDATE users SET enable='true' where username='".$_GET['activeuser']."'" ;
if($conn->query($sql) === true){}
$out = shell_exec('bash adduser '.$_GET['activeuser'].' '.$_GET['password']);
}
if(!empty($_GET['deactiveuser'])){
$sql = "UPDATE users SET enable='false' where username='".$_GET['deactiveuser']."'" ;
if($conn->query($sql) === true){}
$out = shell_exec('bash delete '.$_GET['deactiveuser']);
}
if(!empty($_GET['removeuser'])){
$sql = "delete FROM users where username='".$_GET['removeuser']."'";
if($conn->query($sql) === true){}
$out = shell_exec('bash delete '.$_GET['removeuser']);
}
$sort = "desc";
if($_GET['sortby'] == "desc"){
$strSQL = "select * from users ORDER BY id desC;" ;
$sort = "asc";
}
if($_GET['sortby'] == "asc"){
$strSQL = "select * from users ORDER BY id asc;" ;
$sort = "desc";
}
include('menu.php'); 
?>
               <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">مدیریت کاربران</div>
						<a href="newuser.php" class="btn btn-danger m-t-10 btn-rounded">کاربر جدید</a>
	<div class="container mt-5" style="max-width: 555px;margin: 20px;float: left;">
        <input type="text" class="form-control" name="live_search" id="live_search" autocomplete="off"
            placeholder="Search ...">
        <div id="search_result" style="z-index: 999;width: 525px;position: absolute;"></div>
    </div>
						<div class="table-responsive" style="padding-bottom: 200px !important;overflow: inherit;">
                            <table class="table table-hover manage-u-table">
                                <thead>
								<tr>
										<th width="70" class="text-center"><a href="index.php?sortby=<?php echo $sort; ?>"><i class="ti-arrow-up text-info" style="font-size: 10px;"></i><i class="ti-arrow-down text-info" style="font-size: 10px;"></i></a></th>
                                        <th>نام کاربری</th>
										<th>رمز عبور</th>
                                        <th>محدودیت</th>
                                        <th>اطلاعات ارتباطی</th>
                                        <th>زمان</th>
                                        <th width="250">وضعیت</th>
                                        <th width="300">مدیریت کردن</th>
                                    </tr>
								</thead>
                                <tbody>
								<?php 
								$m=1;
								
								$rs = mysqli_query($conn,$strSQL);
								while($row = mysqli_fetch_array($rs)){
								if ($row['enable'] == "true") {$status = '<a class="btn btn-success m-t-10 btn-rounded">فعال</a>';}else {$status = '<a class="btn btn-danger m-t-10 btn-rounded">غیرفعال</a>';}
								echo '
									<tr>
										<td name="ip'.$m.'" style="display:none;">'.$_SERVER['SERVER_NAME'].'</td>
										<td name="port'.$m.'" style="display:none;">'.$port.'</td>
										<td class="text-center" name="id">'.$m.'</td>
										<td name="username'.$m.'">'.$row['username'].'</td>
										<td name="password'.$m.'">'.$row['password'].'</td>
										<td>ترافیک : '.$row['traffic'].'
											<br><span class="text-muted">کاربر همزمان : '.$row['multiuser'].'</span></td>
										<td>'.$row['email'].'
											<br><span class="text-muted">'.$row['mobile'].'</span></td>
										<td>تاریخ ثبت نام : '.$row['startdate'].'
											<br><span class="text-muted">تاریخ انقضا : '.$row['finishdate'].'</span></td>
										<td>
											'.$status.'
										</td>
										<td>
											<div class="btn-group m-r-10">
                                    <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toggle waves-effect waves-light" type="button"> <i class="fa fa-cog m-r-5"></i> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="newuser.php?edituser='.$row['username'].'">ویرایش</a></li>
                                        <li><a href="index.php?activeuser='.$row['username'].'&password='.$row['password'].'">فعال کردن</a></li>
                                        <li><a href="index.php?deactiveuser='.$row['username'].'">غیرفعال کردن</a></li>
                                        <li class="divider"></li>
                                        <li><a href="index.php?removeuser='.$row['username'].'" style="color:Tomato;">حذف</a></li>
                                    </ul>
                                </div>
								<button type="button" class="userinfo btn waves-effect waves-light btn-info" onclick="getinfo('.$m.')"><i class="fa fa-link"></i></button>
										</td>
									</tr>';
										$m++; 
								}
									?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
			</div>
			<?php include('footer.php'); ?>