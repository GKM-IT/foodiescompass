<?php
/**
 *  Expects a 'eid' parameter and a 'back_url'[optional]
 */

	require_once("./include/config.inc.php");
	require_once("./include/lib.inc.php");
	require_once("./include/session.inc.php");
	require_once("./include/connection.inc.php");
	require_once("./include/flags.inc.php");
	
	
	
	// Keeps track of error while generating this page
	$page_error=SUCCESS;
	
	if($_GET['eid']!='')
	{
		$_GET['eid']=mysql_real_escape_string($_GET['eid']);
		
		// Search in database
		if(is_numeric($_GET['eid']))
		$sql="SELECT * from food_items WHERE food_items_id='$_GET[eid]'";
		else
		$sql="SELECT * from food_items WHERE dish_url='$_GET[eid]'";
		//echo $sql;
		$result=mysql_query($sql);
		if(!$result)
		{
			$page_error=DB_ERROR;
		}
		else
		{
			if(mysql_num_rows($result)==0)
			{
				// No match with eid found
				$page_error=SEARCH_ERROR;
			}
			else
			{
				// Match found. Generate page.
				$page_error=SUCCESS;
			}
		}
	}
	else
	{
		// An eid was not passed.
		$page_error=INSUFFICIENT_PARAMS_ERROR;
	}
	$food=mysql_fetch_array($result);
	$sql_rest="SELECT * from restaurant WHERE rsID='$food[rest_id]'";
	$result_rest=mysql_query($sql_rest);
	$rest = mysql_fetch_array($result_rest);
	
	$food_id = $food["food_items_id"];
	$title_top = $food["dish_name"].' at '.$rest["res_name"];
	$title= $food["dish_name"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title><?php echo $title_top; ?> FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="shorcut icon" href="images/favicon.gif" />
		<link href="css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script src="js/crossfade_images.js" type="text/javascript"></script>
		<script src="js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.selectbox-0.2.min.js"></script>
		<link href="css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/jquery.tipTip.minified.js"></script>
		<link href="<?php echo BASE_URL_2?>css/tipTip.css" type="text/css" rel="stylesheet" />
		<link href="css/lightbox.css" rel="stylesheet" />
		<script src="js/lightbox.js"></script>
	</head>
		
	<body class="home" onLoad="initialize()">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>
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
								<?php
									if(isset($_GET['back_url']))
									{ 
								?>
								<a href="<?php echo $_GET['back_url'];?>" class="search_results1" style="font-size:20px;">< Back to List</a></td>
								<?php
									} 
								?>
								</td></tr>
							</table>
							
							<div class="clearfix"></div>
						</div>
						
						<?php
							
						?>
						<div style="margin:0 10px 0 10px; padding-bottom:5px; margin-top:15px;">
						<input type="hidden" name="food_id" id="food_id" value="<?php echo $food["food_items_id"]?>"/>
							<div id="food_result_left" style="float:left; width:400px; position:relative;">
							
                            <!-- /////////////////////////// -->
                            
                            <div>
							<a href="<?php echo get_page_url("food_pic",array("fid"=>$food["food_items_id"], "size"=>"full")); ?>" rel="lightbox[sims]" style="text-decoration: none;"><img src="<?php echo get_page_url("food_pic",array("fid"=>$food["food_items_id"], "size"=>"normal")); ?>" style="margin-left:5px;"/>
							</a></div>
                            
                            
                            <!-- /////////////////////////// -->
							<div style="margin-top: -14px; float:left; display:none ">
								<img src="<?php echo BASE_URL_2?>images/icon_info.png" style="position:absolute; z-index:90"  class="tooltip" title="This picture is just representation of food item and may/ may not represent the actual dish.">
								<div class="clearfix"></div>
							</div>
							<br>
							<div id="more_images" style="margin: 5px 0 0 5px;">
							<?php
							$more_img = mysql_query("select image_id from images where food_id='$food[food_items_id]' and main_pic='0'");
							while($img = mysql_fetch_array($more_img))
							{
							
							?>
							<a href="<?php echo get_page_url("food_image",array("fid"=>$img["image_id"], "size"=>"full"));?>" rel="lightbox[sims]" ><img src="<?php echo get_page_url("food_image",array("fid"=>$img["image_id"], "size"=>"sqaure_mid"));?>" style="margin:2px 0 0 2px;" ></a>
												
							<?php 
							
							}
							
							?>
							</div>
							
								<div class="food_item" style="padding-bottom:8px; border-bottom:1px solid #ccc; margin-top:8px;">
										<h1 class="food_name" style="font-size:24px;"><a href="<?php echo get_page_url("food",array("eid"=>$food["food_items_id"]));?>"><?php echo $food['dish_name']; ?></a><br><font class="text_orange">@ <a href="<?php echo get_page_url("restaurant",array("eid"=>$food["rest_id"]));?>" style="color:inherit;"><?php echo $rest['res_name']; ?></a></font></h1>
								</div>
								<div style="margin:5px 0 0 0">
								<?php
								if(is_logged_in())
								{
								?>
								<div style="float:right; margin-top:5px;"><a id="prevc1" href="<?php echo get_page_url("add_pic",array("fid"=>$food["food_items_id"])); ?>">Add Pictures</a></div>
								
								<?php } else {?>
								<div style="float:right; margin-top:5px;"><a class="login_popup" id="prevc1"  href="javascript:void(0);">Add Pictures</a></div>
								<?php 
								}
								$str_like = render_like_pair($food['food_items_id']);
								echo $str_like;
								echo '<div style="width:250px; float:left;margin-top:5px;">
								<div class="likes" id="like_'.$food['food_items_id'].'">
								<div style="width:';
									
									$data1 = floor(1+110*$food['likes']/($food['likes']+$food['dislikes']+1));
									echo $data1;
												
												echo 'px; background:#3db54a; height:10px; float:left; "></div><br><br>'.$food['likes'].' Likes<input type="hidden" id="initial_like_'.$food['food_items_id'].'" value="'.$food["likes"].'">
												</div>
												<div class="dislikes" id="dislike_'.$food['food_items_id'].'" style="margin-left:5px;width:80px;">
													<div style="width:';
													$data2 = floor(1+110*$food['dislikes']/($food['likes']+$food['dislikes']+1));
													
													
													echo $data2.'px; background:#ed2224; height:10px;float:left; "></div><br><br>'.$food['dislikes'].' Dislikes <input type="hidden" id="initial_dislike_'.$food['food_items_id'].'" value="'.$food["dislikes"].'">
												</div>
											</div>
											<div class="clearfix"></div>';
											
							?>
							</div>
							<?php
							if($food["description"])
							{?>
							<div style="margin-top:8px;border-top: 1px solid #ccc; padding-top:8px;">
							<p class="des_title">Description:</p>
							<p class="brown"><?php echo $food["description"]?></p>
							
							</div>
							
							<?php
							}?>
							<div style="margin-top:8px;border-top: 1px solid #ccc; padding-top:8px;">
							<p class="des_title" style="float:left"><span id="comment_count"><?php
							$result= mysql_query("select comment_id from comment where food_id='$_GET[eid]'");
							echo mysql_num_rows($result)."</span> Comments";
							
							?></p>
							<p class="des_title" style="float:right">
								<a id="prevc1" href="javascript:void(0)" onClick="all_comment(<?php echo logged_userid().','.$food_id?>);">View all</a>
								<a id="prevc2" href="javascript:void(0)" onClick="less_comment(<?php echo logged_userid().','.$food_id?>);" style="display:none;">View less</a>
								<?php if(is_logged_in())
{ ?>
<a id="newc" href="javascript:void(0)" onClick="write_comment();">Write Comment</a>
<?php }
else
{
?>
<a id="newc" class="login_popup" href="javascript:void(0)" onClick="write_comment();">Write Comment</a>
<?php } ?>
								
								
							</p>
							
							<div class="clearfix"></div>
							<!-------- COMMENT --->
							<?php if(is_logged_in())
{ ?>
							<div id="newtext" style="display:none;">
		<form>					
  <div id="toolbar" style="display: none;">
    <a data-wysihtml5-command="bold" title="CTRL+B"><img src="images/font_bold_icon&16.png"></a>
    <a data-wysihtml5-command="italic" title="CTRL+I"><img src="images/font_italic_icon&16.png"></a>
    <a data-wysihtml5-command="createLink" title="Insert Link"><img src="images/link_icon&16.png"></a>
    
    <div data-wysihtml5-dialog="createLink" style="display: none; margin-bottom:10px;">
      <label>
        <b>Link:</b>
        <input data-wysihtml5-dialog-field="href" value="http://" style="padding:3px 5px; border:1px solid #ccc">
      </label>
      <a data-wysihtml5-dialog-action="save" id="prevc1"	>Insert</a>&nbsp;<a data-wysihtml5-dialog-action="cancel" id="prevc1">Cancel</a>
    </div>
    
  </div>
  <div id="comment_textarea_div">
  <textarea id="comment_textarea" placeholder="Enter Comment ..."></textarea></div>
  <br><input type="button" value="Comment" id="upload_comment" onClick="put_comment(<?php echo logged_userid().','.$food_id?>);"/>
</form>
		</div>					
				<!---------comments end -------->
<?php } ?>		

<div id="all_comments">
<?php
$offset=5.5*60*60;
$result= mysql_query("select comment.user_id, comment.comment, comment.date, userinfo.username, userinfo.name, userinfo.unique_id from comment inner join userinfo on comment.user_id = userinfo.user_id where comment.food_id='$food_id' order by comment.comment_id desc limit 5");
			if(mysql_num_rows($result)>0)
			{
				while($comment = mysql_fetch_assoc($result)){
					echo '<div class="comment-item">
					<div class="comment-strip">
						<div style="float:left;"><a href="'.get_page_url("profile",array("uid"=>$comment["unique_id"])).'" style="border:none;"><img src="'.get_page_url("profile_pic",array("user_id"=>$comment["user_id"])).'"></a></div>
						<div style="float:left;margin: 15px 0 0 5px;">by <span style="font-family:Quicksand_bold;">
						<a href="'.get_page_url("profile",array("uid"=>$comment["unique_id"])).'" style="text-decoration:none; color:inherit;">';
						echo ($comment["name"]) ? $comment["name"] : $comment["username"];
						echo '</span></a></div>
						<div style="float:right;margin: 15px 0 0 5px;">'.date("H:i:s d/m/y", $comment["date"] + $offset).' </div>
						<div class="clearfix"></div>
					</div>
					<div class="comment_data">
					'.html_entity_decode($comment['comment']).'
					</div>
					
				</div>';
				}
				}
				
		?>


</div>

    </div>

	
							</div>
							</div>
							<div id="food_result_right" style="float:left; width:570px; margin-left:10px; ">
								<div style="padding-bottom:5px; border-bottom:1px solid #bbb; margin-bottom:5px;">
									<div style="float:left">
										<div style="float:left">
										<?php
										if($food["user_id"]!=0)
										{
										$sql_user="SELECT * from userinfo WHERE user_id='$food[user_id]'";
										$result_user=mysql_query($sql_user);
										$user = mysql_fetch_array($result_user);
										?>
											<a href="<?php echo get_page_url("profile",array("uid"=>$user["unique_id"]));?>" style="border:none;">
												<img src="<?php echo get_page_url("profile_pic",array("unique_id"=>$user["unique_id"]));?>">
											</a>
										</div>
										<div style="float:left; margin-left:10px;">
											<h1 class="food_name" style="font-size:20px;">
												<font style="font-size:24px;">
													<a href="<?php echo get_page_url("profile",array("uid"=>$user["unique_id"]));?>">
														<?php echo $user['name']?$user['name']:$user['username'];  ?>
													</a>
												</font>
												<br><font class="text_orange">Discovered this!</font></h1>
										</div>
										<?php } else {?>
										<a href="javascript:void(0)" style="border:none;">
												<img src="<?php echo BASE_URL_2;?>images/icon_king.png">
											</a>
										</div>
										<div style="float:left; margin-left:10px;">
											<h1 class="food_name" style="font-size:20px;">
												<font style="font-size:24px;">
													<a href="javascript:void(0)">
														foodiescompass
													</a>
												</font>
												<br><font class="text_orange">Discovered this!</font></h1>
										</div>
										<?php }?>
										<div class="clearfix"></div>
									</div>
									<div style="float:right; width:290px;">
									</div>
									<div class="clearfix"></div>
								</div>
								<div style="padding-bottom:5px; border-bottom:1px solid #bbb; margin-bottom:5px;">
									<div style="float:left; width:60%; margin-right:10px;">
										<h1 class="food_name" style="font-size:20px;">Restaurant:</h1>
										
										<?php
										
										?>
										
										<p style="margin:5px 0" class="food_typo"><b><?php echo $rest['res_name']; ?></b><br>
											<?php echo ($rest['res_address']=="")?"":$rest['res_address']."<br/>";?>
											<?php echo ($rest['res_city']=="")?"":$rest['res_city']."<br/>";?>
										</p><p style="margin:5px 0" class="food_typo">
										Phone: <?php echo ($rest['res_phone']!='')? $rest['res_phone'] : 'N/A' ;?><br>
										Website: <?php echo ($rest['res_website']!='')? $rest['res_website'] : 'N/A' ;?><br>
										Email: <?php echo ($rest['res_email']!='')? $rest['res_email'] : 'N/A' ;?></p>
									</div>
									<div class="food_typo" style="float:right; width:35%;">
										<table cellspacing="5" border="0">
										<tr>
											<?php
												if($food['vegetarian']==0)
												{
											?>
											<td><img src="images/icon_vegy.png"></td>
											<td><span class="veg">N/A</span></td>
											<?php
												}
												else if($food['vegetarian']==1)
												{
											?>
											<td><img src="images/nonveg.png"></td>
											<td><span class="non_veg">Non Vegetarian</span></td>
											<?php
												}
												else if($food['vegetarian']==2)
												{
											?>
											<td><img src="images/veg.png"></td>
											<td><span class="non_veg">Vegetarian</span></td>
											<?php
												}
											?>
										</tr>
										</table>
										<div style="margin:10px 0 20px 0">
                                        <?php 
										if($food['cost'] == 0)
										{
										?>
										<span style="display:none">Cost: INR <?php echo $food['cost'];?></span><?php 
										}
										else
										 {?>
                                         <span style="background:#FDECD6; padding:5px 10px;">Cost: INR <?php echo $food['cost'];?></span><?php 
										}?>
										</div>
										
										<a href="<?php echo get_page_url('restaurant',array('eid'=>$food['rest_id']));?>"><img src="images/button_more_at_this_place.png"></a>&nbsp;&nbsp;
										<!---<img src="images/button_show_similar_items.png">--->
									</div>
									<div class="clearfix"></div>
								</div>
								<div id="final_results" ><div id="wait" ></div></div>
							
</div>
							</div>
							
							<div class="clearfix"></div>
						</div>
						
						
					
				</div>
		
		
		
		</div>
		<br>
		<?php require_once(ABS_PATH_TO_HOME.'footer.php'); ?>
	<script>
	
  
	$("#map_toggler").click(function() {
  $("#map_display").toggleClass("fullscreen");
});
	
		var map,places;
		var geocoder;
		var autocomplete, autocomplete_rest;
		var marker;
		var lat,lng,user_place;	
		var countryRestrict = { 'country': 'in' };
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();
		
		function initialize() 
		{
			
			
			autocomplete = new google.maps.places.Autocomplete(	document.getElementById('food_place'), {
				componentRestrictions: countryRestrict
			});
	
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
			place_changed();
			});
	
			
			lat=get_cookie('latitude');
			lng=get_cookie('longitude');
			user_place=get_cookie('user_place');
			
			
			$('#user_lat').val(lat);
			$('#user_lng').val(lng);
			$('#user_place_name').val(user_place);
			//alert('asdsad');		
			$("#final_results").load("map_food.php", {food_id: $('#food_id').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
				
				
			<?php include('include/login_signup.php'); ?>
			
			
		
		}
		function codeLatLng(lat, lng) 
		{

				var latlng = new google.maps.LatLng(lat, lng);
				geocoder = new google.maps.Geocoder();
				geocoder.geocode({'latLng': latlng}, function(results, status) {
				
				if (status == google.maps.GeocoderStatus.OK) {
				console.log(results)
				if (results[1]) {
				var myString = results[0].formatted_address;
				var myArray = myString.split(',');
				$("#user_place_name").val(myArray[0]);
				set_cookie('user_place',myArray[0],7);
				$("#final_results").load("map_food.php", {food_id: $('#food_id').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
				} 
			  } 
			});
			return true;
		}
  

$('#food_search').keypress(function(e){
    if ( e.which == 13 ) return false;
    //or...
    if ( e.which == 13 ) e.preventDefault();
});
		
$('#food_place').keypress(function(e){
    if ( e.which == 13 ) return false;
    //or...
    if ( e.which == 13 ) e.preventDefault();
});
		
function place_changed() {
    var place = autocomplete.getPlace();
  $('#food_lat').val(place.geometry.location.lat());
  $('#food_lng').val(place.geometry.location.lng());
  $('#food_place_name').val(place.name);
  
  //alert(place.geometry.location.lat());
  for (var i=0; i<place.address_components.length; i++) {
            for (var b=0;b<place.address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
			
                if (place.address_components[i].types[b] == "administrative_area_level_2") {
                    //this is the object you are looking for
                    city= place.address_components[i];
                    break;
                }
				
				if (place.address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    state= place.address_components[i];
                    break;
                }
				
				if (place.address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    locality= place.address_components[i];
                    break;
                }
				
            }
        }
		//$('#food_place').val(place.name);
		$('#food_place').val(place.name + ', ' + locality.long_name);
		$('#food_locality').val(locality.long_name);
		$('#food_city').val(city.long_name);
  }

		
		
		
  
  
  function set_icon(markers, pane, lat_res ,lng_res)
{
	//alert($('.map_food_item').length);
	
	switch($('.map_food_item').length)
	{
		case 2:
				marker1.setIcon('');
				marker2.setIcon('');
				break;
		case 3:
				marker1.setIcon('');
				marker2.setIcon('');
				marker3.setIcon('');
				break;
		case 4:
				marker1.setIcon('');
				marker2.setIcon('');
				marker3.setIcon('');
				marker4.setIcon('');
				break;
		case 5:
				marker1.setIcon('');
				marker2.setIcon('');
				marker3.setIcon('');
				marker4.setIcon('');
				marker5.setIcon('');
				break;
	}
	
	switch($('.map_food_item').length)
	{
		case 2:
				marker1.setZIndex(5);
				marker2.setZIndex(5);
				break;
		case 3:
				marker1.setZIndex(5);
				marker2.setZIndex(5);
				marker3.setZIndex(5);
				break;
		case 4:
				marker1.setZIndex(5);
				marker2.setZIndex(5);
				marker3.setZIndex(5);
				marker4.setZIndex(5);
				break;
		case 5:
				marker1.setZIndex(5);
				marker2.setZIndex(5);
				marker3.setZIndex(5);
				marker4.setZIndex(5);
				marker5.setZIndex(5);
				break;
	}
	

	markers.setIcon('images/map_icon.png');
	markers.setZIndex(10);

	$('.map_food_item').css("background-color","#fff");
	$('#food_item' + pane +'').css("background-color","#fee4bd");
	directionsDisplay.setMap(null);
		directionsDisplay.suppressMarkers = true;
        var start = new google.maps.LatLng($("#user_lat").val(), $("#user_lng").val());
        var end = new google.maps.LatLng(lat_res,lng_res);
        var request = {
          origin: start,
          destination: end,
          travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
          }
        });
		directionsDisplay.setMap(map);
}

  function remove_icon(markers)
{
	 
}
  
	function change_page(page)
	{
		$('#wait').html('loading');
		$("#final_results").load("demo.php", {food_array: $('#food_array').val(), food_name: $('#food_search').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val(), food_page:page });
	
	}
	
	function FullScreenControl(map) {
	var controlDiv = document.createElement('div');
	controlDiv.index = 1;
	controlDiv.style.padding = '5px';

	// Set CSS for the control border.
	var controlUI = document.createElement('div');
	controlUI.style.backgroundColor = 'white';
	controlUI.style.borderStyle = 'solid';
	controlUI.style.borderWidth = '2px';
	controlUI.style.cursor = 'pointer';
	controlUI.style.textAlign = 'center';
	controlDiv.appendChild(controlUI);

	// Set CSS for the control interior.
	var controlText = document.createElement('div');
	controlText.style.fontFamily = 'Arial,sans-serif';
	controlText.style.fontSize = '12px';
	controlText.style.paddingLeft = '4px';
	controlText.style.paddingRight = '4px';
	controlText.innerHTML = '<strong>Full Screen</strong>';
	controlUI.appendChild(controlText);

	var fullScreen = false;
	var mapDiv = map.getDiv();
	var divStyle = mapDiv.style;
	if (mapDiv.runtimeStyle)
		divStyle = mapDiv.runtimeStyle;
	var originalPos = divStyle.position;
	var originalWidth = divStyle.width;
	var originalHeight = divStyle.height;
	var originalTop = divStyle.top;
	var originalLeft = divStyle.left;
	var originalZIndex = divStyle.zIndex;
	
	// Setup the click event listener
	google.maps.event.addDomListener(controlUI, 'click', function() {
		var center = map.getCenter();
		if (!fullScreen) {
			divStyle.position = "fixed";
			divStyle.width = "100%";
			divStyle.height = "100%";
			divStyle.top = "0";
			divStyle.left = "0";
			divStyle.zIndex = "100";
			controlText.innerHTML = '<strong>Exit full screen</strong>';
		}
		else {
			if (originalPos == "")
				divStyle.position = "relative";
			else
				divStyle.position = originalPos;
			divStyle.width = originalWidth;
			divStyle.height = originalHeight;
			divStyle.top = originalTop;
			divStyle.left = originalLeft;
			divStyle.zIndex = originalZIndex;
			controlText.innerHTML = '<strong>Full Screen</strong>';
		}
		fullScreen = !fullScreen;
		google.maps.event.trigger(map, 'resize');
		map.setCenter(center);
	});
	
	return controlDiv;
}
function put_rest_location()
{
	var r=confirm("Do you want to save this location?");
	if (r==true)
	  {
	 put_rest_location_confirm();
	  }
	else
	  {
	 return false;
	  }
}

function put_rest_location_confirm()
{
//alert('asdasd');
<?php if(is_logged_in()) {?>
var eid = <?php echo logged_userid();?>;
<?php } else {?>
var eid=0;
<?php }?>
var rid = <?php echo $rest['rsID'];?>;
var rest_lat = $('#rest_user_lat').val();
var rest_lng = $('#rest_user_lng').val();
$.ajax({
				url:'ajax/location_save.php',
				data:{'eid':eid, 'rid':rid, 'lat':rest_lat ,'lng':rest_lng},
				success:function(data){
				
						location.reload();
					
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
				//alert(data);
					if(data=='<?php echo SUCCESS;?>' || data == '<?php echo SUCCESS2;?>')
					{
						// Like success
						// Change icons
						
						$(context).html("<img src='images/icon_thumb_up_shadow.png' />");
						$('a[name=dislike_'+eid+']').html("<img src='images/icon_thumb_down.png' />");
						
						// Update counts
						initial_like = parseInt($('#initial_like_'+ eid).val(),10);
						initial_dislike = parseInt($('#initial_dislike_'+ eid).val(),10);
						new_likes = initial_like+1;
						if(data=='<?php echo SUCCESS;?>') new_dislikes = initial_dislike;
						else new_dislikes = initial_dislike -1;
						like_width= Math.floor(1+140*new_likes/(new_likes+new_dislikes+1));
						dislike_width = Math.floor(1+140*new_dislikes/(new_likes+new_dislikes+1));
						$('#like_'+eid).html('<div style="width:'+like_width+'px; background:#3db54a; height:10px; float:left;"></div><br><br>'+ new_likes +' Likes<input type="hidden" id="initial_like_'+eid+'" value="'+new_likes+'">');
						$('#dislike_'+eid).html('<div style="width:'+dislike_width+'px; background:#ed2224; height:10px; float:left;"></div><br><br>'+ new_dislikes +' Disikes<input type="hidden" id="initial_dislike_'+eid+'" value="'+new_dislikes+'">');
						
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
				//alert(data);
					if(data=='<?php echo SUCCESS;?>' || data == '<?php echo SUCCESS2;?>')
					{
						// Dislike succcess
						// Change icons
						$(context).html("<img src='images/icon_thumb_down_shadow.png' />");
						$('a[name=like_'+eid+']').html("<img src='images/icon_thumb_up.png' />");
						
						// Update counts
					// Update counts
						// Update counts
						initial_like = parseInt($('#initial_like_'+ eid).val(),10);
						initial_dislike = parseInt($('#initial_dislike_'+ eid).val(),10);
						new_dislikes = initial_dislike+1;
						if(data=='<?php echo SUCCESS;?>') new_likes = initial_like;
						else new_likes = initial_like -1;
						like_width= Math.floor(1+110*new_likes/(new_likes+new_dislikes+1));
						dislike_width = Math.floor(1+110*new_dislikes/(new_likes+new_dislikes+1));
						$('#like_'+eid).html('<div style="width:'+like_width+'px; background:#3db54a; height:10px; float:left;"></div><br><br>'+ new_likes +' Likes<input type="hidden" id="initial_like_'+eid+'" value="'+new_likes+'">');
						$('#dislike_'+eid).html('<div style="width:'+dislike_width+'px; background:#ed2224; height:10px; float:left;"></div><br><br>'+ new_dislikes +' Dislikes<input type="hidden" id="initial_dislike_'+eid+'" value="'+new_dislikes+'">');
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
	
</script>
<script src="js/advanced.js"></script>
<script src="js/wysihtml5-0.4.0pre.min.js"></script>
<script>
  var editor = new wysihtml5.Editor("comment_textarea", {
    toolbar:        "toolbar",
    stylesheets:    "css/stylesheet.css",
    parserRules:    wysihtml5ParserRules
  });
 
function write_comment()
{
	$("#newtext").animate({height: "toggle", opacity: "toggle"}, 1000);
}
 		
 function put_comment(uid,fid)
{
	var text_c = $("#comment_textarea").val();
	 write_comment();
	if(text_c == '')
	{
	 alert('empty comment');
	}
	else
	{
		
	$.post("ajax/comments.php", {  uid: uid, fid: fid, new_comment: text_c }, function(data) {
		$('#all_comments').prepend(data);
			var intital_comment = parseInt($('#comment_count').html());
		var final_comment = intital_comment+1;
		$('#comment_count').html(final_comment);
		
		$("#comment_textarea_div").html('<textarea id="comment_textarea" placeholder="Enter Comment ..."></textarea>');
		
	});
	
	}
	
}
	
 function all_comment(uid,fid)
{
	$("#prevc1").hide();
	
	$.post("ajax/all_comments.php", {  uid: uid, fid: fid }, function(data) {
		$('#all_comments').html(data);
		
		$("#prevc2").show();
	});
	
	
}	
 function less_comment(uid,fid)
{
	
	$("#prevc2").hide();
	$.post("ajax/all_comments.php", {  uid: uid, fid: fid, limit:'5' }, function(data) {
		$('#all_comments').html(data);
		$("#prevc1").show();
	});
	
	
}


</script>
	</body>
	<?php include ABS_PATH_TO_HOME.'include/tiptip.php'; ?>
</html>	