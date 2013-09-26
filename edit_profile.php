<?php
	require_once("./include/config.inc.php");
	require_once("./include/lib.inc.php");
	require_once("./include/session.inc.php");
	require_once("./include/connection.inc.php");
	require_once("./include/flags.inc.php");
	
	/* Error flag */
	$error=array();
	
	/* Error codes */
	define('EDIT_PROFILE_EMAIL_INVALID',0);
	define('EDIT_PROFILE_INCOMPLETE_FORM',1);
	define('EDIT_PROFILE_OLD_PASS_MISMATCH',2);
	define('EDIT_PROFILE_NEW_PASS_MISMATCH',3);
	
	if(is_logged_in())
	{
		/* Get current profile data */
		$result=mysql_query("SELECT name,username,profile_pic,profile_pic_uploaded, user_id,dob,about_me,password,email,address1,address2,city,state FROM userinfo WHERE user_id=$_SESSION[id]");
		$user_data=mysql_fetch_array($result);
		$title = $user_data['name'] ? $user_data['name'] :$user_data['username']; 
		$title = $title.'\'s Profile'; 
		
		/* If form has been submitted... */
		if(isset($_POST['form_submit']))
		{
			
			/* If all form fields (not checking passwords right now) have been filled... */
			if($_POST['name']!='' &&
			   $_POST['username']!='' && 
			   $_POST['dob_dd']!='DD' && 
			   $_POST['dob_mm']!='MM' && 
			   $_POST['dob_yyyy']!='YYYY' && 
			   $_POST['about_me']!='' && 
			   $_POST['email']!='' && 
			   $_POST['address1']!='' && 
			   $_POST['address2']!='' && 
			   $_POST['city']!='' && 
			   $_POST['state']!='')
			   {
			   	/* Check email validity */
			   	if(preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^',$_POST['email'])===0)
			   	{
			   		$error[]=EDIT_PROFILE_EMAIL_INVALID;
			   	}
			   	
				/* If password fields were visible... */
			   	if(isset($_POST['pass_change_flag']))
			   	{
			   		error_log('Password fields visible');
			   		
			   			/* ... and user already has a password... */
			   			if(isset($_POST['old_pass']))
			   			{
			   				/* ... then none of the password fields should be empty... */
					   		if($_POST['old_pass']!='' && $_POST['new_pass1']!='' && $_POST['new_pass2']!='')
					   		{
					   			
			   					/* ... and old password entered must match old password saved ... */
			   					if($user_data['password']!=md5(mysql_real_escape_string($_POST['old_pass'])) )
					   			{
					   				$error[]=EDIT_PROFILE_OLD_PASS_MISMATCH;
					   			}
						   		
						   		/*... and new passwords should match. */	
					   			if($_POST['new_pass1']!=$_POST['new_pass2'])
					   			{
					   				$error[]=EDIT_PROFILE_NEW_PASS_MISMATCH;
					   			}
					   		}
					   		/* If passwords fields were empty */
					   		else
					   		{
					   			$error[]=EDIT_PROFILE_INCOMPLETE_FORM;
					   		}
					   	}
					   	/* If user does not have a password (signup using Facebook/Twitter)... */
					   	else
					   	{
					   		/* ... then none of the password fields should be empty... */
					   		if($_POST['new_pass1']!='' && $_POST['new_pass2']!='')
					   		{
						   		/* ...and newly entered passwords should match. */	
					   			if($_POST['new_pass1']!=$_POST['new_pass2'])
					   			{
					   				$error[]=EDIT_PROFILE_NEW_PASS_MISMATCH;
					   			}
					   		}
					   		else
					   		{
					   			$error[]=EDIT_PROFILE_INCOMPLETE_FORM;
					   		}
					   	} 	
			   	}
			   	
			   	if(!$error)
			   	{
			   		/* If there are no errors */
			   		$_POST['name']=mysql_real_escape_string($_POST['name']);
			   		$_POST['username']=mysql_real_escape_string($_POST['username']);
			   		$_POST['about_me']=mysql_real_escape_string($_POST['about_me']);
			   		$_POST['email']=mysql_real_escape_string($_POST['email']);
			   		$_POST['address1']=mysql_real_escape_string($_POST['address1']);
			   		$_POST['address2']=mysql_real_escape_string($_POST['address2']);
			   		$_POST['city']=mysql_real_escape_string($_POST['city']);
			   		$_POST['state']=mysql_real_escape_string($_POST['state']);
			   		$_POST['new_pass1']= (isset($_POST['pass_change_flag']))? md5(mysql_real_escape_string($_POST['new_pass1'])): $user_data['password'];
			   		
			   		/* Update database */
			   		mysql_query("UPDATE userinfo SET name='$_POST[name]', username='$_POST[username]',
			   		about_me='$_POST[about_me]', email='$_POST[email]', address1='$_POST[address1]', address2='$_POST[address2]',
			   		city='$_POST[city]', state='$_POST[state]', password='$_POST[new_pass1]', dob='$_POST[dob_mm]/$_POST[dob_dd]/$_POST[dob_yyyy]' 
			   		WHERE user_id=$_SESSION[id]");
			   		
			   		$result=mysql_query("SELECT name,username,dob,user_id,about_me,password,email,address1,address2,city,state FROM userinfo WHERE user_id=$_SESSION[id]");
					$user_data=mysql_fetch_array($result);
			   		
			   	}
			   	
			   }
			   
			   /* ..some fields were incomplete... */
			   else
			   {
			   	$error[]=EDIT_PROFILE_INCOMPLETE_FORM;
			   	
			   	/* Use entered information for populating the form */
			   	$user_data['name']=$_POST['name'];
		   		$user_data['username']=$_POST['username'];
		   		$user_data['about_me']=$_POST['about_me'];
		   		$user_data['email']=$_POST['email'];
		   		$user_data['address1']=$_POST['address1'];
		   		$user_data['address2']=$_POST['address2'];
		   		$user_data['city']=$_POST['city'];
		   		$user_data['state']=$_POST['state'];
		   		
		   		if(isset($_POST['pass_change_flag']))
		   		{
		   			$old_pass_val=$_POST['old_pass'];
		   			$new_pass1_val=$_POST['new_pass1'];
		   			$new_pass2_val=$_POST['new_pass2'];
		   		}
			   }
		}

	}
	else
	{
		header("Location:index.php");
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
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>
	
	<?php require_once("./include/fb_js.inc.php"); ?>
	
	<!-- The popup --->
		<?php require_once 'pop_up.php'; ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<?php require_once 'header_gen.php'; ?>
								
				<div id="content">
					<?php require_once 'page_search.php';  ?>
						
						<div style="border-bottom: 1px solid #F9AE44; margin:0 5px 0 5px;"></div>
						
						<div style="margin:0 10px 0 10px;padding-bottom:5px; margin-top:15px;">
							<div id="follower_left" style="width:600px;float:left;border-right:1px solid #bbb;">
								<div id="follower" style="padding-bottom:4px; ">

									<div style="float:left; width:370px;">
										<div id="follower_about">
											<h3 class="food_name follower_name">Edit Profile Information</h3>
										</div>
										<div class="clearfix"></div>
									</div>
									<div style="margin-top:10px; float:left; width:370px;">
									<span style='font-family:Quicksand_bold;color:#f8ae43;'>
										<?php
											if(isset($_POST['form_submit']) && !$error)
											{
												/* Form submitted and there are no error = SUCCESS ! */
												echo "<br/><center>Changes saved successfully !</center><br/>";
											}
											else if(isset($_POST['form_submit']) && $error)
											{
												if(in_array(EDIT_PROFILE_INCOMPLETE_FORM,$error))
												{
													echo "<br/><center>Some fields were empty !</center><br/>";
												}
											}
										?>
									</span>
									<form action="<?php echo get_page_url('edit_profile');?>" method="POST">
										<div class="user_info">
											<div style="margin:5px;">Name</div>
											<input type="text" class="input1" name="name" style="width:300px" value="<?php echo $user_data['name'];?>"/>
										</div>
										<div class="user_info">
											<div style="margin:5px;">Foodie Name</div>
											<input name="username" type="text" class="input1" style="width:300px" value="<?php echo $user_data['username'];?>" readonly/>
										</div>
										<?php
											$dob=explode('/',$user_data['dob']);
										?>
										<div class="user_info">
											<div style="margin:5px;">Birthday (DD/MM/YYYY)</div>
											
											<?php /* Date */ ?>
											<select name="dob_dd">
												<?php
													for($i=1;$i<=31;$i++)
													{
												?>
												<option <?php echo (intval($dob[1])==$i)? 'selected="selected"': ''; ?> value="<?php echo $i;?>"><?php echo ($i<10)?'0'.$i: $i;?></option>
												<?php
													}
												?>
											</select>
											
											<?php /* Month */ ?>
											<select name="dob_mm">
												<?php
													for($i=1;$i<=12;$i++)
													{
												?>
												<option <?php echo (intval($dob[0])==$i)? 'selected="selected"': ''; ?>  value="<?php echo $i;?>"><?php echo ($i<10)?'0'.$i: $i;?></option>
												<?php
													}
												?>
											</select>
											
											<?php /* Year */ ?>
											
											<select name="dob_yyyy">
												<?php
													for($i=(date('Y')-MIN_AGE);$i>=(date('Y')-MIN_AGE-120);$i--)
													{
												?>
												<option <?php echo (intval($dob[2])==$i)? 'selected="selected"': ''; ?> value="<?php echo $i;?>"><?php echo $i;?></option>
												<?php
													}
												?>
											</select>
											
										</div>
										<div class="user_info">
											<div style="margin:5px;">About Me</div>
											<textarea name="about_me" class="input1" style="width:400px; height:70px;"><?php echo $user_data['about_me']; ?></textarea>
										</div>
										<div class="user_info">
											<?php
												if(in_array(EDIT_PROFILE_EMAIL_INVALID,$error))
												{
													echo "Invalid email address !";
												}
											?>
											<div style="margin:5px;">Email</div>
											<input name="email" type="text" class="input1" style="width:300px;" value="<?php echo $user_data['email']; ?>" readonly/>
										</div>
										<div class="user_info">
											<div style="margin:5px;">Address</div>
											<input name="address1" type="text" class="input1" style="width:300px" value="<?php echo $user_data['address1']; ?>"/>
											<input name="address2" type="text" class="input1" style="width:300px;margin-top:5px;" value="<?php echo $user_data['address2']; ?>"/>
										</div>
										<div class="user_info">
											<div style="margin:5px;">City</div>
											<input name="city" type="text" class="input1" value="<?php echo $user_data['city']; ?>"/>
										</div>
										<div class="user_info">
											<div style="margin:5px;">State</div>
											<input name="state" type="text" class="input1" value="<?php echo $user_data['state']; ?>"/>
										</div>
										<div class="user_info">
											<div style="margin:5px;">Password</div>
											
											<input type="button" class="button2" id="pass_change_btn" value="<?php echo ($user_data['password']=='')?'Create password...':'Change Password...';?>"/>
											
											<div class="closable" style="margin-left:30px;">
											
												<span style='font-size:12px;color:#f8ae43;'>
												<?php
													if(in_array(EDIT_PROFILE_OLD_PASS_MISMATCH,$error))
													{
														echo "Old password did not match !";
													}
												?>
												</span>
												
												<?php
													if($user_data['password']!='')
													{
												?>
												<div style="margin:5px;">Old password</div>
												<input name="old_pass" type="password" class="input1" value="<?php echo $old_pass_val;?>"/>
												<?php
													}
												?>
												
												<span style='font-size:12px;color:#f8ae43;'>
												<?php
													if(in_array(EDIT_PROFILE_OLD_PASS_MISMATCH,$error))
													{
														echo "Passwords do not match !";
													}
												?>
												</span>
												
												<div style="margin:20px 5px 5px 5px;">New password</div>
												<input name="new_pass1" type="password" class="input1" value="<?php echo $new_pass1_val;?>"/>
												
												<div style="margin:5px;">Re-enter new password</div>
												<input name="new_pass2" type="password" class="input1" value="<?php echo $new_pass2_val;?>"/>
												<br/>
												<input type="button" id="pass_change_cancel_btn" class="button2" value="Cancel"/>
											</div>
											<script type="text/javascript">
											$('#pass_change_btn').click(function(){
												$(this).css('display','none');
												$('.closable').css('display','block').append("<input id='pass_change_flag' type='hidden' name='pass_change_flag' />");
											});
											
											$('#pass_change_cancel_btn').click(function(){
												$('#pass_change_btn').css('display','inline');
												$('.closable').css('display','none');
												$('#pass_change_flag').remove();
											});
											</script>
										</div>
										<div class="user_info">
											<br/><br/>
											<input type="submit" class="button1" value="Save"/>
										</div>
										<div class="clearfix"></div>
									<input type="hidden" name="form_submit" value="aouasd" />
									</form> <!-- Edit profile information form ends  -->
									</div>
									<div class="clearfix"></div>
								</div>
								
								<div id="activities" style="margin-top:10px;">
								

								</div>
							</div>
							
							<div id="follower_right" style="width:360px;float:right; padding-left:10px;">
									
								<div id="follower1" style="margin-top:10px;">
									<h3 class="food_name" style="font-size:20px;">Profile picture</h3>
									<div id="people_item" style="float:left">
									<img src="<?php echo get_page_url("profile_pic",array("user_id"=>$user_data["user_id"], "size"=>'bigger')); ?>">
										
									</div>
									<div class="clearfix"></div>
									<div class="upload_profile">
									<p style="margin-bottom:5px;">Change Profile Pic</p>
									<form id="pic_upload_form" method="post" enctype="multipart/form-data">
							<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_SIZE;?>" />
							<input name="food_pic" id="food_pic" type="file"  style="padding:3px;"/>					<input type="hidden" id="pic_uploaded" name="pic_uploaded" value="false"/>
							<div id="upload" style="text-align:center;"></div>
							<span id="fileInfo" class="food_form_error"></span>
							<input type="button" value="Upload" class="button1" id="upload_submit" style="float:left;color:#fff;" /><span id="wait"></span>
						</form>
						<br/><br/>
						
									<!---<input type="submit" class="button1" value="Upload">--->
								</div>	
								</div>
							</div>
							
							<div class="clearfix"></div>
							
						</div>
						
						
					
				</div>
		
		
		
		</div>
		<br>
	<?php include('footer.php'); ?>
	</body>
	
	<?php require_once('include/php_js.php'); ?>

	<script type="text/javascript">
	
		$('#upload_submit').hide();
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
  
	$("#food_pic").change(function(){
			if( this.files[0].size < 2000000)
			{
				var ext = $(this).val().split('.').pop().toLowerCase();				
				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
				{
					$("#upload").html("Hey!! Are you even uploading an image? You clicked on the wrong file.");
				}
				else
				{
					fileUpload(this.form,'ajax/upload_user_pic.php','upload');
				}
			}
			else
			{
				$("#upload").html("Sorry. Bur your file exceeds the max allowed size. We don't have much webspace.. we are not GOOGLE.");
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
				{
					user_pic($('#pic_uploaded').val());
				}
			else
			return false;
		});
		
		function user_pic(image)
		{
		$("#wait").html('wait');
				$.ajax({
				url:'ajax/user_pic_upload.php',
				data:{'image':image},
				success:function(data){
				//alert(data);
					if(data=='<?php echo NO_SESSION_EXISTS_ERROR;?>' ||  data=='<?php echo INSUFFICIENT_PARAMS_ERROR;?>')
					{
						alert('Errors!!');
						
					}
					else
					{
						$('#people_item').html('<img src="<?php echo BASE_URL."/".PR_DIR;?>' + data +'">');
						$('#upload_submit').hide();
						$('#upload').html('');$("#wait").html('');
					}
				}
			});
		
		
		}
	</script>
</html>