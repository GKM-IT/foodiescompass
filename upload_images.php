<?php
	require_once('include/config.inc.php');
	require_once('include/lib.inc.php');
	require_once('include/flags.inc.php');
	require_once('include/session.inc.php');
	require_once('include/connection.inc.php');
	require_once('include/functions.inc.php');
	define('IMG_RESIZE_WIDTH',375);
	define('IMG_RESIZE_HEIGHT',245);
	if(is_logged_in())
	{
	
	$title='';
	$title = 'Upload Images';
	$upload_error=false;
	//echo logged_userid();
	if(isset($_POST['pic_uploaded']))
	{
		if($_POST['food_name']!="" && $_POST['food_place']!="" && $_POST['food_veg']!="" && $_POST['food_desc']!="" && $_POST['pic_uploaded'])
		{
			$food_name=mysql_real_escape_string($_POST['food_name']);
			$food_place=mysql_real_escape_string($_POST['food_place']);
			$rest_id = mysql_real_escape_string($_POST['rest-id']);
			$rest_name = mysql_real_escape_string($_POST['rest-name']);
			$rest_address = mysql_real_escape_string($_POST['rest-address']);
			$rest_lat = mysql_real_escape_string($_POST['rest-lat']);
			$rest_lng = mysql_real_escape_string($_POST['rest-lng']);
			$rest_city = explode(',',mysql_real_escape_string($_POST['rest-city']));
			$rest_line_1 = $rest_city[0];
			$rest_line_2 = $rest_city[1];
			$food_veg=mysql_real_escape_string($_POST['food_veg']);
			$food_desc=mysql_real_escape_string($_POST['food_desc']);
			$food_location=mysql_real_escape_string($_POST['food-location']);
			$food_lat=mysql_real_escape_string($_POST['food-lat']);
			$food_lng=mysql_real_escape_string($_POST['food-lng']);
			$food_time=strtotime("now");
			if(logged_userid())
			{
				$userid=logged_userid();
			}
			$name = $food_name;
			$names = explode(" ",$name);
			$count =0;
			$string='';
			foreach($names as $na)
			{
				if($count ==0)
				{
					$string=$string.strtolower($na);
				}
				else{
					$string=$string.'-'.strtolower($na);
				}
				$count++;
			}
			
			$query_name = mysql_query("select dish_url from food_items where dish_url='$string' ");
			$total = mysql_num_rows($query_name);
			if($total ==0)
			{
				$string_upd=$string;
			}
			else
			{
					$total=1;
					$flag=0;
					$string_upd=$string.'-'.$total;
					while($flag=='0')
					{
						$query_name_again = mysql_query("select dish_url from food_items where dish_url='$string_upd' ");
						$total_again = mysql_num_rows($query_name_again);
						if($total_again==0)
						{
								$flag=1;
								$string_upd=$string.'-'.$total;
						}
						else
						{
						$total =$total+1;
						$string_upd=$string.'-'.$total;
						}
					
					}
			}
		
			// Insert into database		
			$query=mysql_query("INSERT INTO food_items (user_id,dish_name,dish_url,place,description,vegetarian,food_location, food_lat, food_lng, food_time) VALUES('$userid','$food_name','$string_upd','$food_place','$food_desc','$food_veg','$food_location', '$food_lat', '$food_lng', '$food_time')");
			
			$food_id=mysql_insert_id();
			
			$query=mysql_query("INSERT INTO activity (activity_id, user_id, food_id,date) VALUES('1','$userid','$food_id','".strtotime("now")."')");
			$activity_id=mysql_insert_id();
			
			$query =  mysql_query("select badges, points from userinfo where user_id='$userid' limit 1");
			$user = mysql_fetch_array($query);
			if($user["badges"])
			{
				$badges=explode(",",$user['badges']);
			}
			else
			{
				$badges = array();
			}
			
			array_push($badges,$activity_id);
			$new_badges=implode(",",$badges);
			
				
			$query=mysql_query("UPDATE userinfo SET badges='$new_badges', points=points+".UPLOAD_POINTS.", num_discoveries=num_discoveries+1  WHERE user_id='$userid'");
			
			if(!$rest_id)
			{
				$rest_id = 'FC'.$food_id;
				mysql_query("INSERT INTO restaurant (restID,res_name) VALUES('$rest_id','$food_place')");
				$rest_id=mysql_insert_id();
			}
			else
			{
				$query=mysql_query("SELECT rsID from restaurant where restID='$rest_id'");
				$restr = mysql_fetch_array($query);
				$total_results = mysql_num_rows($query);
				if($total_results == 0)
				{
					mysql_query("INSERT INTO restaurant (restID,res_name,res_address, res_city,res_state, res_lat,res_lng) VALUES('$rest_id','$rest_name','$rest_address','$rest_line_1','$rest_line_2' ,'$rest_lat' , '$rest_lng')");
					$rest_id=mysql_insert_id();
				}
				else
				{
					$rest_id= $restr["rsID"];
				}
			}
			
			//updating restaurant Information
			$query=mysql_query("UPDATE food_items SET rest_id='$rest_id' WHERE food_items_id='$food_id'");
			
			
			$img_extension=substr($_POST['pic_uploaded'],strpos($_POST['pic_uploaded'],'.') );
			$img_filename="food_item".$food_id.$img_extension;
						
			if(!rename(ABS_PATH_TO_HOME.IMG_DIR.$_POST['pic_uploaded'],ABS_PATH_TO_HOME.IMG_DIR.$img_filename))
			{
				$upload_error="Unsuccessfull renaming image.";
			}
			else
			{		
				rename(ABS_PATH_TO_HOME.IMG_DIR.'orig_'.$_POST['pic_uploaded'],ABS_PATH_TO_HOME.IMG_DIR.'orig_'.$img_filename);
				//Update database with image name
				require_once("include/resize_img.inc.php");
				$resizer=new SimpleImage();
				$resizer1=new SimpleImage();
				$resizer2=new SimpleImage();
				$resizer4=new SimpleImage();
				$resizer->load('./uploads/food_pics/'.$img_filename);
				$resizer1->load('./uploads/food_pics/'.$img_filename);
				$resizer2->load('./uploads/food_pics/'.$img_filename);
				$resizer4->load('./uploads/food_pics/orig_'.$img_filename);
				
				$img_info=getimagesize(ABS_PATH_TO_HOME.IMG_DIR.$img_filename);
				$img_width=$img_info[0];
				$img_height=$img_info[1];
				
				$width1 = 138;
				$height1 = 90;
				
				$width2 = 200;
				$height2 = 200;
				
				$height4 = 800;
				$width4 = 800;
				
				$end_chars1 = strtolower(substr($img_filename, -4));
				$end_chars2 = strtolower(substr($img_filename, -5));
         // echo $end_chars1;     
                if( $end_chars1 == '.jpg' || $end_chars2 == '.jpeg')
                {
                        $image = IMAGETYPE_JPEG;
                }
                else
                {
                        if($end_chars1 == '.png')
                        {
                                $image = IMAGETYPE_PNG;
                        }
                        else
                        {
                                if($end_chars1 == '.gif')
                                {
                                        $image = IMAGETYPE_GIF;
                                }
                               
                        }
                }
				
				if(($img_width/$img_height)>=($width1/$height1))
				{
					//Resize to height
					$resizer->resizeToHeight($height1);
				}
				else
				{
					//Resize to width
					$resizer->resizeToWidth($width1);
				}
				
				//Crop image to size,centered
				$resizer->cropImage($width1,$height1,true);
				$resizer->save2(ABS_PATH_TO_HOME.IMG_DIR.'tn1_'.$img_filename, $image);
				
				
				if(($img_width/$img_height)>=($width2/$height2))
				{
					//Resize to height
					$resizer2->resizeToHeight($height2);
				}
				else
				{
					//Resize to width
					$resizer2->resizeToWidth($width2);
				}
				
				//Crop image to size,centered
				$resizer2->cropImage($width2,$height2,true);
				$resizer2->save2(ABS_PATH_TO_HOME.IMG_DIR.'tn2_'.$img_filename,$image);
				
				if(($img_width/$img_height)<=($width4/$height4))
				{
					//Resize to height
					$resizer4->resizeToHeight($height4);
				}
				else
				{
					//Resize to width
					$resizer4->resizeToWidth($width4);
				}
				
				$resizer4->merge();
				$resizer4->save2(ABS_PATH_TO_HOME.IMG_DIR.'tn_full_'.$img_filename,$image);
				
				
				$resizer1->cropImage(IMG_RESIZE_WIDTH,IMG_RESIZE_HEIGHT,true);
				$resizer1->merge2();
				$resizer1->save(ABS_PATH_TO_HOME.IMG_DIR.''.$img_filename);
				
				$query=mysql_query("UPDATE food_items SET dish_image='$img_filename' WHERE food_items_id='$food_id'");
			}
			
			header("location: complete-".$food_id);
		}
		else
		{
			/* Form was incomplete. */
			$upload_error="Some fields were incomplete: ".$_POST['food_name'].", ".$_POST['food_place'].", ".$_POST['food_veg'].", ".$_POST['food_desc'].", ".$_FILES['food_pic']['name'].",".$_FILES['food_pic']['error'];	
		}
	}
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
border: 1px solid whitesmoke;
cursor: pointer;
font-family: 'Quicksand_Bold',Arial;
}

.ui-menu-item:hover {

background: #f6b65d;

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
font-size: 15px;
overflow: hidden;
text-overflow: ellipsis;
    }
.autocomplete-detail {
  	color: #333;
font-weight: normal;
font-size: 12px;
margin-top: 5px;
overflow: hidden;
text-overflow: ellipsis;
    }
	
article, aside, figure, footer, header, hgroup, 
menu, nav, section { display: block; }
</style>
	</head>
		
	<body class="home" onload="initialize();">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>
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
					<?php if($upload_error){ ?>
					<div style="padding:10px;text-align:center;">
					<?php
						echo ($upload_error)?($upload_error):'';
					?>
					</div>
					<?php } ?>
										
					<!-- **********Image upload form*************** --> 
					
					
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
					<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
					<td width="47%" align="left" valign="top">
					<h1 id="location_sensor" class="heading_type1 size2" style="display:none">You are in or near: <span id="user_location" class="text_orange">Loading..</span></h1>
					</td>
					<td width="6%" align="center" valign="middle">
					<h1 class="heading_type1 size2">OR</h1>
					</td>
					<td width="47%" align="right" valign="top">
						
							<input id="location_name" type="text" class="input1 searchfood" style="display:inline; width:150px;" value="Select Location" title="Select Location"/>
							
						
					</td>
					
					</tr></table>
					</div>
   <form enctype="multipart/form-data" id="food_form" action="<?php echo $_SERVER['PHP_SELF'];?>" METHOD="POST">
					<h1 class="heading_type1 size2">Add new discovery</h1>
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
						<input type="text" id="food_name" name="food_name" value="Name of the Discovery" title="Name of the Discovery" class="input1 searchfood" style="display:inline; width:270px;"/>
						
							<span style="margin:0 5px 0 5px; font-size:24px;">@</span>
							<input type="text" id="venue" name="food_place" value="Restaurant" title="Restaurant" class="input1 searchfood" style="display:inline; width:270px; -webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px;"/>
<div><span id="nameInfo" style="float:left; margin-left:10px;" class="food_form_error"></span>
<span id="placeInfo" style="float:right;margin-right: 60px;" class="food_form_error"></span>
</div>
<div class="clearfix"></div>
				 <div style="display:none;">
						<input type="text" id="food-location" name="food-location"/>
						<input type="text" id="food-lat" name="food-lat"/>
						<input type="text" id="food-lng" name="food-lng"/>
						<input type="text" id="rest-id" name="rest-id"/>
						<input type="text" id="rest-name" name="rest-name"/>
						<input type="text" id="rest-address" name="rest-address"/>
						<input type="text" id="rest-city" name="rest-city"/>
						<input type="text" id="rest-lat" name="rest-lat"/>
						<input type="text" id="rest-lng" name="rest-lng"/>
						<div class="categoryIconContainer">
							<img id="venue-icon" src="" class="ui-state-default" />    
						</div>
						<div id="venue-name" class="autocomplete-name"></div>
						<div id="venue-address" class="autocomplete-detail"></div>
						<div id="venue-cityLine" class="autocomplete-detail"></div>
					</div>
					
					</div>
					
					<div style="border-bottom:1px dashed #ccc; margin:10px 0; padding:10px 0 15px 0">
					<span class="heading_type1 size2">Vegeterian</span>
					<span style="margin-left:200px;">
						<input type="radio" class="radio1" id="food_veg" name="food_veg" value="1" checked="true"/> <label style="font-size:17px;position:absolute" for="food_veg"><img src="images/icon_vegy.png" style="margin-top: -15px;"></label> &nbsp;&nbsp; 
							<input type="radio" class="radio1"  id="food_nonveg" name="food_veg" value="0" style="margin-left:80px;"/> <label style="font-size:17px; position:absolute; " for="food_nonveg"><img src="images/icon_non_vegy.png" style="margin-top: -15px;"></label>
					</span>
					</div>
					
					
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
					<h1 class="heading_type1 size2">Description</h1>
						<textarea id="food_desc" name="food_desc" class="input1 searchfood" style="display:inline; width:600px; height:100px;"></textarea>
						<span id="desInfo" class="food_form_error"></span>
						<div class="clearfix"></div>
					</div>
					
					<!-- 'pic_uploaded' contains the name of the uploaded file -->
					<input type="hidden" id="pic_uploaded" name="pic_uploaded" value="false"/>
					</form>
					
					<!-- Ajax image upload by iframe method -->
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
						<h1 class="heading_type1 size2">Discovery photo <span style="font-weight:normal;font-size:12px;">( Max. file size: <?php echo (MAX_UPLOAD_SIZE/(1024*1000));?> Mb )</span></h1>
						<form id="pic_upload_form" method="post" enctype="multipart/form-data">
							<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_SIZE;?>" />
							<input name="food_pic" id="food_pic" size="27" type="file"  style="padding:3px;"/>
						</form>
						<br/><br/>
						<div id="upload" style="text-align:center;"></div>
						<span id="fileInfo" class="food_form_error"></span>
					</div>
					
					<!-- Form submit button -->
					<input type="button" value="Submit" class="button1" id="upload_submit" style="float:left;color:#fff;"/>
					
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
		<?php include('footer.php'); ?>
	<script>
	var map;
	var geocoder;
	var autocomplete, autocomplete_ask_location;
	var countryRestrict = { 'country': 'in' };
	var marker;
	var lat,lng;
	function initialize() 
		{
			autocomplete = new google.maps.places.Autocomplete(	document.getElementById('food_place'), {
				componentRestrictions: countryRestrict
			});
	
			google.maps.event.addListener(autocomplete, 'place_changed', function() 			{
			place_changed();
			});
			
			autocomplete_ask_location = new google.maps.places.Autocomplete(document.getElementById('location_name'), {
				componentRestrictions: countryRestrict
			});
	

			google.maps.event.addListener(autocomplete_ask_location, 'place_changed', function() 			{
			place_changed_ask_location();
			});
			
			geocoder = new google.maps.Geocoder();
			lat=get_cookie('latitude');
			lng=get_cookie('longitude');
			user_place=get_cookie('user_place');
			
			if(!lat && !lng)
			{
				//Get user location
				navigator.geolocation.getCurrentPosition(function(position){
				lat=position.coords.latitude;
				lng=position.coords.longitude;
				
				// Set cookies
				set_cookie('latitude',lat,7);
				set_cookie('longitude',lng,7);
				$("#food-lat").val(lat);
				$("#food-lng").val(lng);
				$("#location_sensor").show();
				codeLatLng(lat, lng);
				
				});
			}
			else
			{	
					$("#location_sensor").show();
					
					$("#food-lat").val(lat);
					$("#food-lng").val(lng);
					if(!user_place){
						codeLatLng(lat, lng);
					}
					else
					{
					 $("#user_location").html( user_place);
					}
			}
			
			
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
		//location.reload();
  }
  
  
		function get_default_location(){
			navigator.geolocation.getCurrentPosition(function(position){
				lat=position.coords.latitude;
				lng=position.coords.longitude;
				
				// Set cookies
				set_cookie('latitude',lat,7);
				set_cookie('longitude',lng,7);
				location.reload();
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
				
			}
		});
		
		/*$("#location_input_form").submit(function(){
			var location=$("#location_name").val();
			if(location!='') codeAddress(location);
			return false;	// Disable page reload
		});
		*/
		
		
		function codeAddress(address) {
			geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) 
		  {
			set_cookie('latitude',results[0].geometry.location.lat(),7);
			set_cookie('longitude',results[0].geometry.location.lng(),7);
			location.reload();
		}
		  else
		  {
			alert("Geocoding was not successful for the following reason: " + status);
		  }
		});
		}
		
		function codeLatLng(lat, lng) {

    var latlng = new google.maps.LatLng(lat, lng);
	
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      console.log(results)
        if (results[1]) {
         //formatted address
       // alert(results[0].formatted_address)
	   $("#food-location").val(results[0].formatted_address);
        //find country name
             for (var i=0; i<results[0].address_components.length; i++) {
            for (var b=0;b<results[0].address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
			
                if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                    //this is the object you are looking for
                    city= results[0].address_components[i];
                    break;
                }
				
				if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    state= results[0].address_components[i];
                    break;
                }
				
				if (results[0].address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    locality= results[0].address_components[i];
                    break;
                }
				if (results[0].address_components[i].types[b] == "route") {
                    //this is the object you are looking for
                    place= results[0].address_components[i];
                    break;
                }
            }
        }
        //city data
        //alert(city.long_name)
			/*if(locality.long_name != city.long_name)
			{
				$("#user_location").html(place.long_name + ', ' + locality.long_name +', ' + city.long_name + ', ' + state.long_name);
			}
			else
			{
				$("#user_location").html(place.long_name + ', ' + city.long_name + ', ' + state.long_name);
			}*/
        $("#user_location").html( city.long_name + ', ' + state.long_name);
		set_cookie('user_place',city.long_name,7);
		
		
		} else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
  }
		
    $(function () {
        $("#venue").foursquareAutocomplete({
            'latitude': get_cookie('latitude'),
            'longitude': get_cookie('longitude'),
            'oauth_token': "Z3A2S3XAO3AZRDFQHZVMMU4FQDDSNNWORJOSNRW5GFC1EPKL",
            'minLength': 2,
            'search': function (event, ui) {
                $("#venue-name").html(ui.item.name);
                $("#rest-id").val(ui.item.id);
                $("#rest-name").val(ui.item.name);
                $("#rest-address").val(ui.item.address);
                $("#rest-city").val(ui.item.cityLine);
				$("#rest-lat").val(ui.item.lat);
                $("#rest-lng").val(ui.item.lng);
                $("#venue-address").html(ui.item.address);
                $("#venue-cityLine").html(ui.item.cityLine);
                $("#venue-icon").attr("src", ui.item.photo);
                return false;
            },
            'onError' : function (errorCode, errorType, errorDetail) {
            	var message = "Foursquare Error: Code=" + errorCode + ", errorType= " + errorType + ", errorDetail= " + errorDetail;
            	log(message);
            }
            
        });
    });
    function log(message) {
        $("<div/>").text(message).prependTo("#log");
        $("#log").scrollTop(0);
    }
</script>

	</body>
	
		<script type="text/javascript">
		
		
		function validateName(){

		//if it's NOT valid
			if( $("#food_name").val().length < 3 | $("#food_name").val() == 'Name of the Discovery' ){
			$('#nameInfo').text("Please enter a valid name");
			return false;
			}
			//if it's valid
			else{
					$('#nameInfo').text("");
					return true;
			}
		}
		
		function validatePlace(){

		//if it's NOT valid
			if($("#venue").val().length < 3 | $("#venue").val() == 'Restaurant' |  $("#venue").val() == 'restaurant'){
			$('#placeInfo').text("Please enter a valid Restaurant's name!");
			return false;
			}
			//if it's valid
			else{
					$('#placeInfo').text("");
					return true;
			}
		}
		
		function validateDes(){

		//if it's NOT valid
			if($("#food_desc").val().length < 3){
			$('#desInfo').text("Please enter the description");
			return false;
			}
			//if it's valid
			else{
					$('#desInfo').text("");
					return true;
			}
		}
		
		function validateFile(){
			var ext = $("#food_pic").val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
				{
			$('#fileInfo').text("Please Choose a valid file");
			return false;
			}
			//if it's valid
			else{
					$('#fileInfo').text("");
					return true;
			}
		}
	
	$('#upload_submit').click(function(){
			if(validateName() & validatePlace() & validateDes() & validateFile())
			$('#food_form').submit();
			else
			return false;
		});
		
	
		$("#food_pic").change(function(){
			if( this.files[0].size < 2000000)
			{
				var ext = $(this).val().split('.').pop().toLowerCase();				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
				{
					$("#upload").html("Hey!! Are you even uploading an image? You clicked on the wrong file.");
				}
				else
				{
					fileUpload(this.form,'ajax/upload_food_pic.php','upload');
				}
			}
			else
			{
				$("#upload").html("Sorry. Bur your file exceeds the max allowed size.");
			}
			
			//fileUpload(this.form,'ajax/upload_food_pic.php','upload');
		});
		
		
		
		function fileUpload(form, action_url, div_id) {
		$('#upload_submit').hide();
			// Create the iframe...
			var iframe = document.createElement("iframe");
			iframe.setAttribute("id", "upload_iframe");
			iframe.setAttribute("name", "upload_iframe");
			iframe.setAttribute("width", "0");
			iframe.setAttribute("height", "0");
			iframe.setAttribute("border", "0");
			iframe.setAttribute("style", "width: 0; height: 0; border: none;");
		 
			// Add to document...
			form.parentNode.appendChild(iframe);
			window.frames['upload_iframe'].name = "upload_iframe";
		 
			iframeId = document.getElementById("upload_iframe");
		 
			// Add event...
			var eventHandler = function () {
		 
					if (iframeId.detachEvent) iframeId.detachEvent("onload", eventHandler);
					else iframeId.removeEventListener("load", eventHandler, false);
		 
					// Message from server...
					if (iframeId.contentDocument) {
						content = iframeId.contentDocument.body.innerHTML;
					} else if (iframeId.contentWindow) {
						content = iframeId.contentWindow.document.body.innerHTML;
					} else if (iframeId.document) {
						content = iframeId.document.body.innerHTML;
					}
					document.getElementById(div_id).innerHTML=content;
					// Del the iframe...
					setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
					
					var response = eval('('+content+')'); //response is json
					if(response.status==0)
					{
						//Upload successfull
						document.getElementById(div_id).innerHTML="<img id='uploaded_img' name='"+response.img_name+"' src='"+response.filepath+"' />";
						$('#pic_uploaded').val(response.img_name);
						$('#fileInfo').text("");
						$('#upload_submit').show();
					}
					else
					{
						document.getElementById(div_id).innerHTML=response.msg;
							
					}
					
				}
		 
			if (iframeId.addEventListener) iframeId.addEventListener("load", eventHandler, true);
			if (iframeId.attachEvent) iframeId.attachEvent("onload", eventHandler);
		 
			// Set properties of form...
			form.setAttribute("target", "upload_iframe");
			form.setAttribute("action", action_url);
			form.setAttribute("method", "post");
			form.setAttribute("enctype", "multipart/form-data");
			form.setAttribute("encoding", "multipart/form-data");
		 
			// Submit the form...
			form.submit();
		 
			document.getElementById(div_id).innerHTML = "Uploading...";
			$('#fileInfo').text("");
		}
		
		$('#upload_submit').click(function()
		{
			
		});

		</script>
</html>
<?php
}
else
{
header('Location: '.get_page_url('landing'));
}
?>
