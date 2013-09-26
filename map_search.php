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
								<td valign="bottom" align="right" style="position:relative;">
								<font class="search_results2 brown" style="font-size:15px;"><a href="about" style="color:inherit; text-decoration:none" >Are we missing something?</a></font>
								</td></tr>
							</table>
							
							<div class="clearfix"></div>
						</div>
						
						<div style="margin:0 10px 0 10px; padding-bottom:5px; margin-top:5px">
						
						<div id="ask_location" style="display:none;">Your location is not known. Please allow your location to be shared OR select location here<br>
						
						<input type="text" id="user_ask_location" name="user_ask_location" value="You @ Location/Place" title="You @ Location/Place" class="input1 searchfood" style="display:inline; width:220px;" placeholder="Enter a location" autocomplete="off">
						</div>
						
						<div id="final_results" >
							<div id="wait" >
							
							</div>
						</div>
						
							
							
							<?php


?>

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
		var autocomplete, autocomplete_ask_location;
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
	
			google.maps.event.addListener(autocomplete, 'place_changed', function() 			{
			place_changed();
			});
			
			autocomplete_ask_location = new google.maps.places.Autocomplete(document.getElementById('user_ask_location'), {
				componentRestrictions: countryRestrict
			});
	
			google.maps.event.addListener(autocomplete_ask_location, 'place_changed', function() 			{
			place_changed_ask_location();
			});
	
			
			lat=get_cookie('latitude');
			lng=get_cookie('longitude');
			user_place=get_cookie('user_place');
			
			if(!lat || !lng)
			{
				$("#ask_location").show();
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
					$("#final_results").load("demo.php", {food_name: $('#food_search').val(), food_place:$('#food_place').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
				}
				
			}
			
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
				$("#ask_location").hide();
				$("#final_results").load("demo.php", {food_name: $('#food_search').val(), food_place:$('#food_place').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
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
		//location.reload();
  }
  
  
  function place_changed_ask_location() {

    var place = autocomplete_ask_location.getPlace();
	
	set_cookie('latitude',place.geometry.location.lat(),7);
	set_cookie('longitude',place.geometry.location.lng(),7);
	$('#user_lat').val(place.geometry.location.lat());
	$('#user_lng').val(place.geometry.location.lng());
	
	set_cookie('user_place',place.name);
	$('#user_place_name').val(place.name);
  
  $("#final_results").load("demo.php", {food_name: $('#food_search').val(), food_place:$('#food_place').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val() });
	$('#ask_location').hide();
 
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
	
	function change_page(page, value, updown, pos)
{
$('#wait').html('loading');
$('.sorting').removeClass('selected');

$("#final_results").load("demo.php", {sortby:pos, food_array_main: $('#food_array_main').val(), food_array: $('#food_array').val(),food_array_like: $('#food_array_like').val(), food_array_main_like: $('#food_array_main_like').val(), food_name: $('#food_search').val(), food_place:$('#food_place').val(), food_lat:$('#food_lat').val(), food_lng:$('#food_lng').val(), user_place:$('#user_place_name').val(), food_locality:$('#food_locality').val(), user_lat:$('#user_lat').val(),user_lng:$('#user_lng').val(), food_page:page, value:value, updown:updown });
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
</html>