<?php 
include('header.php'); 
include('menu.php');
$msg = file_get_contents("https://konusanlar.tk/changelog.txt");
?>
 <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading" style="display: inline-block;">تغییرات آپدیت</div>
						<div class="table-responsive" style="padding-left: 40px !important;direction: ltr;">
						<p><?php echo $msg ; ?> </p>
						</div>
                    </div>
                </div>
            </div>
			</div>
<?php include('footer.php'); ?>