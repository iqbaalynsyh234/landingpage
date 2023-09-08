<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Jennete RentCar</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/jennete2020/img/favicon.ico">

	<!-- CSS here -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/owl.carousel.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/slicknav.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/flaticon.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/gijgo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/animated-headline.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/magnific-popup.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/themify-icons.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/nice-select.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jennete2020/css/style.css">
</head>
<body>
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="<?php echo base_url(); ?>assets/jennete2020/img/logo/loder2.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start-->


<main class="login-body" data-vide-bg="<?php echo base_url(); ?>assets/jennete2020/img/design.jpg">
    <!-- Login Admin -->
   <form class="form-default" id="frmlogin" name="frmlogin" onsubmit="javascript : return frmlogin_onsubmit(this);">
        
        <div class="login-form">
            <!-- logo-login -->
            <!--<div class="logo-login">
                <a href="index.html"><img src="<?php echo base_url(); ?>assets/jennete2020/img/logo/loder.png" alt=""></a>
            </div>-->
            <h2>Login Here</h2>
            <div class="form-input">
                <label for="name">Username</label>
                <input  type="text" name="username" id="username" placeholder="username">
            </div>
            <div class="form-input">
                <label for="name">Password</label>
                <input type="password" name="userpass" id="userpass" placeholder="password">
            </div>
            <div class="form-input pt-30" >
                <input type="submit" name="submit" value="login" style="background-color:#5c437b">
            </div>
			
			<div class="form-input">
				<span id="dvwait" style="display:none;">
					<img src="<?=base_url();?>assets/images/anim_wait.gif" border="0" />
				</span>
			</div>
            
            <!-- 
            <a href="#" class="forget">Forget Password</a>
            
            <a href="register.html" class="registration">Registration</a>-->
        </div>
    </form>
    <!-- /end login form -->
</main>


    <script src="<?php echo base_url(); ?>assets/jennete2020/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.slicknav.min.js"></script>

    <!-- Video bg -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.vide.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/slick.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/wow.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/animated.headline.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.magnific-popup.js"></script>

    <!-- Date Picker -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/gijgo.min.js"></script>
    <!-- Nice-select, sticky -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.sticky.js"></script>
    <!-- Progress -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.barfiller.js"></script>
    
    <!-- counter , waypoint,Hover Direction -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.counterup.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/waypoints.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.countdown.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/hover-direction-snake.min.js"></script>

    <!-- contact js -->
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/contact.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.form.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/mail-script.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/jquery.ajaxchimp.min.js"></script>
    
    <!-- Jquery Plugins, main Jquery -->	
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/plugins.js"></script>
    <script src="<?php echo base_url(); ?>assets/jennete2020/js/main.js"></script>
    <script>

    jQuery(document).ready(
					
	)
                
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
    </body>
</html>