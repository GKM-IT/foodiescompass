<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
require_once('../../include/session.inc.php');
require_once('../../include/connection.inc.php');
require_once('twitteroauth.php');
require_once('../../include/config.inc.php');
require_once('../../include/lib.inc.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['twt_oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(TWITTER_APP_ID, TWITTER_APP_SECRET, $_SESSION['twt_oauth_token'], $_SESSION['twt_oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['twt_access_token'] = $access_token['oauth_token'];
$_SESSION['twt_access_token_secret'] = $access_token['oauth_token_secret'];

/* Remove no longer needed request tokens */
unset($_SESSION['twt_oauth_token']);
unset($_SESSION['twt_oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
	/* The user has been verified and the access tokens can be saved for future use */
  	$_SESSION['status'] = 'verified';
  
  	$new_conn = new TwitterOAuth(TWITTER_APP_ID, TWITTER_APP_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  	
  	$user_data=$new_conn->get('account/verify_credentials');
  	
  	$profile_data=$new_conn->get('users/show',array('screen_name'=>$user_data->screen_name));
  	
  	// Search for this twitter id in userinfo table
  	$query=sprintf("SELECT username,user_id FROM userinfo WHERE twitter_id='%s'",$profile_data->id);
  	$result=mysql_query($query);
  	
  	if($result)
  	{
  		if(mysql_num_rows($result)>0)
  		{
  			// User already signed up. Log him in
  			$results_array=mysql_fetch_array($result);
  			start_session($results_array['user_id'], $results_array['username']);
  			
  			// Redirect to home
  			header("Location: ".$_SESSION["current_page"]);
  		}
  		else
  		{
  			// First login attempt.
  			
  			// Create a unique id
  			$unique_id=getUniqueId($profile_data->id);
  			$time=time();
  			$query=sprintf("INSERT INTO userinfo (name,username,twitter_id,profile_pic,unique_id,first_login_time,last_login_time) VALUES('%s','%s','%s','%s','%s','%s','%s')",$user_data->name,$user_data->screen_name,$profile_data->id,$profile_data->profile_image_url, $unique_id, $time, $time);
  			$result=mysql_query($query);
  			
  			if($result)
  			{
  				start_session(mysql_insert_id(), $user_data->screen_name);
  				
  				// Redirect to home
  				header("Location: ".$_SESSION["current_page"]);
  			}
  			else
  			{
  				echo "Database error: Please try after sometime.";
  			}
  		}
  	}
  	else
  	{
  		echo "Database error: Please try after some time.";
  	}
  	
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: ./clearsessions.php');
}
?>