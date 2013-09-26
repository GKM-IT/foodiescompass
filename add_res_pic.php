<?php
	require_once('include/config.inc.php');
	require_once('include/lib.inc.php');
	require_once('include/flags.inc.php');
	require_once('include/session.inc.php');
	require_once('include/connection.inc.php');
	require_once('include/functions.inc.php');
	define('IMG_RESIZE_WIDTH',375);
	define('IMG_RESIZE_HEIGHT',245);
	$rest_id = mysql_real_escape_string($_GET["rid"]);
	//echo $rest_id;
	if(is_logged_in())
	{
	
	$title='';
	$title = 'Upload Images';
	$upload_error=false;
	//echo logged_userid();
	if(isset($_POST['pic_uploaded']))
	{
		if($_POST['pic_uploaded'])
		{
			
			$food_time=strtotime("now");
			if(logged_userid())
			{
				$userid=logged_userid();
			}
			$food_id = mysql_real_escape_string($_GET["fid"]);
			// Insert into database		// type 3 for restaurant's images
			$query=mysql_query("INSERT INTO images (user_id, rest_id, image_type) VALUES('$userid', '$rest_id','3')");
			
			$image_id=mysql_insert_id();
			
			$query=mysql_query("INSERT INTO activity (activity_id, user_id, rest_id,date, image_id) VALUES('8','$userid','$_GET[rid]','".strtotime("now")."' , '$image_id' )");
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
			
				
			$query=mysql_query("UPDATE userinfo SET badges='$new_badges', points=points+".ADD_RES_PIC_POINTS.", num_pictures=num_pictures+1  WHERE user_id='$userid'");
			
			$img_extension=substr($_POST['pic_uploaded'],strpos($_POST['pic_uploaded'],'.') );
			
			$img_filename="res_pic".$image_id.$img_extension;
						
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
				$resizer1->merge();
				$resizer1->save(ABS_PATH_TO_HOME.IMG_DIR.''.$img_filename);
				
				$query =  mysql_query("select res_image from restaurant where rsID ='$rest_id' limit 1");
				$food_prev_img = mysql_fetch_array($query);
				$prev_img = $food_prev_img["res_image"];
				
				if($prev_img)
					{ $query=mysql_query("UPDATE images SET image='$img_filename' WHERE image_id='$image_id'");}
				else
				{
					$query=mysql_query("UPDATE images SET image='$img_filename', main_pic='1' WHERE image_id='$image_id'");
					
					$query=mysql_query("UPDATE restaurant SET res_image='$img_filename' WHERE rsID='$rest_id'");
				}
			}
			
			header("location: restaurant.php?eid=".$rest_id);
		}
		else
		{
			/* Form was incomplete. */
			$upload_error="Some fields were incomplete";	
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
					
					
					
   <form enctype="multipart/form-data" id="food_form" action="<?php echo $_SERVER['PHP_SELF'].'?rid='.$rest_id;?>" METHOD="POST">
					
					<div>
						<div>
							<?php
								
										
								//$rest_id = mysql_real_escape_string($_GET["rid"]);
								//$rest_id = $_GET["rid"];
								$query_rest = mysql_query( "select * from restaurant where rsID= '$rest_id'");
								$restr = mysql_fetch_array($query_rest);
							?>
							<?php if($restr["res_image"])
							{ ?>
							<div style="float:left;">
							<img src="<?php echo get_page_url("rest_pic",array("rid"=>$restr["rsID"], "size"=>"thumb")); ?>" style="margin-left:5px;"/>
							</div>
							
							<?php }
							?>
							
							
								<div class="food_item" style="padding-bottom:8px; margin-left:10px; float:left;">
										<h1 class="food_name" style="font-size:24px;"><a href="<?php echo get_page_url("restaurant",array("eid"=>$rest_id));?>" style="color:inherit;"><?php echo $restr['res_name']; ?></a><br></h1>
											<p style="margin:5px 0; font-weight:bold;" class="food_typo"  >
											<?php echo ($restr['res_address']=="")?"":$restr['res_address']."<br/>";?>
											<?php echo ($restr['res_city']=="")?"":$restr['res_city']."<br/>";?>
										</p>
								</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0;">
						
					
					</div>
					
					<!-- 'pic_uploaded' contains the name of the uploaded file -->
					<input type="hidden" id="pic_uploaded" name="pic_uploaded" value="false"/>
					</form>
					
					<!-- Ajax image upload by iframe method -->
					<div style="border-bottom:1px dashed #ccc; padding-bottom:5px; margin:10px 0; text-align:center;" >
						<h1 class="heading_type1 size2">Add New Photo <span style="font-weight:normal;font-size:12px;">( Max. file size: <?php echo (MAX_UPLOAD_SIZE/(1024*1000));?> Mb )</span></h1>
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
	var marker;
	var lat,lng;
	function initialize() 
		{
			geocoder = new google.maps.Geocoder();
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
				$("#food-lat").val(lat);
				$("#food-lng").val(lng);
				codeLatLng(lat, lng);
				});
			}
			else
			{	
					$("#food-lat").val(lat);
			$("#food-lng").val(lng);
					codeLatLng(lat, lng);
				
			}
			
			
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
		
		$("#location_input_form").submit(function(){
			var location=$("#location_name").val();
			if(location!='') codeAddress(location);
			return false;	// Disable page reload
		});
		
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
			if(validateFile())
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
		
		

		</script>
</html>
<?php
}
else
{
header('Location: '.get_page_url('landing'));
}
?>
