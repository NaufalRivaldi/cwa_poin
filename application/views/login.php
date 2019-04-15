<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="keywords" content="">
<meta property="og:title" content="">
<meta name="description" content="">
<title>Login Page - Sistem Skor Produk Citra Warna</title>
<link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url()?>css/font-awesome.min.css">
<link rel="stylesheet" href="<?=base_url()?>css/alertify.core.css">
<link rel="stylesheet" href="<?=base_url()?>css/alertify.default.css">
<link rel="stylesheet" href="<?=base_url()?>css/login.min.css">
</head>
<Body>
<div id="wrapper">
	<div class="container">
	<form action="login/process" method="post">

		<div class="row">
			<div class="col-xs-10 col-xs-push-1 col-sm-push-3 col-sm-6 col-md-4 col-md-push-4">
				<div class="loginbox">
					<h1><span class="fa fa-key"></span> Log In Sistem Skor Produk Citra Warna</h1>
					<div class="formcontent">
						<label for="username">Username</label>
						<input type="text" placeholder="Username Anda" name="username" id="username" class="form-control">
						<label for="pass">Password</label>
						<input type="password" name="password" placeholder="Password Anda" id="pass" class="form-control">
						<div class="padd">
							<button class="btn btn-primary" name="btn"><span class="fa fa-key"></span> Log In</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
	</div>
</div>
	
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/less-1.3.3.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/alertify.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/modernizr.custom.28468.js"></script>

<script type="text/javascript">
$(function(){
	$(".notif").click(function(){
		$(this).next(".notif-container").slideToggle();
		$(this).children(".num").hide();
	});
	$(".first").click(function(){
		$(".submenu, .showmenu").slideUp();
		$(this).next(".submenu, .showmenu").slideToggle();

	});
	$(".box-error, .box-success, .box-warning, .box-information").click(function(){
		$(this).slideToggle();
	});

	<?php
		$this->def->pesan_alertify();
	?>

});
</script>
</Body>
</html>