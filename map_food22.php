<?php

	//sleep(5);
	require_once("include/config.inc.php");
	require_once("include/lib.inc.php");
	require_once("include/session.inc.php");
	require_once("include/connection.inc.php");
	require_once("include/flags.inc.php");
	
function distance($lat1, $lon1, $lat2, $lon2, $unit) { 

  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}


$food = $_POST["food_id"];
$place = $_POST["food_place"];
$locality= $_POST["food_locality"];
$user_lat =$_POST["user_lat"];
$user_lng =$_POST["user_lng"];
$user_place = $_POST["user_place"];
//echo 'user@'.$user_lat.' '.$user_lng;


if(!$user_lat) { $user_lat = $_POST["food_lat"];$user_lng =$_POST["food_lng"];$user_place = $_POST["food_place"];}
	
		$query="SELECT food_items.food_items_id, food_items.dish_name, restaurant.res_lat, restaurant.res_lng, restaurant.res_city FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where food_items_id ='$food' ";
	//echo $query;

	$results = mysql_query($query);
	$num_results=mysql_num_rows($results);
	//echo $num_results;
	$food = mysql_fetch_array($results);
	if($food["res_lat"]) { 
	if($user_lat != 'false')$distance = distance( (double)$food["res_lat"], (double)$food["res_lng"], $user_lat, $user_lng, "k" );
	//echo '<br>rest@'.$food["res_lat"].' '.$food["res_lng"];	


echo '<div id="wait" ></div>';
	//echo 'Total Distance: '.$distance.'<br>';
	echo '
<div id="map_result_right" style="float:left; width:570px;  ">
<div style="background:#faaf42; padding:20px; color:#fff; font-weight:bold; font-size:18px;">
								<div style="width:280px; float:left;">';
								if($user_place=='false' || $user_place='')
								{
								echo 'Please select your location';
								}
								else
								{
								echo 'You @ '.$user_place;
								}
								echo '</div>
									<div style="float:right;">';
									if($user_place=='false' || $user_place='') {
										echo '<input type="text" id="change_user_loc" placeholder ="Select Location" title="User Location" class="input1 searchfood" style="display:inline; width:200px; margin-top: -8px;"/>';
										}
										else
										{
										echo '<input type="text" id="change_user_loc" placeholder ="Change Location" title="User Location" class="input1 searchfood" style="display:inline; width:200px; margin-top: -8px;"/>';							
										}
										
									echo '</div>
									<div class="clearfix"></div>
								</div>			
								<div id="map_display">
								</div>
								<div id="map_display_bottom" style=" ">
								
									<div style="float:right;">
										<img src="images/map_icon_you.png" width="14" height="20"> You&nbsp;&nbsp;&nbsp;&nbsp; <img src="images/map_icon.png" width="14" height="20"> Your Food
										
									</div>
									<div class="clearfix"></div>
								</div>
							
</div>

<script>
var autocomplete_user;
var icon1 = "images/map_icon.png";
var icon2 = "images/map_icon_you.png";
		var map;
		var marker;
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();
	autocomplete_user = new google.maps.places.Autocomplete(document.getElementById(\'change_user_loc\'), {
      
			componentRestrictions: countryRestrict
    });
	
	google.maps.event.addListener(autocomplete_user, \'place_changed\', function() {
			place_changed_user();
			});
	
function place_changed_user() {
    var place = autocomplete_user.getPlace();
  set_cookie(\'latitude\',place.geometry.location.lat(),7);
  set_cookie(\'longitude\',place.geometry.location.lng(),7);
  set_cookie(\'user_place\',place.name,7);
  
location.reload();

  }

		
	
			
			var mapOptions = {';
			if($user_lat == 'false' || $user_lat='') echo 'center: new google.maps.LatLng('.$food["res_lat"].','.$food["res_lng"].'),';
			else echo 'center: new google.maps.LatLng('.$user_lat.','.$user_lng.'),';
			  echo 'zoom: 14,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_display"),mapOptions);
			
var userlatlng = new google.maps.LatLng('.$user_lat.','.$user_lng.');


			// Set marker
			 marker = new google.maps.Marker({
				map: map,
				animation: google.maps.Animation.DROP,
				position: new google.maps.LatLng('.$user_lat.','.$user_lng.'),
				draggable:false,
				title:\'You are here\',
				 icon: icon2
				 });';
$count =1;

			echo 'var marker'.$count.' = new google.maps.Marker({
		  position: new google.maps.LatLng('.$food["res_lat"].','.$food["res_lng"].'), 
		  map: map,
		  title: \'Food\',
		  clickable: true,
		  animation: google.maps.Animation.DROP,
		  draggable:false,
		  icon: icon1
  });';

 echo ' var reslatlng = new google.maps.LatLng('.$food["res_lat"].','.$food["res_lng"].');';
 
 echo 'directionsDisplay.setMap(null);
		directionsDisplay.suppressMarkers = true;
        var start = new google.maps.LatLng('.$user_lat.','.$user_lng.');
        var end = new google.maps.LatLng('.$food["res_lat"].','.$food["res_lng"].');
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
		directionsDisplay.setMap(map);';
  		
		echo'
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
	  new FullScreenControl(map));
	</script>';
	}
	else
	{
	
echo '<div id="wait" ></div>';
echo '<input type="hidden" id="rest_user_lat"> <input type="hidden" id="rest_user_lng"> ';
echo '<p  class="food_name" style="font-size:14px;">The Location for this item is not known. You can help us by locating it using following steps:</p>

<p  style="font-size:12px; line-height:1.5;color: #888;
font-weight: bold;">1. Search for the nearest place/area in the search box.</p>
<p  style="font-size:12px; color: #888;
font-weight: bold;">2. Drag the marker to appropriate location and press <b>Save</b> button</p><Br>';
	//echo 'Total Distance: '.$distance.'<br>';
	echo '
<div id="map_result_right" style="float:left; width:570px;  ">
<div style="background:#faaf42; padding:20px; color:#fff; font-weight:bold; font-size:18px;">
							
									<div style="float:left;">
										<input type="text" id="change_user_loc" placeholder ="Select Food\'s Location" title="User Location" class="input1 searchfood" style="display:inline; width:350px; margin-top: -8px;"/>
										
										
									</div>
									<div style="float:left;">
										<input class="save_button" type="button" onclick="put_rest_location()" value="Save Location" style="">
										
										
									</div>
									<div class="clearfix"></div>
								</div>			
								<div id="map_display">
								</div>
								<div id="map_display_bottom" style=" ">
								
									<div style="float:right;">
										<img src="images/map_icon_you.png" width="14" height="20"> You&nbsp;&nbsp;&nbsp;&nbsp; <img src="images/map_icon.png" width="14" height="20"> Your Food
										
									</div>
									<div class="clearfix"></div>
								</div>
							
</div>

<script>
var autocomplete_user;
var icon1 = "images/map_icon.png";
var icon2 = "images/map_icon_you.png";
		var map;
		var marker, marker2;
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();
	autocomplete_user = new google.maps.places.Autocomplete(document.getElementById(\'change_user_loc\'), {
      
			componentRestrictions: countryRestrict
    });
	
	google.maps.event.addListener(autocomplete_user, \'place_changed\', function() {
			place_changed_user();
			});
	
	

function place_changed_user() {

    var place = autocomplete_user.getPlace();
  var foodlatlng = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
map.setCenter(foodlatlng);
map.setZoom(14);
marker2.setPosition(foodlatlng);
$("#rest_user_lat").val(place.geometry.location.lat());
$("#rest_user_lng").val(place.geometry.location.lng());
  }

		
	
			
			var mapOptions = {
			  center: new google.maps.LatLng('.$user_lat.','.$user_lng.'),
			  zoom: 8,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_display"),mapOptions);
			
var userlatlng = new google.maps.LatLng('.$user_lat.','.$user_lng.');


			// Set marker
			 marker = new google.maps.Marker({
				map: map,
				animation: google.maps.Animation.DROP,
				position: new google.maps.LatLng('.$user_lat.','.$user_lng.'),
				draggable:false,
				title:\'You are here\',
				 icon: icon2
				 });
				 
				  marker2 = new google.maps.Marker({
				map: map,
				animation: google.maps.Animation.DROP,
				position: new google.maps.LatLng(0,0),
				draggable:true,
				title:\'Food Location\',
				 icon: icon1
				 });
				 ';

  		
		echo'
		google.maps.event.addListener(marker2, \'dragend\', function(){
				var point = marker2.getPosition();
				 //var foodlatlng_drag = new google.maps.LatLng(point.lat(),point.lng());
				//map.setCenter(foodlatlng_drag);
				$("#rest_user_lat").val(point.lat());
				$("#rest_user_lng").val(point.lng());
		});
		
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
	  new FullScreenControl(map));
	</script>';
	
	
	}
	
	?>