<?php 	if( $_GET["killid"] ) {$out = shell_exec('sudo kill -9 ' . $_GET["killid"]); }
		if( $_GET["username"] ) {$out = shell_exec('sudo killall -u '. $_GET["username"] ); }
include('header.php');
include('menu.php'); ?>
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
								$duplicate = array();
								$m=1;
								$onlineuserlist = preg_split("/\r\n|\n|\r/", $list);
								foreach($onlineuserlist as $user){
								$user = preg_replace('/\s+/', ' ', $user);
								$userarray = explode(" ",$user);
								$userarray[8] = strstr($userarray[8],"->");
								$userarray[8] = str_replace("->","",$userarray[8]);
								$userip = substr($userarray[8], 0, strpos($userarray[8], ":"));
								$color = "#fdaeae";
								if(!in_array($userarray[2], $duplicate)){
											$color = "#97f997";
											array_push($duplicate,$userarray[2]);
										}
								if (!empty($userarray[2]) && $userarray[2] !== "sshd" ) {
									echo '<tr style="background-color:'.$color.' !important">
                                        <td class="text-center">'.$m.'</td>
                                        <td><span class="font-medium">'.$userarray[2].'</span></td>
                                        <td><span class="font-medium">'.$userip.'</span></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><a href="/p/online.php?username='.$userarray[2].'"><span class="label label-danger">Kill All User</span></a>
										<a href="/p/online.php?killid='.$userarray[1].'"><span class="label label-danger">Kill User</span></a></td>
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
            <?php include('footer.php'); ?>