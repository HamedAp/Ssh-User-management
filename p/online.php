<?php 	if( $_GET["killid"] ) {$out = shell_exec('sudo kill -9 ' . $_GET["killid"]); }
		if( $_GET["username"] ) {$out = shell_exec('sudo killall -u '. $_GET["username"] ); }
?>
<?php include('header.php') ?>
<?php include('menu.php') ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">لیست کاربران آنلاین</div>
						<div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
								 <tr>
                                        <th width="70" class="text-center">#</th>
                                        <th>نام کاربری</th>
                                        <th>IP</th>
                                        <th></th>
                                        <th></th>
                                        <th width="250"></th>
                                        <th width="300">مدیریت کردن</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
								$m=1;
								$onlineuserlist = preg_split("/\r\n|\n|\r/", $list);
								foreach($onlineuserlist as $user){
								$user = preg_replace('/\s+/', ' ', $user);
								$userarray = explode(" ",$user);
								if (strpos($userarray[7], "@") !== false) {
								$userarray[7] = substr($userarray[7], 0, strpos($userarray[7], "@"));
								}
								$userarray[4] = substr($userarray[4], 0, strpos($userarray[4], ":"));
								$userarray[6] = str_replace("/sshd:","",$userarray[6]);
								if (!empty($userarray[4]) && $userarray[7] !== "root" ) {
									echo '<tr>
                                        <td class="text-center">'.$m.'</td>
                                        <td><span class="font-medium">'.$userarray[7].'</span></td>
                                        <td><span class="font-medium">'.$userarray[4].'</span></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
										<a href="/p/online.php?username='.$userarray[7].'"><span class="label label-danger">Kill User</span></a>
										</td>
                                    </tr>';
								}
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
            <?php include('footer.php') ?>