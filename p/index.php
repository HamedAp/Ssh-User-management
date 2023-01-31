<?php include('header.php') ?>
<?php
if(!empty($_POST['delete'])){ shell_exec('sudo killall -u '.$_POST['delete']); $output = shell_exec('bash /var/www/html/p/delete '.$_POST['delete']);}
if(!empty($_POST['username'])){ $output = shell_exec('bash /var/www/html/p/ch '.$_POST['username'].' '.$_POST['qq'].' '.$_POST['password']);}
?>
<?php include('menu.php') ?>
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
								$m=1;
								$output = shell_exec('cat /var/www/html/p/tarikh 2>&1');
								$userlist = preg_split("/\r\n|\n|\r/", $output);
								foreach($userlist as $user){
									$userarray = explode(":",$user);
									if (!empty($userarray[0])) {
									echo '<form action="index.php" method="post">
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
			<?php include('footer.php') ?>
