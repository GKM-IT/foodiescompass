<?php
 $TITLE='home';

 require_once("include/session.inc.php");
 require_once("include/config.inc.php");
 require_once("include/lib.inc.php");
 require_once("include/functions.inc.php");
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
		<script src="js/jinclude.js" type="text/javascript"></script>
	</head>
		
	<body class="home" onload="initialize();">
			
		<!-- The popup --->
		<?php require_once('pop_up.php'); ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');"></div>
		
			<div id="wrapper" class="white">
				<div id="header_home">
					<div id="big_plate">
						<img src="images/big_plate.png">
					</div>
					<div id="blog_home">
						<img src="images/logo_blog.png">
					</div>
					<div id="logo_home">
						<a href="index.php"><img src="images/logo.png"></a>
					</div>
					<?php 
						require_once 'main_menu.php';
					?>
					<div style="clear:both;"></div>
					<div id="text_block1">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vel turpis nisl. Cras et purus ut diam convallis posuere. Fusce feugiat ultricies leo, eget gravida neque dapibus vitae.
					</div>
					<div style="clear:both;"></div>
					
					<!-- Food search form -->
					<div id="search_form">
						<h1 class="search_head"><img src="images/what_should_i_eat.png" alt="What should I eat?"></h1>
						
					<form id="food_search_form" action="map_search.php" method="get">
					<input type="text" id="food_search" name="food_search" value="Food Item" title="Food Item" class="input1 searchfood" />
					<span style="margin-left:20px;"><b>IN</b></span>
					<input type="text" id="food_place" name="food_place" value="<?php echo FOOD_PLACE_DEFAULT_VAL;?>" title="<?php echo FOOD_PLACE_DEFAULT_VAL;?>" class="input1 searchfood" />
					<input type="submit" value="" id="search_submit" align="right" title="Search" >
					<div class="clearfix"></div>
					<div id="search_options">
						tomato, potato, onion, porcini, garlic, spaghetti, rissoto,
						potato, onion, porcini, garlic, spaghetti, rissoto, tomato,
						tomato, potato, onion, porcini, garlic, spaghetti, rissoto,
						potato, onion, porcini, garlic, spaghetti, rissoto, tomato,
											</div>
					
					<div id="">
					<?php get_upload_link();?><img src="images/button_upload_photos.png"></a>
					</div>
					</form>
					</div>
					
					
					<div id="right_foodie">
						<div><img src="images/recently_added.png"></div>
						<div id="recent_foodie">
						<?php
							most_recent_food(3);
						?>
						</div>
					<!-- Top followers -->
					
						<div id="followers_home">
						<div ><img src="images/top_followers.png"></div>
						<?php
						$top_follower_result = mysql_query("SELECT user_id,unique_id,name,followers,follows,about_me FROM userinfo ORDER BY points LIMIT 0,3");
											
						while($top_follower=mysql_fetch_array($top_follower_result))
						{
							$num_followers=($top_follower["followers"]=="")? 0: count(explode(",",$top_follower["followers"]));
							$num_follows=($top_follower["follows"]=="")? 0: count(explode(",",$top_follower["follows"]));
						?>
						<div id="follower_item">
							<div id="follower_pic">
								<a href="<?php echo get_page_url("profile",array("uid"=>$top_follower["unique_id"]));?>" style="border:none;">
									<img src="<?php echo get_page_url("profile_pic",array("user_id"=>$top_follower["user_id"])); ?>">
								</a>
							</div>
							<div id="follower_about">
							<h1 class="food_name">
								<a href="<?php echo get_page_url("profile",array("uid"=>$top_follower["unique_id"]));?>" style="border:none;">
									<?php echo $top_follower["name"];?>
								</a>
							</h1>
							<span class="italic_text"><?php echo $top_follower["about_me"];?></span>
								<p class="followers_stats">
								<?php echo $num_follows;?> Following<br>
								<?php echo $num_followers;?> Followers<br></p>
								<?php render_follow_button($top_follower["user_id"]);?>
								
								</div>
								<div id="follower_badge">
								<img src="images/profile_badge.png">
								<img src="images/profile_badge.png"><br>
								<img src="images/profile_badge.png">
								<img src="images/profile_badge.png">
								</div>
								
								<div class="clearfix"></div>
						</div>
						<?php } ?>
						</div>
					</div>
					
					<!--  Popular Items -->
					<div id="left_foodie">
						<div id="most_pop_flag"><img src="images/most_popular.png"></div>
						<div id="text_block_most_pop">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vel turpis nisl. Cras et purus ut diam convallis posuere. Fusce feugiat ultricies leo, eget gravida neque dapibus vitae.
						</div>
						
						<div id="most_pop_food" class="preload-1">
						
						</div>
					</div>
					
					
					<div class="clearfix"></div>
				</div>
					
		</div>
		
		<?php require_once('footer.php'); ?>
	</body>
	<?php include 'include/php_js.php'; ?>
</html>