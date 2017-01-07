<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Sentir, Responsive admin and dashboard UI kits template">
		<meta name="keywords" content="admin,bootstrap,template,responsive admin,dashboard template,web apps template">
		<meta name="author" content="Ari Rusmanto, Isoh Design Studio, Warung Themes">
		<title>Login</title>

		<base href="<?php echo CONFIG_BASE_HREF;?>">
 
		<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		<link href="<?php echo CONFIG_BASE_HREF;?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="<?php echo CONFIG_BASE_HREF;?>/css/style.css" rel="stylesheet">
		<link href="<?php echo CONFIG_BASE_HREF;?>/css/style-responsive.css" rel="stylesheet">
 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
 
	<body class="login tooltips">
	
		
		
		
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<div class="login-header text-center">
			<img src="<?php echo CONFIG_BASE_HREF;?>/img/logo-lew.jpg" class="logo" alt="Logo">
		</div>
		<div class="login-wrapper">
			<?php if (count($arrErrors)>0) { ?>

			<div class="alert alert-danger alert-bold-border fade in alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Warning!</strong> <br/>
				<?php echo implode($arrErrors); ?>.
			</div>

			<?php } ?>

			<form role="form" action="backend/login" method="post">
				<div class="form-group has-feedback lg left-feedback no-label">
				  <input type="text" name="uname" class="form-control no-border input-lg rounded" placeholder="Enter username" autofocus>
				  <span class="fa fa-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback lg left-feedback no-label">
				  <input type="password" name="upass" class="form-control no-border input-lg rounded" placeholder="Enter password">
				  <span class="fa fa-unlock-alt form-control-feedback"></span>
				</div>
				<div class="form-group">
				  <div class="checkbox">
					<label>
					  <input type="checkbox" name="save" class="i-yellow-flat"> Remember me
					</label>
				  </div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-warning btn-lg btn-perspective btn-block">LOGIN</button>
				</div>
			</form>
			<!--<p class="text-center"><strong><a href="forgot-password.html">Forgot your password?</a></strong></p>
			<p class="text-center">or</p>
			<p class="text-center"><strong><a href="register.html">Create new account</a></strong></p>-->
		</div><!-- /.login-wrapper -->
		<!--
		===========================================================
		END PAGE
		===========================================================
		-->
		
		<!--
		===========================================================
		Placed at the end of the document so the pages load faster
		===========================================================
		-->
		<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
		<script src="<?php echo CONFIG_BASE_HREF;?>/js/jquery.min.js"></script>
		<script src="<?php echo CONFIG_BASE_HREF;?>/js/bootstrap.min.js"></script>
		<script src="<?php echo CONFIG_BASE_HREF;?>/plugins/retina/retina.min.js"></script>
		<script src="<?php echo CONFIG_BASE_HREF;?>/plugins/nicescroll/jquery.nicescroll.js"></script>
		<script src="<?php echo CONFIG_BASE_HREF;?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo CONFIG_BASE_HREF;?>/plugins/backstretch/jquery.backstretch.min.js"></script>

		<!-- MAIN APPS JS -->
		<script src="<?php echo CONFIG_BASE_HREF;?>/js/apps.js"></script>
		
	</body>
</html>