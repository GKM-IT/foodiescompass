<?php
	require_once("./include/config.inc.php");
	require_once("./include/lib.inc.php");
	require_once("./include/session.inc.php");
	require_once("./include/connection.inc.php");

	$title = 'Foodies';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title><?php echo $title;?> FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="shorcut icon" href="images/favicon.gif" />
		<link href="css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script src="js/crossfade_images.js" type="text/javascript"></script>
		<script src="js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.selectbox-0.2.min.js"></script>
		<link href="css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="js/jquery.tipTip.minified.js"></script>
		<link href="css/tipTip.css" type="text/css" rel="stylesheet" />
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
						<div id="follower_search">
						<form action="<?php echo get_page_url('followers');?>" method="POST">
							<div id="page_search_form_div" style="width:900px;">
							<font class="search_results1" style="color:#fff;font-size:25px;">Followers</font>
								<input type="text" id="follower" name="follower" value="<?php echo $_POST["follower"]? $_POST["follower"] : "Search Follower"?>" title="Search Follower" class="input1 searchfood" style="display:inline; width:450px; float:right"/>
							</div>
						
						</form>					
						</div>
						<div style="border-bottom: 1px solid #F9AE44; margin:0 5px 0 5px;"></div>
						
						<div style="margin:0 10px 0 10px; padding-bottom:5px; margin-top:15px;">
							
							<div id="final_results" ><div id="wait_follow" style="text-align: center;
margin: 50px;" align="center"></div></div>
								
							
							
							<div class="clearfix"></div>
						
						</div>
						
						
					
				</div>
		
		
		
		</div>
		<br>
		<?php require_once("footer.php"); ?>
			

	<script>
	$("#final_results").ajaxStart(function(){
    $("#wait_follow").html('<?php echo LOADER;?>');
	});
	
	$("#final_results").ajaxComplete(function(){
    $("#wait_follow").html("");
	});
  
  
		var lat,lng,user_place;	
		var autocomplete, autocomplete_rest;
		var countryRestrict = { 'country': 'in' };
		function initialize() 
		{
			$("#final_results").load("followers_load.php", {food_page:'1', query:$('#follower').val() });
			autocomplete = new google.maps.places.Autocomplete(	document.getElementById('food_place'), {
				componentRestrictions: countryRestrict
			});
			google.maps.event.addListener(autocomplete, 'place_changed', function() 			{
			place_changed();
			});
		}
		
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
  
		function change_page(page)
		{
			$('#wait').html('loading');
			$("#final_results").load("followers_load.php", {food_array: $('#food_array').val(),food_page:page });
		
		}
		
  function follow(id,context)
{
	if(id==-1)
	{
		alert('Login to follow this user');
	}
	else
	{
		$(context).removeClass().addClass('follow_inactive');	// Make button inactive
		$(context).attr('onclick','');	// Disable on-click event
		$.ajax({
			url: "ajax/ajax_follow.php",
			data: {'id':id},
			success: function(data){
				if(data=='<?php echo USER_FOLLOWED;?>')
				{
					$(context).removeClass().addClass('unfollow');
					//alert('User Followed');
				}
				else if(data=='<?php echo USER_UNFOLLOWED;?>')
				{
					$(context).removeClass().addClass('follow');
					//alert('User Unfollowed');
				}
				else
				{
					// Some error has occurred
					var msg;
					switch(data)
					{
						case '<?php echo DB_ERROR;?>': msg='Database error.'; break;
						case '<?php echo SEARCH_ERROR;?>': msg='User does not exist.'; break;
						case '<?php echo DB_UPDATE_ERROR;?>': msg='Database update error.'; break;
						case '<?php echo SELF_FOLLOW_ERROR;?>': msg='You cannot follow yourself.'; break;
						case '<?php echo INSUFFICIENT_PARAMS_ERROR;?>': msg='Insufficient parameters.'; break;
						case '<?php echo NO_SESSION_EXISTS_ERROR;?>': msg='No user logged in.'; break;
					}
					alert(msg);
				}
			}
		});
	}
}	// End of follow

</script>

	</body>
	
	
</html>