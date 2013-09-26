<?php
	/* Includes */
	require_once("include/config.inc.php");
	require_once("include/lib.inc.php");
	require_once("include/session.inc.php");
	require_once("include/connection.inc.php");
	require_once("include/flags.inc.php");
	
	$title='';
	$title = 'Search Results';
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
		// Construct search query
		$limit="LIMIT ".(($_GET['p']-1)*NUM_SEARCH_RESULTS+1).",".NUM_SEARCH_RESULTS;
			
		if($_GET['filter']==2 || $_GET['filter']==3 || $_GET['filter']==4 )
		{
			$dish_type;
			switch($_GET['filter'])
			{
				case 2: $dish_type='starter'; break;
				case 3: $dish_type='main_course'; break;
				case 4: $dish_type='dessert'; break;
			}
			
			$filter=" AND dish_type='$dish_type') ORDER BY display_name ASC";
		}
		else
		{
			// Alphabetical ordering
			$filter=") ORDER BY display_name ASC";
		}
		
		/*
		 * Search algorithm: Get the top 50 places matching the search terms, sort them by popularity,
		 * and show those that like within a radius of SEARCH_RADIUS from the user's current location.
		 */
		$query=sprintf("SELECT * FROM food_items1 WHERE normalized_name LIKE '%%%s%%' ORDER BY likes DESC",$_GET[food_search]);
		$results=mysql_query($query);
		if(!$results)
		{
			error_log("MySQL Error: ".mysql_error());
			$search_error=DB_SEARCH_ERROR;
		}
		else
		{
			$num_results=mysql_num_rows($results_with_limit);
			$total_results=mysql_num_rows($results_without_limit);
			$search_error=SEARCH_OK;
			
		}
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
	</head>
		
	<body class="home" onload="initialize()">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true"></script>
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
						<div style="border-bottom: 1px solid #F9AE44; margin:0 10px 0 10px; padding-bottom:5px;">
							<table width="100%" ><tr>
							
								<td valign="bottom" style="width:250px;">
								<font class="search_results1">Search Results</font></td>
								
								<td valign="bottom">
								
								<font class="search_results2">FOR > </font>
								
								<font class="search_results3"><?php echo $_GET['food_search'];?></font>
								<font class="search_results2">IN</font>
								<font class="search_results3"><?php echo $_GET['food_place'];?></font>
								</td>
								<td valign="bottom" style="position:relative; top:-15px;">
								
								<div style="width:150px; height:30px;font-size:13px; float:right; top:20px; ">
									<select name="order_by" id="order_by" tabindex="1">
										<option value="1">alphabetical A-Z</option>
										<option value="2">Starters</option>
										<option value="3">Main Course</option>
										<option value="4">Desserts</option>
									</select>
								</div>
								</td></tr>
							</table>
							
							<div class="clearfix"></div>
						</div>
						
						<div style="margin:0 10px 0 10px; padding-bottom:5px; margin-top:15px;">
							<div id="map_result_left" style="float:left; width:400px;">
							<?php 
								if($search_error==SEARCH_OK)
								{
									if($num_results > 0 )
									{
										while($search_result=mysql_fetch_array($results_with_limit))
										{
							?>
								<div class="map_food_item" style="padding-bottom:8px; border-bottom:1px solid #ccc; margin-top:8px;">
									<div id="left" style="float:left; width:140px;">
										<img src="images/food_item_pic.png" />
									</div>
									<div id="right" style="float:right; width:255px;">
										<h1 class="food_name">
											<a href="<?php echo get_page_url('food',array('eid'=>$search_result['eid'])); ?>" style="text-decoration:none;">
												<span style="color:#000000;text-decoration:none;"><?php echo $search_result['display_name'];?></span>
												<font class="text_orange"> @ <?php echo $search_result['restaurant_name'];?></font>
											</a>
										</h1>
										<div > 
											<?php render_like_pair($search_result['eid']);?>
											<div style="width:180px; float:right;margin-top:5px;">
												<div class="likes">
												<div style="width:<?php echo floor(1+70*$search_result['likes']/($search_result['likes']+$search_result['dislikes']+1));?>px; background:#3db54a; height:6px; float:left;"></div><br><?php echo $search_result['likes'];?> Likes
												</div>
												<div class="dislikes" style="margin-left:5px;width:80px;">
													<div style="width:<?php echo floor(1+70*$search_result['dislikes']/($search_result['likes']+$search_result['dislikes']+1));?>px; background:#ed2224; height:6px;float:left;"></div><br><?php echo $search_result['dislikes'];?> Dislikes
												</div>
											</div>
											<div class="clearfix"></div>
											<ul>
												<li>
													<span class="distance_data">
														<span class="location" id="location_<?php echo $search_result['eid'];?>" style="display:hidden;">
															<span class="latitude" name="<?php echo $search_result['location_lat'];?>"></span>
															<span class="longitude" name="<?php echo $search_result['location_lng'];?>"></span>
														</span>
														<span class="distance"></span>
													</span>
													&nbsp;miles from current location.
												</li>
											</ul>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<?php 
										}
									}
									else
									{
								?>
									<center> Your search return 0 results. Try again with different keywords.</center>
								<?php
									}
								}
								else
								{
									// Some search error has occured. Display message to user
									switch($search_error)
									{
										case NO_FOOD_ITEM_ERROR:  echo '<br/><center>You must enter a food item.</center>'; break;
										case NO_FOOD_PLACE_ERROR:  echo '<br/><center>You must enter a place to search for.</center>'; break;
										case INVALID_PAGE_ERROR:  echo '<br/><center>Please enter a valid page number.</center>'; break;
										case DB_SEARCH_ERROR:  echo '<br/><center>Database error.</center>'; break;
									}
								}
									
								?>
								
								<?php
									// Display pagination only if required
									if( $search_error==SEARCH_OK && $total_results >  NUM_SEARCH_RESULTS )
									{
								?>
								<div id="pagination">
									<a href="#" >1</a>
									<a href="#" class="selected_page">2</a>
									<a href="#" >3</a>
									<a href="#" >4</a>
									<a href="#" >5</a>
								</div>
								<?php
									}
								?>
							</div>
							
							<?php
								// Show map only if search is successfull
								
								if($search_error==SEARCH_OK && $num_results>0)
								{
							?>
							<div id="map_result_right" style="float:left; width:570px; margin-left:10px; ">
								<div style="background:#faaf42; padding:20px; color:#fff; font-weight:bold;">
								ENTER YOUR LOCATION
									<div style="float:right;">
										<span id="loc_input" style="display:none;">
											<form id="location_input_form" style='display:inline;'>
												<input id="location_name" type="text"/>
												<input type="submit" value="Search" id="location_search" />
											</form>
										</span>
										<select name="location" id="location" tabindex="1">
											<option value="1">Your Current Location</option>
											<option value="2" id="choose_loc">Choose location...</option>
										</select>
									</div>
								</div>
								<div id="map_display">
								</div>
							<?php
								}
							?>
							</div>
							
							<div class="clearfix"></div>
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
	<?php
		// Load Google Map javascript only if search is successful
		if($search_error==SEARCH_OK && $num_results>0)
		{
	?>
	<script>
		var map;
		var geocoder;
		var marker;
		var lat,lng;	
		function initialize() 
		{
			lat=get_cookie('latitude');
			lng=get_cookie('longitude');
			if(!lat && !lng)
			{
				//Get user location
				navigator.geolocation.getCurrentPosition(function(position){
				lat=position.coords.latitude;
				lng=position.coords.longitude;
				
				// Set cookies
				set_cookie('latitude',lat,7);
				set_cookie('longitude',lng,7);
				});
			}
			geocoder = new google.maps.Geocoder();
			var mapOptions = {
			  center: new google.maps.LatLng(lat,lng),
			  zoom: 12,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_display"),mapOptions);
			
			// Set marker
			marker = new google.maps.Marker({
				map: map,
				animation: google.maps.Animation.DROP,
				position: new google.maps.LatLng(lat,lng),
				draggable:true,
				title:'You are here'
			});
			
			google.maps.event.addListener(marker, 'dragend', update_distances);
			update_distances();
		}
		    
	function update_distances() 
	{
		$('.distance_data').each(function(){
			var lat=$(this).find('.latitude').attr('name');
			var lng=$(this).find('.longitude').attr('name');
			console.log('Lat:'+lat+' Long:'+lng);
			if(lat<0 || lng<0)
			{
				$(this).find('.distance').html('Unknown');
			}
			else
			{
				var present_pos=marker.getPosition();
				var d=distance(lat,lng,present_pos.lat(),present_pos.lng());
				$(this).find('.distance').html(d);
			}
		});
	}
   
	$("#location").change(function(){
		if( $("#location").val()=='2')
		{
			$("#loc_input").css('display','inline');
		}
		else if( $("#location").val()=='1' )
		{
			$("#loc_input").css('display','none');
			map.setCenter(new google.maps.LatLng(lat,lng));
		}
	});
	
	$("#location_input_form").submit(function(){
		var location=$("#location_name").val();
		if(location!='')
			codeAddress(location);
		return false;	// Disable page reload
	});
		
	 function codeAddress(address) {
		geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) 
		  {
			map.setCenter(results[0].geometry.location);
			marker.setPosition(results[0].geometry.location);
			update_distances();
		  }
		  else
		  {
			alert("Geocoding was not successful for the following reason: " + status);
		  }
		});
	}
 
	function like(eid,flag,context)
	{
		if(eid<0)
		{
			// user not logged in
			if(flag==<?php echo ACTION_LIKE;?>)
			{
				alert("Login to like this item");
			}
			else
			{
				alert("Login to dislike this item");
			}
		}
		else if(flag==<?php echo ACTION_LIKE;?>)
		{
			$.ajax({
				url:'ajax/ajax_like.php',
				data:{'eid':eid},
				success:function(data){
					if(data=='<?php echo SUCCESS;?>')
					{
						// Like success
						// Change icons
						$(context).html("<img src='images/icon_thumb_up_shadow.png' />");
						$('a[name=dislike_'+eid+']').html("<img src='images/icon_thumb_down.png' />");
						
						// Update counts
						/* Not implemented as yet */
						
					}
					else
					{
						// Some error
						switch(data)
						{
							case '<?php echo DB_ERROR;?>': alert("Database error"); break;
							case '<?php echo INSUFFICIENT_PARAMS_ERROR;?>': alert("Insufficient parameters error"); break;
							case '<?php echo NO_SESSION_EXISTS_ERROR;?>': alert("You need to login to like this."); break;
							case '<?php echo DUPLICATE_LIKE;?>': /* Do nothing */ break;
						}
					}
				}
			});
		}
		else if(flag==<?php echo ACTION_DISLIKE;?>)
		{
			$.ajax({
				url:'ajax/ajax_dislike.php',
				data:{'eid':eid},
				success:function(data){
					if(data=='<?php echo SUCCESS;?>')
					{
						// Dislike succcess
						// Change icons
						$(context).html("<img src='images/icon_thumb_down_shadow.png' />");
						$('a[name=like_'+eid+']').html("<img src='images/icon_thumb_up.png' />");
						
						// Update counts
						/* Not implemented as yet */
					}
					else
					{
						switch(data)
						{
							case '<?php echo DB_ERROR;?>': alert("Database error"); break;
							case '<?php echo INSUFFICIENT_PARAMS_ERROR;?>': alert("Insufficient parameters error"); break;
							case '<?php echo NO_SESSION_EXISTS_ERROR;?>': alert("You need to login to like this."); break;
							case '<?php echo DUPLICATE_LIKE;?>': /* Do nothing */; break;
						}
					}
				}
			});
		}
	}
	
	function distance(lat1,lon1,lat2,lon2)
	{
		var pi=3.14159265359;
		var R = 6371*0.621371; // miles
		var d = Math.acos(Math.sin(lat1*pi/180)*Math.sin(lat2*pi/180) + Math.cos(lat1*pi/180)*Math.cos(lat2*pi/180) * Math.cos((lon2-lon1)*pi/180)) * R;
		console.log(d);
		return Math.floor(d);
	}
								
		
	</script>
	<?php
		}
	?>
	</body>
</html>