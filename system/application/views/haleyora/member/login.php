<!DOCTYPE html>
<html lang="en">
<head>
	<title>HALEYORA POWERINDO</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/haleyora/images/icons/favicon.ico"/>
	<!--<link rel="stylesheet" type="text/css" href="css/util.css">-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/haleyora/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/haleyora/login/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/haleyora/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->


</head>
<body>
	
	<!--  -->
	<div class="simpleslide100">
		<div class="simpleslide100-item bg-img1" style="background-image: url('<?php echo base_url(); ?>assets/haleyora/images/haleyoslide2.jpg');"></div>
		<div class="simpleslide100-item bg-img1" style="background-image: url('<?php echo base_url(); ?>assets/haleyora/images/haleyoslide.jpg');"></div>
	</div>

	<div class="flex-col-c-sb size1 overlay1">
		<!--  -->
		<!--<div class="w-full flex-w flex-sb-m p-l-80 p-r-80 p-t-22 p-lr-15-sm">
			<div class="wrappic1 m-r-30 m-t-10 m-b-10">
				<a href="#"><img src="images/icons/haleyora.png" alt="LOGO"></a>
			</div>

			<div class="flex-w m-t-10 m-b-10">
				<a href="#" class="size2 m1-txt1 flex-c-m how-btn1 trans-04">
					Sign Up
				</a>
			</div>
		</div>-->

		<!--  
		<div class="flex-col-c-m p-l-15 p-r-15 p-t-50 p-b-120">
			<h3 class="l1-txt1 txt-center p-b-40 respon1">
				Coming Soon
			</h3>
			
			<div class="flex-w flex-c-m cd100">
				<div class="flex-col-c wsize1 m-b-30">
					<span class="l1-txt2 p-b-9 days">35</span>
					<span class="s1-txt1 where1 p-l-35">Days</span>
				</div>

				<div class="flex-col-c wsize1 m-b-30">
					<span class="l1-txt2 p-b-9 hours">17</span>
					<span class="s1-txt1 where1 p-l-35">Hours</span>
				</div>

				<div class="flex-col-c wsize1 m-b-30">
					<span class="l1-txt2 p-b-9 minutes">50</span>
					<span class="s1-txt1 where1 p-l-35">Minutes</span>
				</div>

				<div class="flex-col-c wsize1 m-b-30">
					<span class="l1-txt2 p-b-9 seconds">39</span>
					<span class="s1-txt1 where1 p-l-35">Seconds</span>
				</div>
			</div>
		</div>-->

		 <!-- 
		<div class="flex-w flex-c-m p-b-35">
			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-facebook"></i>
			</a>

			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-twitter"></i>
			</a>

			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-youtube-play"></i>
			</a>
		</div>-->
		
		<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section"><img src="<?php echo base_url(); ?>assets/haleyora/images/haleyora_logo.png"></h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<!--<h3 class="mb-4 text-center">Have an account?</h3>-->
		      	<form class="signin-form" id="frmlogin" onSubmit="javascript: return frmlogin_onsubmit(this)">
		      		<div class="form-group">
		      			<input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
						<span class="fa fa-fw fa-lock field-icon"></span>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" name="userpass" id="userpass" type="password" class="form-control" placeholder="Password" required>
	              <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="form-control btn submit px-3" style="background-color:rgba(243, 157, 44, 0.8);;"><a style="color:white;" >Log In </a></button>
						<span id="dvwait" style="display:none;" class="row justify-content-center">
							<img src="<?=base_url();?>assets/images/anim_wait.gif" border="0" />
						</span>
	            </div>
	            <!--<div class="form-group d-md-flex">
	            	<div class="w-50">
		            	<label class="checkbox-wrap checkbox-primary">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
	            </div>-->
	          </form>
	          <!--<p class="w-100 text-center">&mdash; Or Sign In With &mdash;</p>
	          <div class="social d-flex text-center">
	          	<a href="#" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Facebook</a>
	          	<a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a>
	          </div>-->
		      </div>
				</div>
			</div>
		</div>
	</section>
	</div>



	


	<!-- --><script src="<?php echo base_url(); ?>assets/haleyora/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/haleyora/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>assets/haleyora/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/haleyora/js/main.js"></script>
	<script>
	function frmlogin_onsubmit()
				{
					jQuery("#dvwait").show();
					jQuery.post("<?=base_url();?>member/dologin", jQuery("#frmlogin").serialize(),
						function(r)
						{
							jQuery("#dvwait").hide();
							if (r.error)
							{
								alert(r.message);
								return;
							}

							location = r.redirect;
						}
						, "json"
					);
					return false;
				}
	</script>
	<script src="<?php echo base_url(); ?>assets/haleyora/js/maintoggle.js"></script>
</body>
</html>