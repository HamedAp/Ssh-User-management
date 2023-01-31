<?php 
if(!empty($_GET['file'])){ 
$output = shell_exec('cp -fr /var/www/html/p/backup/'.$_GET["file"].' /var/www/html/p/tarikh');
$users = shell_exec("cat /var/www/html/p/tarikh ");
	$backupusers = preg_split("/\r\n|\n|\r/", $users);
	foreach($backupusers as $user){
		$userinfo = explode(":",$user);
		$output = shell_exec('bash /var/www/html/p/adduser '.$userinfo[0].' '.$userinfo[1].' '.$userinfo[2]);
	}
 }
include('header.php');
include('menu.php'); ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">بازگردانی بکاپ کاربران</div>
						<div class="table-responsive">
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
										$backup = str_replace("-tarikh","",$backup);
										if (!empty($backup)) {
										echo '<tr>
											<td class="text-center">'.$m.'</td>
											<td><span class="font-medium">'.$backup.'</span></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><a href="restore.php?file='.$backup.'-tarikh"><span class="label label-warning">Restore</span></a></td>
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
<?php include('footer.php') ?>