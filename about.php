<?php
	require_once("include/lib.inc.php");
	require_once("include/config.inc.php");
	require_once("include/session.inc.php");
	
	$title='About';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title>About Us FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="shorcut icon" href="<?php echo BASE_URL_2?>images/favicon.gif" />
		<link href="<?php echo BASE_URL_2?>css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/jquery-1.7.2.min.js"></script>
		<script src="<?php echo BASE_URL_2?>js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo BASE_URL_2?>js/jquery.tipTip.minified.js"></script>
		<link href="<?php echo BASE_URL_2?>css/tipTip.css" type="text/css" rel="stylesheet" />

	</head>
		
	<body class="home" onload="initialize()">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>
	
	<!-- The popup -->
		<?php require_once 'pop_up.php'; ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<?php require_once 'header_gen.php'; ?>
								
				<div id="content">
					<?php require_once 'page_search.php'; ?>
					
					<div id="about_left">
				

<h1 class="heading_type1 size1">Food for thought</h1>
<p class="para_type1"><span class="heading_type1 text_orange" style="font-size:16px;">What is foodies compass all about?</span></p>
<p class="para_type1">We are a squad of food enthusiasts who have solutions to all your craving questions. We strive to help you make informed choices and get the most out of your dining experience. At Foodies Compass, you can access and create extensive information related to your food, from what to eat at an untried restaurant with food pictures, ratings and reviews to uploading photographs of food you like.</p>

<h1 class="heading_type1 size1">Team Foodies</h1>
<p class="para_type1">We are as diverse as cuisines in the world yet together motivated for our common inspiration -Food. Foodies Compass is a result of continuous efforts of food aficionados who love to explore miscellany of food cultures around the world combined with efficient developers, web designers determined to make this platform an integrated foodie's world. We have on board food photographers and a professional social media team to make Foodies Compass a completely comfortable and pleasing experience. We believe that our users/members play a significant role in completing our Foodies Compass family</p>

					</div>
					<div id="about_right">
					<h1 class="heading_type1">Contact Form</h1>
					<div id="contact_form">
					<form>
<?php
if(is_logged_in())
{
	$result = mysql_query("select name, email from userinfo where user_id='".logged_userid()."' limit 1");

	$ppl = mysql_fetch_array($result);
	$name = $ppl["name"];
	$email = $ppl["email"];
}


?>						
							<input type="text" id="name" name="name" value="<?php echo $name? $name:'Your Name';?>" title="Your Name" class="input1 searchfood" style="width:300px;"/>
							<input type="hidden" id="user_id" value="<?php echo logged_userid();?>"/>
							<span id="nameInfo" style="float:left; margin-left:10px;" class="food_form_error"></span>
							<div style="margin-bottom:20px;"></div>
							<input type="text" id="email" name="email" value="<?php echo $email? $email:'Your Email';?>" title="Your Email" class="input1 searchfood" style="width:300px;"/>
							<span id="emailInfo" style="float:left; margin-left:10px;" class="food_form_error"></span>
							<div style="margin-bottom:20px;"></div>
							<textarea id="message" name="message" style="width:400px; height:200px; font-size:13px; line-height:1.5;" class="input1 searchfood" onclick="clear_this_textarea()" onblur="ret_this_textarea()">Your Message</textarea>
							<span id="messageInfo" style="float:left; margin-left:10px;" class="food_form_error"></span>
							<div style="margin-bottom:20px;"></div>
							<input type="button" class="button1" value="Send" onclick="sendmessage();">				
</form>	
	</div>				
					
					
					</div>
					<div style="clear:both">
					
<h1 class="heading_type1 size1">How it works?</h1>
<p class="para_type1"><span class="heading_type1 text_orange" style="font-size:16px;">For restaurant owners: </span>
If you own one of the heavenly restaurants anywhere in India, this is the platform where you can expand your base. Each restaurant owner at Foodies Compass is provided with a free ownership of a restaurant page. We aim to show your specialty as well as popular food items in your restaurant. It is just like your own mini website with a flexibility to target your customers innovatively through food pictures, characteristics and an integrated social media platform that would boost your restaurant's social quotient.</p>

<p class="para_type1"><span class="heading_type1 text_orange" style="font-size:16px;">For Foodies:</span>
Dive into the blissful online world of food where you can explore exactly what you wish. You can have your own gang of foodies and speak your heart out about your food. Foodies Compass is a platform for you to stay up to date with restaurants, review s of food items offered by them through authentic ratings and food pictures. We intend to make your dining experience enchanting. Just log in to find out answers to all your questions related to eating out!</p>
<h1 class="heading_type1 size1">Find Us</h1>
<div id="social_icons">
<a href="http://vimeo.com/user17703330" target="_blank"><img src="images/vm_icon.png"></a>
<a href="https://www.facebook.com/compass.foodies" target="_blank"><img src="images/fb_icon.png"></a>
<a href="http://pinterest.com/foodiescompass/" target="_blank"><img src="images/pt_icon.png"></a></a>
<a href="https://twitter.com/foodiescompass" target="_blank"><img src="images/tw_icon.png"></a>
</div>
					</div>
				</div>
		
		
		
		</div>
		<br>
		<?php require_once("footer.php"); ?>
	</body>
	<?php include 'include/php_js.php'; ?>
	
	<script type="text/javascript">
	
	var autocomplete, autocomplete_rest;
		var countryRestrict = { 'country': 'in' };
		
	function initialize() 
		{
			
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
  
	function clear_this_textarea()
	{
	if($("#message").val()=='Your Message') $("#message").val('');
	}
	function ret_this_textarea()
	{
	if($("#message").val()=='') $("#message").val('Your Message');
	}
	
	function validateName(){

		//if it's NOT valid
			if( $("#name").val().length < 3 || $("#name").val() == 'Your Name' ){
			$('#nameInfo').text("Please enter a valid name");
			return false;
			}
			//if it's valid
			else{
					$('#nameInfo').text("");
					return true;
			}
		}
		
		function validateDes(){

		//if it's NOT valid
			if($("#message").val().length < 3 || $("#message").val() == 'Your Message')  {
			$('#messageInfo').text("Please enter message");
			return false;
			}
			//if it's valid
			else{
					$('#messageInfo').text("");
					return true;
			}
		}
		
		function validateEmail(){
		var email=$("#email").val();
		var pattern=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/
		//if it's NOT valid
			if( email!="" && (email.match(pattern)) ){
			$('#emailInfo').text("");
					return true;
			return false;
			}
			//if it's valid
			else{
				$('#emailInfo').text("Please enter a valid email");
					
			}
		}
		
		function sendmessage(){
			if(validateName() & validateDes() & validateEmail())
			{
				$.post("ajax/messages.php", { name: $("#name").val(), user_id: $("#user_id").val() ,email: $("#email").val(), message: $("#message").val(), user_lat: get_cookie('latitude'), user_lng:get_cookie('longitude'), place:get_cookie('user_place') } ,function(data) {
				
				 $('#contact_form').html("<span class=\"thankyou\">Thank You " + $("#name").val()+ ". Your message has been recieved by us.</span>");

				  
				});
	
			}
			else
			return false;
		}
	</script>
</html>