<?php
	require_once('include/config.inc.php');
	require_once('include/lib.inc.php');
	require_once('include/session.inc.php');
	require_once('include/connection.inc.php');
	require_once('include/functions.inc.php');
	if($_POST["complete"])
	{
		if($_POST["rest_address"])
		{
			mysql_query("update restaurant set res_address='$_POST[rest_address]' where rsID='$_POST[rest_id]'");
		}
		if($_POST["rest_city"])
		{
			mysql_query("update restaurant set res_city='$_POST[rest_city]' where rsID='$_POST[rest_id]'");
		}
		if($_POST["rest_state"])
		{
			mysql_query("update restaurant set res_state='$_POST[rest_state]' where rsID='$_POST[rest_id]'");
		}
		
		if($_POST["cost"])
		{
	
			mysql_query("update food_items set cost='$_POST[cost]' where food_items_id='$_POST[complete]'");
		}
		
		header('Location: '.get_page_url("food",array("eid"=>$_POST["complete"])));
	}
	else
	{
	$title='';
	$title = 'Upload Images';
	$upload_error=false;
	$food_id = preg_replace("/[^0-9]/","",$_GET["food"]);
	require_once('include/food_item.inc.php');
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
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/4sqacplugin.js" type="text/javascript"></script>
		
		<style>
.ui-autocomplete-loading { background: white url('http://jqueryui.com/demos/autocomplete/images/ui-anim_basic_16x16.gif') right center no-repeat; }

#venue-label {
	display: block;
	font-weight: bold;
	margin-bottom: 1em;
}
#venue-icon {
	float: left;
	height: 32px;
	width: 32px;
	vertical-align: middle;
}
#venue-address {
	margin: 0;
	padding: 0;
}
#venue-city {
	margin: 0;
	padding: 0;
}
	
.ui-menu-item {
	min-height: 40px;
background: #F8C174;
width: 270px;
padding: 5px 10px;
margin-left: 10px;
border: 1px solid white;
cursor: pointer;
}
	
.categoryIconContainer {
	border-radius: 3px 3px 3px 3px;
    float: left;
    height: 32px;
    margin-right: 5px;
    overflow: hidden;
    width: 32px;
    vertical-align: middle;
	display:none;
}
.autocomplete-name {
	color: #444;
font-weight: bold;
font-size: 13px;
overflow: hidden;
text-overflow: ellipsis;
    }
.autocomplete-detail {
  	color: #333;
font-weight: normal;
font-size: 13px;
margin-top: 5px;
overflow: hidden;
text-overflow: ellipsis;
    }
	
article, aside, figure, footer, header, hgroup, 
menu, nav, section { display: block; }
</style>
	</head>
		
	<body class="home">
	
	<!-- The popup --->
		<?php require_once 'pop_up.php'; ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<?php require_once 'header_gen.php'; ?>
								
				<div id="content">
					<?php require_once 'page_search.php'; ?>
					
					<div id="upload_left">
					
					<!-- Error Box -->
											
										
					 
					<div id="food_result_left" style="border-bottom:1px dashed #ccc; padding-bottom:5px;" >
					<h1 class="food_name" style="font-size:20px;">
					Thank You for Sharing...
					</h1>
					<?php if($food['dish_image']) { ?>
							<img src="<?php echo BASE_URL."/".IMG_DIR?><?php echo $food['dish_image']; ?>" style="float:left; margin-right:10px; margin-top:8px;"/>
					<?php } ?>
								<div class="food_item" style="padding-bottom:8px;  margin-top:8px; ">
										<h1 class="food_name" style="font-size:24px;"><?php echo $food['dish_name']; ?><br><font class="text_orange">@ <?php echo $restr['res_name']; ?></font></h1>
										<div class="food_name"><?php echo	$restr["res_address"]; ?></div>
										<div class="food_name" style="display:inline;"><?php echo	$restr["res_city"]; ?></div>
										<div class="food_name" style="display:inline;"><?php echo	$restr["res_state"]; ?></div>	
										<div style="margin-top:10px;"><?php echo $food['description']; ?></div>
								</div>

						<div class="clearfix"></div>
					</div>
					
					<!-- **********Complete form*************** -->
						<div class="clearfix"></div>	
					<form enctype="multipart/form-data" id="food_form" action="<?php echo $_SERVER['PHP_SELF'];?>" METHOD="POST">
					<input type="hidden" name="complete" value="<?php echo $food['dish_url']?>" />
					<input type="hidden" name="rest_id" value="<?php echo $rest_id?>" />
					<?php 
					$flag=0;
					if(!$check_restr) {
						$flag=1;?>
					<h1 class="heading_type1 size2">Restaurant Details: </h1>
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
					
					<?php if(!$restr["res_address"]) { ?>
					
						<input type="text" id="rest_address" name="rest_address" placeholder="Restaurant's Street Address"  class="input1 searchfood" style="width:570px;"/>
					<?php } ?>
					
					<?php if(!$restr["res_city"]) { ?>
					
						<input type="text" id="rest_city" name="rest_city" placeholder="City" title="City" class="input1 searchfood" style="width:570px;"/>
					<?php } ?>
					
					<?php if(!$restr["res_state"]) { ?>
					
						<input type="text" id="rest_state" name="rest_state" placeholder="State" title="State" class="input1 searchfood" style="width:570px;"/>
					<?php } ?>
					
					
					
					</div>
					<?php } ?>
					
					<?php if($check_cost == 1) { $flag=1; ?>
					<div style="border-bottom:1px dashed #ccc; margin:10px 0; padding:10px 0 15px 0">
					<span class="heading_type1 size2">Cost</span>
					<span style="margin-left:80px;">
						<input type="text" id="cost" name="cost" value="Cost" title="Cost" class="input1 searchfood" style="width:100px; display:inline;"/>
					</span>
					</div>
					<?php } ?>
					
					</form>
					
										
					<!-- Form submit button -->
					<?php if($flag==1)
					{
					?>
					<input type="button" value="Submit" class="button1" id="upload_submit" style="float:left;color:#fff; margin-right:20px;"/>
					<?php
					}
					?>
					<?php if($flag==0)
					{
					?>
					<br>
					<?php }?>
					<a  class="button3" id="food_page" href="<?php 
					$url = get_page_url("food",array("eid"=>$food["dish_url"])); echo $url;?>" style="float:right;color:#fff; background:#f39917; "/>Food Page</a>
					
					<a  href="<?php echo get_page_url("home")?>" class="button3" id="home_page" style="float:right;color:#fff; background:#f39917; margin-right:10px;"/>Skip to home</a>
					
					</div>
					
					<!--  ******** Upload form ends *********** -->
					
					<div id="upload_right">
						<div id="most_pop_flag"><img src="images/recently_added.png"></div>
												
						<div id="most_pop_food" style="margin-left:0px;">
							<?php
							most_popular_food(4);
						?>
						</div>
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
	<script type="text/javascript">
		
		$('#upload_submit').click(function(){
			$('#food_form').submit();
		});
		
		
		</script>

	</body>
	
		
</html>
<?php } ?>