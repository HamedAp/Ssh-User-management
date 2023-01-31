<?php 
if(!empty($_POST['backupuser'])){ $output = shell_exec('bash bckup  2>&1'); }
 include('header.php'); 
 include('menu.php'); 
 ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">بکاپ کاربران</div>
						<div class="table-responsive">
						<form action="backup.php" method="post">
						<button name="backupuser" type="submit" class="btn-rounded btn btn-primary pull-right hidden-xs hidden-sm waves-effect waves-light" value="backup" style="margin-left: 40px!important;" >بکاپ کاربران</button>
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
										$backup = str_replace("-tarikh","",$backup);
										if (!empty($backup)) {
										echo '
										<tr>
											<td class="text-center">'.$m.'</td>
											<td><span class="font-medium">'.$backup.'</span></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><a href="/p/backup/'.$backup.'-tarikh"><span class="label label-success">Download</span></a></td>
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