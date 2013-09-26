<?php
	/* Includes */
	require_once("include/config.inc.php");
	require_once("include/lib.inc.php");
	require_once("include/session.inc.php");
	require_once("include/connection.inc.php");
	require_once("include/flags.inc.php");
	
	$title='';
	$title = 'Forgot Password';
	$search_error=SEARCH_OK;	// Flag for identifying search error
	
	/* 'food_search' and 'food_place' are compulsory */
	if($_GET['food_search']!='' && $_GET['food_place']!='')
	{
		$_GET['food_search']=mysql_real_escape_string($_GET['food_search']);
		$_GET['food_place']=mysql_real_escape_string($_GET['food_place']);
	}
	else
	{
		if($_GET['food_search']=='')
		{
			$search_error=NO_FOOD_ITEM_ERROR;
		}
		else
		{
			$search_error=NO_FOOD_PLACE_ERROR;
		}
	}
	
	// Page number is optional but must be integer >0
	if( $_GET['p']!='')
	{
		if(!is_an_integer($_GET['p']) || (is_an_integer($_GET['p']) && $_GET['p']<1) )	// from lib.inc.php
		{
			$search_error=INVALID_PAGE_ERROR;
		}
	}
	else
	{
		$_GET['p']=1;
	}
	
	// Perform search if there are no errors 
	if($search_error==SEARCH_OK)
	{
		$query="SELECT * FROM food_items";
		$results_without_limit = mysql_query($query);
		$num_results=mysql_num_rows($results_without_limit);
		//$total_results=mysql_num_rows($results_without_limit);
		$search_error=SEARCH_OK;
	}	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title>FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script src="js/crossfade_images.js" type="text/javascript"></script>
		<script src="js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.selectbox-0.2.min.js"></script>
		<link href="css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
		<style>
		


		</style>
	</head>
		
	<body class="home" onload="initialize()">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>

	

  </head>
 	
	<?php require_once("./include/fb_js.inc.php"); ?>
	
	<!-- The popup --->
		<?php require_once 'pop_up.php'; ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<?php require_once 'header_gen.php'; ?>
								
				<div id="content">
					<?php require_once 'page_search.php'; ?>
						
							<div id="" style="margin-top:80px" align="center">
								<span style="color: #77675f;">Enter your email-id:</span> <input type="text" id="email_reset" value="Your Email" title="Your Email" class="input1 searchfood" style="display:inline; width:220px;"/>&nbsp;&nbsp;<button style="font-family: Quicksand_book;
padding: 10px;
border: none;color:#fff;
border-radius: 10px;background: #77675f;
cursor: pointer;" onclick="reset_password()">Reset Password!</button><br><br>
<span id="resetInfo" ></span>
							</div>
						
					
				</div>
		
		
		
		</div>
		<br>
		<div id="footer">
			<div id="wrapper">
			<div id="footer_left">
			<img src="./images/logo_footer.png" style="display:inline; margin-top:20px; float:left;">
				<ul>
					<li class="footer_menu_current"><a href="#home">Home</a></li>
					<li><a href="#profile">Profile</a></li>
					<li><a href="#dine">Dine</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#map">Map</a></li>
				</ul>
			</div>
			
			<div id="footer_right">
				<div id="copyright">
					&copy; 2012 - All Right Reserved by FoodiesCompass.
					<br/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed erat lacus, ac elementum urna. Nulla sed tellus sed augue varius bibendum id quis turpis. 
				</div>
				<div id="bread_img"></div>
				
			</div>
			</div>
	</div>
	
	<script>
	
		function initialize() 
		{
		
			
		}
function reset_password()
{

$('#resetInfo').html('loading');
//$('.sorting').removeClass('selected');

$("#resetInfo").load("resend_password.php", {email: $('#email_reset').val() });
}
	
</script>
	
	</body>
</html>