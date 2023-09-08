<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <title>Share Link</title>
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
	<!-- icons -->
    <link href="<?php echo base_url() ?>assets/dashboard/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dashboard/assets/plugins/iconic/css/material-design-iconic-font.min.css">
    <!-- bootstrap -->
	<link href="<?php echo base_url() ?>assets/dashboard/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/dashboard/assets/css/pages/extra_pages.css">
	<!-- favicon -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/dashboard/assets/img/favicon.ico" />
</head>
<body>
    <div class="limiter">
		<div class="container-login100 page-background">
			<div class="wrap-login100">
				<form class="form-404">
					<span class="login100-form-logo">
            <?php if ($codemsg == 1) {?>
              <i class="zmdi zmdi-check"></i>
            <?php }else {?>
              <i class="zmdi zmdi-alert-circle"></i>
            <?php } ?>

					</span>
					<span class="form404-title p-b-34 p-t-27">
						<?php echo $code; ?>
					</span>
					<p class="content-404"><?php echo $msg; ?></p>
					<div class="container-login100-form-btn">
						<a href="<?php echo base_url() ?>maps" class="login100-form-btn">
              Go to home page
            </a>
					</div>
					<!-- <div class="text-center p-t-27">
						<a class="txt1" href="#">
							Need Help?
						</a>
					</div> -->
				</form>
			</div>
		</div>
	</div>
    <!-- start js include path -->
    <script src="<?php echo base_url() ?>assets/dashboard/assets/plugins/jquery/jquery.min.js" ></script>
    <!-- bootstrap -->
    <script src="<?php echo base_url() ?>assets/dashboard/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="<?php echo base_url() ?>assets/dashboard/assets/js/pages/extra_pages/login.js" ></script>
    <!-- end js include path -->
</body>
</html>
