<?php 
global $msg;
include('header.php');
include('menu.php'); 
$date = date('Y-m-d');
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
$output = shell_exec('mysql -u '.$username.' --password='.$password.' ShaHaN < /var/www/html/p/backup/'.$_GET["file"]);
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
?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">بازگردانی بکاپ کاربران</div>
						<?php echo $msg; ?>
						<div class="table-responsive">
						<form action="restore.php" method="post" style="display:block;" enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload" style="float: right !important;margin-left: 20px!important;display: inline !important;margin-right: 50px;">
						<button name="uploadsql" type="submit" class="btn-rounded btn btn-primary pull-right waves-effect waves-light" value="upload" style="float: right !important;margin-left: 40px!important;" >آپلود بکاپ</button>
						</form>
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
										$output = shell_exec("ls /var/www/html/p/backup ");
										$backuplist = preg_split("/\r\n|\n|\r/", $output);
										foreach($backuplist as $backup){
										if (!empty($backup)) {
										echo '<tr>
											<td class="text-center">'.$m.'</td>
											<td><span class="font-medium">'.$backup.'</span></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><a href="restore.php?file='.$backup.'"><span class="label label-warning">Restore</span></a></td>
										</tr>'; 
										}
									$m++;
										}	?>
						  </tbody>
                            </table>
						</div>
                    </div>
                </div>
            </div>
			</div>
<?php include('footer.php'); ?>