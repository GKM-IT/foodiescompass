<?php
require_once("./include/config.inc.php");
require_once("./include/lib.inc.php");
require_once("./include/session.inc.php");
require_once("./include/connection.inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title>FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="shorcut icon" href="images/favicon.gif" />
		<link href="css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script src="js/crossfade_images.js" type="text/javascript"></script>
		<script src="js/jinclude.js" type="text/javascript"></script>
	</head>
		
	<body class="home">
	<?php require_once("./include/fb_js.inc.php"); ?>

	<!-- The popup -->
		<?php require_once("pop_up.php");?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<div id="header">
					<div id="plate">
						<img src="images/plate.png">
					</div>
					<div id="blog">
						<img src="images/logo_blog.png">
					</div>
					<div id="explore">
						<a href="<?php echo get_page_url("home");?>"><img src="images/start_button.png"></a>
					</div>
					<div id="mobile">
						<div style="float:left;">Coming soon on</div> <img src="images/icon_android.png"><img src="images/icon_ios.png">
					</div>
					<div id="logo">
						<a href="<?php echo BASE_URL_2;?>"><img src="images/logo.png"></a>
					</div>
					
					<!-- Top menu -->
					<?php require_once('main_menu.php'); ?>
					
					
				</div>
			</div>
				
			
			
				<div id="slider" style="">
				<div id="top_shadow">
				</div>
					<div id="cycler">
						<div style="background:url(images/old_slider/slider_4_pic.jpg)" ></div>
						<div style="background:url(images/old_slider/slider_3_pic.jpg)" ></div>
						<div style="background:url(images/old_slider/slider_1_pic.jpg)" ></div>
						<div style="background:url(images/old_slider/slider_2_pic.jpg)" ></div>
					</div>
					<div id="bottom_shadow">
				</div>
					<div id="menu_text" >
						<ul style="list-style:none; padding:0px; margin:0;">
							<li class="show" >
								<font class="menu_text_big">Choosing what to eat? </font>
								<br>
								<font class="menu_text_small">Explore the best dishes at your city's restaurants now</font>
							</li>
							<li class="hide">
								<font class="menu_text_big">Looking for the best Pao Bhaji near you?</font>
								<br>
								<font class="menu_text_small">Find out right here</font>
							</li>
							<li class="hide">
								<font class="menu_text_big">Discovered amazing Chicken Curry?</font>
								<br>
								<font class="menu_text_small">Upload photos and let your friends know</font>
							</li>
							<li class="hide">
								<font class="menu_text_big">Loved eating out recently?</font>
								<br>
								<font class="menu_text_small">Voice your opinion</font>
							
							</li>
							</ul>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
				<div id="wrapper" class="white">
				<div id="content">
				<div id="slider_thumbs">
					<ul>
						<li class="current_slide">
							<div class="thumb_container">
								<img src="images/old_slider/slider_4_pic_thumb.jpg" class="current_slide">
							</div>
							<div class="half_black" class="hide" rel="0">
							</div>
						</li>
						
						<li class="">
							<div class="thumb_container">
								<img src="images/old_slider/slider_3_pic_thumb.jpg" class="current_slide">
							</div>
							<div class="half_black" class="" rel="1">
							</div>
						</li>
						<li class="">
							<div class="thumb_container">
								<img src="images/old_slider/slider_1_pic_thumb.jpg" class="current_slide">
							</div>
							<div class="half_black" class="" rel="2">
							</div>
						</li>
						<li class="">
							<div class="thumb_container">
								<img src="images/old_slider/slider_2_pic_thumb.jpg" class="current_slide">
							</div>
							<div class="half_black" class="" rel="3">
							</div>
						</li>
						
					</ul>
				</div>
				<div id="text_block2">
					<span class="text_style1">LET US FIND YOUR PERFECT MASALA DOSA</span><br/>
					Get food reviews, photographs and more..<br/>
					<div id="fb_button_holder">
						<a class="login_popup" id="fb_signup_btn" href="javascript:void(0);"></a>
					</div>
					
				</div>
				
					
		</div>
		
		
		
		</div>
		<?php require_once("footer.php"); ?>
	</body>
	<script>
	 $('#cycler div').css('z-index',1);
	  $('#cycler div:first').css('z-index',3).addClass('active');
	</script>
	<?php include 'include/php_js.php'; ?>
	<?php include 'include/tiptip.php'; ?>
</html>