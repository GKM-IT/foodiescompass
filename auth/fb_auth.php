<?php
	ini_set("include_path","/home/foodiesc/public_html/beta/");
	
	require_once('../include/lib.inc.php');
	require_once('../include/config.inc.php');
	require_once("../include/connection.inc.php");
	require_once("../include/session.inc.php");
	
	/*_____________ FACEBOOK APP DETAILS ___________________ */
	
	$app_id=FB_APP_ID;
	$app_secret=FB_APP_SECRET;
	
	if(isset($_GET['error']))
	{
		/* An error has occured. Redirect to home page on error. */
		header('Location: '.BASE_URL);
	}
	else if ( isset($_GET['code'] ))
	{
		$code=str_replace("#_=_","",$_GET['code']);
		$url="https://graph.facebook.com/oauth/access_token?"."client_id=$app_id&".
		"redirect_uri=".get_page_url('fb_auth')."&".
		"client_secret=$app_secret&code=$code";
		
		$result=curl($url);
		
		/* If some text is obtained... */
		if($result)
		{
		
			/* ...then check if text is facebook error msg or access token.*/
			if($error=json_decode($result))
			{
				/* Opps....json has been received. This means error has occured. */
				echo "Error : ".$error->msg;
			}
			else
			{
				/* Wohoo! No error. */
				parse_str($result);
								
				/* Access token obtained! $access_token contains the access token from now on.
				 Obtain user data and sign him up. */
				
				$userdata=json_decode(curl("https://graph.facebook.com/me?access_token=$access_token"));
				
				/* Facebook ID and email address of person trying to log in */
				$fb_id=$userdata->id;
				$fb_email=$userdata->email;
				
				/* Checking is above facebook_id or email address already exists in the database... */
				$id_check=mysql_query("SELECT user_id,username FROM userinfo WHERE facebook_id='$fb_id'");
				$email_check=mysql_query("SELECT user_id,username FROM userinfo WHERE email='$fb_email'");
				
				/* If facebook_id is not in db...*/
				if(mysql_num_rows($id_check)==0 )
				{
					/* .. and email address is also not registered... */
					if(mysql_num_rows($email_check)==0)
					{
						/* ... then this is a fresh new user. */
								
						/* Generate unique id. */
						$unique_id=getUniqueId($userdata->id);
						
						$result=mysql_query("INSERT INTO userinfo 
								(unique_id,facebook_id,username,name,city,gender,email,dob,first_login_time,last_login_time,profile_pic) 
								VALUES(
									'".getUniqueId($userdata->id)."',
									'".$userdata->id."',
									'".$userdata->username."',
									'".$userdata->name."',
									'".$userdata->location->name."',
									'".$userdata->gender."',
									'".$userdata->email."',
									'".$userdata->birthday."',
									'".time()."',
									'".time()."',
									'https://graph.facebook.com/".$userdata->id."/picture'
								)
							    ");
							    
						/* Get auto-generated user ID */
						$user_id=mysql_insert_id();
						
						/* User signed up. Set fb_access cookie to true*/
						setcookie('fb_access','yes',time()+60*60*24*30,'/','foodiescompass.com');
						
						/* Direct fb_login allowed from only this id. */
						setcookie('fb_access_id',md5($fb_id.SALT1),time()+60*60*24*30,'/','foodiescompass.com');
						
						/* Log him in and redirect to home page. */
						start_session($user_id,$userdata->username);
						header("Location: ".get_page_url('home'));
					}
					else if(mysql_num_rows($email_check)==1)
					{
						/* Email id previously registered but no facebook_id exists. Update field facebook_id for the user. */
						mysql_query("UPDATE userinfo SET facebook_id='$fb_id', last_login_time='".time()."' WHERE email='$fb_email'");
						
						/* Enable direct_fb_login from next time */
						setcookie('fb_access','yes',time()+60*60*24*30,'/','foodiescompass.com');
						
						/* Direct fb_login allowed from only this id. */
						setcookie('fb_access_id',md5($fb_id.SALT1),time()+60*60*24*30,'/','foodiescompass.com');
						
						/* Get userdata assosiated with this email address */
						$userdata_array=mysql_fetch_array($email_check);
						
						/* Start session for user */
						start_session($userdata_array['user_id'],$userdata_array['username']);
						
						/* Redirect to home page */
						header("Location: ".$_SESSION["current_page"]);
						
					}
					else
					{
						/* Duplicate email id's are not allowed. */
					}
				}
				else
				{
					/* If user has logged in before using facebook, we already have his data. */
					$user_arr=mysql_fetch_array($id_check);
					
					/* Start session. */
					start_session($user_arr['user_id'],$user_arr['username']);
										
					/* Allow Facebook direct login from next time */
					setcookie('fb_access','yes',time()+60*60*24*30,'/','foodiescompass.com');
					
					/* Direct fb_login allowed from only this id. */
					setcookie('fb_access_id',md5($fb_id.SALT1),time()+60*60*24*30,'/','foodiescompass.com');
					
					/* Redirect to home page */
					header("Location: ".$_SESSION["current_page"]);
					
				}
				
			}
			
		}
		
		/* Curl error. */
		else
		{
			echo "Problem connecting with Facebook servers.";
		}
	}

?>