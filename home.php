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
		<link rel="shorcut icon" href="<?php echo BASE_URL_2?>images/favicon.gif" />
		<link href="<?php echo BASE_URL_2?>css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/jquery-1.7.2.min.js"></script>
		<script src="<?php echo BASE_URL_2?>js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/jquery.tipTip.minified.js"></script>
		<link href="<?php echo BASE_URL_2?>css/tipTip.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
		<link rel="stylesheet" href="<?php echo BASE_URL_2?>css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
	</head>
		
	<body class="home" onload="initialize();">
			
		<!-- The popup --->
		<?php require_once(ABS_PATH_TO_HOME.'pop_up.php'); ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('<?php echo BASE_URL_2?>images/yellow_grad.png');"></div>
		
			<div id="wrapper" class="white">
				<div id="header_home">
					<div id="big_plate">
						<img src="<?php echo BASE_URL_2?>images/big_plate.png">
					</div>
					<div id="blog_home">
						<img src="<?php echo BASE_URL_2?>images/logo_blog.png">
					</div>
					<div id="logo_home">
						<a href="<?php echo BASE_URL_2?>"><img src="<?php echo BASE_URL_2?>images/logo.png"></a>
					</div>
					<?php 
						require_once ABS_PATH_TO_HOME.'main_menu.php';
					?>
					<div style="clear:both;"></div>
					<div id="text_block1" style="height:80px;">
						
					</div>
					<div style="clear:both;"></div>
					
					<!-- Food search form -->
					<div id="search_form">
						<h1 class="search_head"><img src="<?php echo BASE_URL_2?>images/what_should_i_eat.png" alt="What should I eat?"></h1>
						
					<form id="food_search_form" action="<?php echo get_page_url('map_search');?>" method="get">
					<input type="text" id="food_search" name="food_search" value="Food Item" title="Food Item" class="input1 searchfood" />
					<span style="margin-left:20px;"><b>IN</b></span>
					<input type="text" id="food_place" name="food_place" value="<?php echo FOOD_PLACE_DEFAULT_VAL;?>" title="<?php echo FOOD_PLACE_DEFAULT_VAL;?>" class="input1 searchfood" />
					
					<input type="hidden" id="food_lat" name="food_lat" value="" >
					<input type="hidden" id="food_lng" name="food_lng" value="" >
					<input type="hidden" id="food_place_name" name="food_place_name" value="" >
					
					<input type="submit" value="" id="search_submit" align="right" title="Search" >
					<div class="clearfix"></div>
					<div id="search_options">
						<a href="search?food_search=pav+bhaji&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">pav bhaji</a>,
						<a href="search?food_search=chicken+biryani&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">chicken biryani</a>,
						<a href="search?food_search=chole+bhature&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">chole bhature
						<a href="search?food_search=chaat&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">chaat</a>,
						<a href="search?food_search=kebab&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">kebab</a>,
						<a href="search?food_search=momos&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">momos</a>,
						<a href="search?food_search=parantha&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">parantha</a>,
						<a href="search?food_search=chicken+tikka&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">chicken tikka</a>,
						<a href="search?food_search=dosa&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">dosa</a>,
						<a href="search?food_search=pizza&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">pizza</a>,
						<a href="search?food_search=aloo+puri&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">aloo puri</a>,
						<a href="search?food_search=samosa&amp;food_place=Location%2FPlace&amp;food_lat=&amp;food_lng=&amp;food_place_name=">samosa</a>
											</div>
					
					<div id="">
					<?php get_upload_link();?><img src="<?php echo BASE_URL_2?>images/button_upload_photos.png"></a>
					</div>
					</form>
					</div>
					
					
					<div id="right_foodie" style="margin-top:-90px">
						<div><img src="<?php echo BASE_URL_2?>images/recently_added.png"></div>
						<div id="recent_foodie">
						<?php
							most_recent_food(4);
						?>
						</div>
					<!-- Top followers -->
					
						<div id="followers_home">
						<div >
							<img src="<?php echo BASE_URL_2?>images/top_followers.png" style="float:left">
							<div style="float:right; margin:10px 8px 0 0"><a id="prevc1" href="<?php echo get_page_url("followers");?>">View All</a></div>
							<div class="clearfix"></div>
						</div>
						<?php followers(4);?>
						</div>
					</div>
					
					<!--  Popular Items -->
					<div id="left_foodie">
						<div id="most_pop_flag"><img src="<?php echo BASE_URL_2?>images/most_popular.png"></div>
						<div id="text_block_most_pop">
							
						</div>
						
						<div id="most_pop_food" class="preload-1">
						<?php
							most_popular_food(8);
						?>
						</div>
					</div>
					
					
					<div class="clearfix"></div>
				</div>
					
		</div>
		
		<?php require_once(ABS_PATH_TO_HOME.'footer.php'); ?>
		<script type="text/javascript">
		var options = {
			script:"<?php echo BASE_URL_2?>ajax/autosuggest.php?json=true&limit=10&",
			varname:"input",
			json:true,
			delay: "100",
			timeout: "20000",
			shownoresults:false,
			maxresults:10,
			callback: function (obj) { document.getElementById('testid').value = obj.id; }
		};
		var as_json = new bsn.AutoSuggest('food_search', options);
		
		var options1 = {
			script:"<?php echo BASE_URL_2?>ajax/autosuggest_place.php?json=true&limit=10&",
			varname:"input",
			json:true,
			delay: "100",
			timeout: "20000",
			shownoresults:false,
			maxresults:10,
			callback: function (obj) { document.getElementById('testid').value = obj.id; }
		};
		var as_json = new bsn.AutoSuggest('food_place', options1);
	
	
	
</script>
	</body>
	<?php include 'include/php_js.php'; ?>
	<?php include ABS_PATH_TO_HOME.'include/tiptip.php'; ?>
	
</html>