<?php
$conn->close();
$version = "3.3";
$version = file_get_contents("https://konusanlar.tk/version.php");
$msg = "لطفا پنل خود را از طریق دستور آپدیت کنید .";
if ($version !== "3.3" ){
	echo'<div id="alerttopleft" class="myadmin-alert myadmin-alert-img alert-info myadmin-alert-top-left" style="display: block;">
 <img src="favicon.png" class="img" alt="img">
 <a href="#" class="closed">×</a>
<h4>شما یک پیام دارید!</h4><b>'.$msg.'</b></div>';
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
	<script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
	<script src="../plugins/bower_components/switchery/dist/switchery.min.js"></script>
	<script src="../plugins/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
	<script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
	<script src="js/custom.min.js"></script>
    <script src="js/dashboard3.js"></script>
	<script src="js/toastr.js"></script>
	<script type="text/javascript">
        $(".myadmin-alert .closed").click(function (event) {
            $(this).parents(".myadmin-alert").fadeToggle(350);
            return false;
        });
		$(".randompassword").click(function (event) {
			var randomstring = Math.random().toString(36).slice(-8);
			$("input[name='password']").val(randomstring);
			return false;
        });
		function getinfo(x){
			var ip = $("td[name='ip"+x+"']").html();
			var port = $("td[name='port"+x+"']").html();
			var username = $("td[name='username"+x+"']").html();
			var password = $("td[name='password"+x+"']").html();
			var msg = "Server IP : " + ip + "\nPort : " + port + "\nUsername : " + username + "\nPassword : " + password ;
			var aux = document.createElement("textarea");
			document.body.appendChild(aux);
			$('textarea').attr('id', 'msgmsg');
			$('#msgmsg').append(msg);
			$("#msgmsg").select();
			document.execCommand('copy');
			document.body.removeChild(aux);
			
			$.toast({
            heading: 'پیام جدید',
            text: 'اطلاعات کاربر کپی شد',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3500, 
            stack: 6
          });
			
		}
		
		
	 
		$(document).ready(function () {
            $("#live_search").keyup(function () {
                var query = $(this).val();
                if (query != "") {
                    $.ajax({
                        url: 'ajax-live-search.php',
                        method: 'POST',
                        data: {
                            query: query
                        },
                        success: function (data) {
                            $('#search_result').html(data);
                            $('#search_result').css('display', 'block');
                            $("#live_search").focusout(function () {
                                $('#search_result').css('display', 'block');
                            });
                            $("#live_search").focusin(function () {
                                $('#search_result').css('display', 'block');
                            });
                        }
                    });
                } else {
                    $('#search_result').css('display', 'none');
                }
            });
        });
    </script>
	<div class="jq-toast-wrap top-right"><div class="jq-toast-single jq-has-icon jq-icon-success" style="text-align: left; display: none;"><span class="jq-toast-loader jq-toast-loaded" style="-webkit-transition: width 3.1s ease-in;                       -o-transition: width 3.1s ease-in;                       transition: width 3.1s ease-in;                       background-color: #ff6849;"></span><span class="close-jq-toast-single">×</span><h2 class="jq-toast-heading">به صفحه مدیریتی مدیرخوب خوش آمدید</h2>در این جا می توانید به تمامی موارد مدیریتی وب سایت خود دسترسی داشته باشید</div></div>
	
	
</body>
</html>
