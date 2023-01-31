<?php include('header.php') ?>
<?php include('menu.php') ?>
                <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">آپدیت خودکار پنل</div>
						<div class="table-responsive">
                        <div class="col-md-6">
							<div class="white-box">
								<h3 class="box-title m-b-0">توضیحات</h3>
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<?php 
										$output = shell_exec('bash /var/www/html/p/bckup  2>&1');
										$output = shell_exec('bash /var/www/html/p/update  2>&1'); echo "آپدیت با موفقیت انجام شد";  ?>
									</div>
								</div>
							</div>
						</div>
						</div>
                    </div>
                </div>
            </div>
			</div>
            <?php include('footer.php') ?>