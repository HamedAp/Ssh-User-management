<?php
global $version;
$version = file_get_contents("https://konusanlar.tk/version.txt");
if ($version !== "1.3" ){
	echo '<script type="text/javascript">
	setTimeout(function () {
	$(".alertbottom").fadeToggle(350);
	}, 2000);
	</script>
	';
}
?>
<div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-warning myadmin-alert-bottom alertbottom" style="display: none;"><a href="/p/update.php">لطفا پنل خود را بروزرسانی کنید .</a></div>
<footer class="footer text-center">ShaHaN Management By HamedAp <?php echo $version; ?></footer>
        </div>
    </div>
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-rtl-master/dist/js/bootstrap-rtl.min.js"></script>
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
	<script src="js/custom.min.js"></script>
    <script src="js/dashboard3.js"></script>
	<script src="js/toastr.js"></script>
</body>
</html>