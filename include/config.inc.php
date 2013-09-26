<?php
	/* Is site running on main server or local server ? */
	define('IS_LOCAL_SERVER',false);
	error_reporting(E_ALL ^ E_NOTICE);
	/* Site Base URL. */
	if(IS_LOCAL_SERVER)
		{
			define('BASE_URL_2','http://localhost/metadata/');
			define('BASE_URL','http://localhost/metadata');
		}
	else
		{
			define('BASE_URL','http://foodiescompass.com/beta');
			define('BASE_URL_2','http://foodiescompass.com/beta/');
		
		}
	
	/* MySql database parameters */
	if(IS_LOCAL_SERVER)
	{
		define('MYSQL_USERNAME','root');
		define('MYSQL_PASSWORD','');
		define('MYSQL_DB','foodies');
	}
	else
	{
		define('MYSQL_USERNAME','foodiesc');
		define('MYSQL_PASSWORD','Izmub27K37');
		define('MYSQL_DB','foodiesc_foodies');
	}
	
			
	/* Abs path of site base directory */
	if(IS_LOCAL_SERVER)
		define('ABS_PATH_TO_HOME','C:/wamp/www/metadata/');
	else
		define('ABS_PATH_TO_HOME','/home/foodiesc/public_html/beta/');
	
	/* Max pic upload size */
	define('MAX_UPLOAD_SIZE',1024*2000);
	
	/* Facebook App ID */
	define('FB_APP_ID','261281000641350');
	
	/* Facebook App secret */
	define('FB_APP_SECRET','9ef82773885bd67d477c4a63d17c7b86');
	
	/* Twitter App ID */
	define('TWITTER_APP_ID','rCNN356fnbgoxYHOm1Tzw');
	
	/* Twitter App Secret */
	define('TWITTER_APP_SECRET','BY1M2ZWIq1lTA7ci9rcvFlupCkPgTfqKjrY8LjBc');
	
	/* Twitter OAuth Callback (Look for this in your Twitter app settings) */
	define('TWITTER_OAUTH_CALLBACK','http://foodiescompass.com/beta/auth/twitter_oauth/callback.php');
	
	/* Food images directory */
	define('IMG_DIR','uploads/food_pics/');
	define('PR_DIR','uploads/profile_pics/');
	
	define('LOADER', '<div style="width:100px; background:#fff; padding:20px 50px; margin:0 40%"><img src="'.BASE_URL_2.'images/10.gif"><br><span class="para_type1 orange_text">Loading..</span></div>');
	/* Minimum age to signup */
	define('MIN_AGE',14);
	
	/* Salt1. Used for encryption of Facebook cookie id */
	define('SALT1','bj983un9373nfw983nf3j');
	
?>