<?php

/**
 * SESSION variables used by the script
 * 
 * id			: User id of the logged in user
 * username		: Username of logged in user
 * profile_pic	: Profile pic of the user
 * unique_id	: Unique id of the user
 * follows		: <array> Follows list of the logged in user 
 * 
 */
	require_once('connection.inc.php');
	
	/* Make the session variables available across all domains
	 * Added to retain session variables irrespective of whether 
	 * www.foodiescompass.com or foodiescompass.com was visited.
	 */
	session_set_cookie_params(0, '/', '.foodiescompass.com');
	
	// Start session
	session_start();
	
	/*
	 * Starts a new session. Username parameter is optional
	 */
	function start_session($user_id,$username=false)
	{
		$_SESSION['id']=$user_id;
				
		$result=mysql_query("SELECT profile_pic,unique_id,username,follows FROM userinfo WHERE user_id='$user_id'");
		$user_data=mysql_fetch_array($result);
		
		$_SESSION['username']=$user_data['username'];
		$_SESSION['profile_pic']=$user_data['profile_pic'];			
		$_SESSION['unique_id']=$user_data['unique_id'];
		
		/*
		 * Added to lessen number of SQL queries per page render.
		 * Added: 17/12/2012
		 */
		$_SESSION['follows']=explode(",",$user_data['follows']);
	}
	/*
	 * Destroys and unsets all session variables to end current session. 
	 */
	function end_session()
	{
		session_destroy();
		session_unset();
	}
	
	function is_logged_in()
	{
		if(isset($_SESSION['username']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function logged_userid()
	{
		if(is_logged_in())
		{
			return $_SESSION['id'];
		}
		else
		{
			return false;
		}
	}
	
	function logged_username()
	{
		if(is_logged_in())
		{
			return $_SESSION['username'];
		}
		else
		{
			return false;
		}
	}
	
	/*** Just for testing ***/
	//start_session('1651','aakash.bhowmick');
?>