<?php 
include('header.php'); 
if(!empty($_POST['submitnewuser'])){ $output = shell_exec('bash /var/www/html/p/adduser '.$_POST['username'].' '.$_POST['password'].' '.$_POST['account']);	}							
include('menu.php'); ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">مدیریت کاربران</div>
						<div class="table-responsive">
                        <div class="col-md-6">
							<div class="white-box">
								<h3 class="box-title m-b-0">ثبت کاربر جدید</h3>
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<form action="newuser.php" method="post">
											<div class="form-group">
												<label for="exampleInputEmail1">نام کاربری</label>
												<input name="username" type="text" class="form-control" id="exampleInputEmail1" placeholder="Username"> </div>
											<div class="form-group">
												<label for="exampleInputPassword1">پسورد</label>
												<input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> </div>
											<div class="form-group">
												<label class="col-sm-12">مدت اکانت</label>
												<div class="col-sm-12" style="width: 30% !important;margin-left: 20px; ">
													<select name="account" class="form-control">
														<option value="1">یکماهه</option>
														<option value="2">دو ماهه</option>
														<option value="3">سه ماهه</option>
														<option value="6">شش ماهه</option>
														<option value="12">یک ساله</option>
													</select>
												</div>
											</div>
											<button name="submitnewuser" type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="submitnewuser" >ثبت</button>
											<a href="index.php" class="btn btn-inverse waves-effect waves-light">انصراف</a>
										</form>
									</div>
								</div>
							</div>
						</div>
						</div>
                    </div>
                </div>
            </div>
			</div>
            <?php include('footer.php'); ?>