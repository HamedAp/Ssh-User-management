<?php 
include('header.php'); 
include('menu.php'); 
$date = date('Y-m-d-his');
if(!empty($_GET['backupuser'])){ $output = shell_exec('mysqldump -u '.$username.' --password='.$password.' ShaHaN users > /var/www/html/p/backup/'.$date.'.sql'); 
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
 ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">بکاپ کاربران</div>
						<?php echo $msg; ?>
						<div class="table-responsive">
						<a href="backup.php?backupuser=<?php echo $date; ?>" class="btn btn-primary m-t-10 btn-rounded" style="margin-right: 40px !important;">بکاپ کاربران</a>
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
											<a href="backup.php?delete='.$backup.'"><span class="label label-danger">Remove</span></a></td>
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