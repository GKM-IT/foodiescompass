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
		$sql="SELECT * from restaurant WHERE rsID='$_GET[eid]'";
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
	$rest=mysql_fetch_array($result);
	$title= $rest["res_name"];
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
		<script type="text/javascript" src="js/jquery.selectbox-0.2.min.js"></script>
		<link href="css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
				<link href="css/lightbox.css" rel="stylesheet" />
		<script src="js/lightbox.js"></script>
	</head>
		
	<body class="home" onload="initialize()">
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
						<input type="hidden" name="rest_id" id="rest_id" value="<?php echo $rest["rsID"]?>"/>
						
							<div id="food_result_left" style="float:left; width:400px;">
							<div style="min-height:150px;" style="border-bottom:1px solid #ccc;">
							<?php if($rest["res_image"])
							{ ?>
							<a href="<?php echo get_page_url("rest_pic",array("rid"=>$rest["rsID"], "size"=>"full")); ?>" rel="lightbox[foodiescompass]" style="text-decoration: none;"><img src="<?php echo get_page_url("rest_pic",array("rid"=>$rest["rsID"], "size"=>"normal")); ?>" style="margin-left:5px;"/>
							</a><br>
							<div id="more_images" style="margin: 5px 0 0 5px;">
							<?php
							$more_img = mysql_query("select image_id from images where rest_id='$rest[rsID]' and main_pic='0'");
							while($img = mysql_fetch_array($more_img))
							{
							
							?>
							<a href="<?php echo get_page_url("food_image",array("fid"=>$img["image_id"], "size"=>"full"));?>" rel="lightbox[foodiescompass]" ><img src="<?php echo get_page_url("food_image",array("fid"=>$img["image_id"], "size"=>"sqaure_mid"));?>" style="margin:2px 0 0 2px;" ></a>
												
							<?php 
							
							}
							
							?>
							</div>
							<?php }
							if(!$rest["res_image"])
							{
							?>
							<div class="food_item" style="padding-bottom:8px;  margin-top:8px;">
										<h1 class="food_name" style="font-size:24px;"><?php echo $rest['res_name']; ?></h1>
							</div>
							<?php } ?>
							<div>
							<?php
								if(is_logged_in())
								{
								?>
								<div style="float:right; margin-top:5px;"><a id="prevc1" href="<?php echo get_page_url("add_res_pic",array("rid"=>$rest["rsID"])); ?>">Add Pictures</a></div>
								
								<?php } else {?>
								<div style="float:right; margin-top:5px;"><a class="login_popup" id="prevc1"  href="javascript:void(0);">Add Pictures</a></div>
								<div class="clearfix"></div>
								<?php 
								} ?>
							
							</div>
							</div>
							<div id="final_results" ><div id="wait" ></div></div>
							</div>
							<div id="food_result_right" style="float:left; width:570px; margin-left:10px; ">
								
								<div style="padding-bottom:5px; border-bottom:1px solid #bbb; margin-bottom:5px;">
							<?php	if($rest["res_image"])
							{
							?>
							<div class="food_item" style="padding-bottom:8px;  margin-top:8px;">
										<h1 class="food_name" style="font-size:24px;"><?php echo $rest['res_name']; ?></h1>
							</div>
							<?php } ?>
							
									<div style="float:left; width:50%;">
										<h1 class="food_name" style="font-size:20px;">Address:</h1>
										
																			
										<p style="margin:5px 0" class="food_typo"><b><?php echo $rest['res_name']; ?></b><br>
											<?php echo ($rest['res_address']=="")?"":$rest['res_address']."<br/>";?>
											<?php echo ($rest['res_city']=="")?"":$rest['res_city']."<br/>";?>
										</p><p style="margin:5px 0" class="food_typo">
										Phone: <?php echo ($rest['res_phone']!='')? $rest['res_phone'] : 'N/A' ;?><br>
										Website: <?php echo ($rest['res_website']!='')? $rest['res_website'] : 'N/A' ;?><br>
										Email: <?php echo ($rest['res_email']!='')? $rest['res_email'] : 'N/A' ;?></p>
									</div>
									<div class="food_typo" style="float:right; width:50%;">
										<table cellspacing="5" border="0">
										
										</table>
										<div style="margin:10px 0">
										<span style="background:#FDECD6; padding:5px 10px;">Price Range: INR <?php echo $rest['cost'];?></span>
										</div>
										
									</div>
									<div class="clearfix"></div>
								</div>
								<div id="final_results_map" ><div id="wait" ></div></div>
							
</div>
							</div>
							
							<div class="clearfix"></div>
						</div>
						
						
					
				</div>
		
		
		
		</div>
		<br>
		<?php require_once("footer.php"); ?>
	<script>
	$("#final_results").ajaxStart(function(){
    $("#wait").html("loading....");
	});
	
	$("#final_results").ajaxComplete(function(){
    $("#wait").html("");
	});
  
  
	$("#map_toggler").click(function() {
	  $("#map_display").toggleClass("fullscreen");
	});

		var map,places;
		var geocoder;
		var autocomplete;
		var marker;
		var lat,lng,user_place;	
		var countryRestrict = { 'country': 'in' };
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();
		
		function initialize() 
		{	
			$("#final_results").load("rest_dish.php", {rest_id: $('#rest_id').val() });
			
			autocomplete = new google.maps.places.Autocomplete(	document.getElementById('food_place'), {
				componentRestrictions: countryRestrict
			});
	
			google.maps.event.addListener(autocomplete, 'place_changed', function() 			{
			place_changed();
			});
	
			
			lat=get_cookie('latitude');
			lng=get_cookie('longitude');
			user_place=get_cookie('user_place');
			$('#user_lat').val(lat);
			$('#user_lng').val(lng);
			$('#user_place_name').val(user_place);
			/*if(!lat || !lng)
			{
				
				//Get user location
				navigator.geolocation.getCurrentPosition(function(position){
				lat=position.coords.latitude;
				lng=position.coords.longitude;
				
				// Set cookies
				set_cookie('latitude',lat,7);
				set_cookie('longitude',lng,7);
				$('#user_lat').val(lat);
				$('#user_lng').val(lng);
				codeLatLng(lat, lng);
				});
			}
			else
			{
				if(!user_place)
				{
					$('#user_lat').val(lat);
					$('#user_lng').val(lng);
					codeLatLng(lat, lng);
				}
				else
				{
					$('#user_lat').val(lat);
					$('#user_lng').val(lng);
					$('#user_place_name').val(user_place);
					//alert('asdsad');		
					$("#final_results_map").load("map_rest.php", {food_id: $('#rest_id').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
				}
				
			}*/
			
			$("#final_results_map").load("map_rest.php", {food_id: $('#rest_id').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
			
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
				$("#final_results").load("demo.php", {food_name: $('#food_search').val(), food_place:$('#food_place_name').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
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

		
		


  function remove_icon(markers)
{
	 
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
						initial_like = parseInt($('#initial_like_'+ eid).val(),10);
						initial_dislike = parseInt($('#initial_dislike_'+ eid).val(),10);
						new_likes = initial_like+1;
						if(data=='<?php echo SUCCESS;?>') new_dislikes = initial_dislike;
						else new_dislikes = initial_dislike -1;
						like_width= Math.floor(1+70*new_likes/(new_likes+new_dislikes+1));
						dislike_width = Math.floor(1+70*new_dislikes/(new_likes+new_dislikes+1));
						$('#like_'+eid).html('<div style="width:'+like_width+'px; background:#3db54a; height:6px; float:left;"></div><br>'+ new_likes +' Likes<input type="hidden" id="initial_like_'+eid+'" value="'+new_likes+'">');
						$('#dislike_'+eid).html('<div style="width:'+dislike_width+'px; background:#ed2224; height:6px; float:left;"></div><br>'+ new_dislikes +' Likes<input type="hidden" id="initial_dislike_'+eid+'" value="'+new_dislikes+'">');
						
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
					// Update counts
						// Update counts
						initial_like = parseInt($('#initial_like_'+ eid).val(),10);
						initial_dislike = parseInt($('#initial_dislike_'+ eid).val(),10);
						new_dislikes = initial_dislike+1;
						if(data=='<?php echo SUCCESS;?>') new_likes = initial_like;
						else new_likes = initial_like -1;
						like_width= Math.floor(1+70*new_likes/(new_likes+new_dislikes+1));
						dislike_width = Math.floor(1+70*new_dislikes/(new_likes+new_dislikes+1));
						$('#like_'+eid).html('<div style="width:'+like_width+'px; background:#3db54a; height:6px; float:left;"></div><br>'+ new_likes +' Likes<input type="hidden" id="initial_like_'+eid+'" value="'+new_likes+'">');
						$('#dislike_'+eid).html('<div style="width:'+dislike_width+'px; background:#ed2224; height:6px; float:left;"></div><br>'+ new_dislikes +' Dislikes<input type="hidden" id="initial_dislike_'+eid+'" value="'+new_dislikes+'">');
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
	function put_rest_location()
{
//alert('asdasd');
var eid = <?php echo (logged_userid())?logged_userid():'-1';?>;

var rid = <?php echo $rest['rsID'];?>;
var rest_lat = $('#rest_user_lat').val();
var rest_lng = $('#rest_user_lng').val();
$.ajax({
				url:'ajax/location_save.php',
				data:{'eid':eid, 'rid':rid, 'lat':rest_lat ,'lng':rest_lng},
				success:function(data){
				
						//alert(data);
					
				}
			});


}
	function change_page(page)
	{
		$('#wait').html('loading');
		$("#final_results").load("rest_dish.php", {rest_id:$('#rest_id').val(), food_page:page });
	
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
	
</script>
	</body>
	
	</script>
</html>