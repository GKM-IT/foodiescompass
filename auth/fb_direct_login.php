<?php
	/* This page is now secure from session hijacking after addition of 
	 * a security cookie 'fb_access_id'. Direct facebook login is not allowed unless
	 * the cookie matches the desired hash.
	 */
	
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/config.inc.php");
	
	//No login if someone is already logged in
	if(!isset($_SESSION['id']))
	{
		if( $_COOKIE['fb_access']=='yes' && $_COOKIE['fb_access_id']!='' && $_COOKIE['fb_access_id']==md5($_GET['id'].SALT1) )
		{
			/* If user is currently logged in using Facebook. */
			if(isset($_GET['id']))
			{
				$_GET['id']=mysql_real_escape_string($_GET['id']);
				$result=mysql_query("SELECT username FROM userinfo WHERE facebook_id='$_GET[id]'");
				
				
				if(mysql_num_rows($result)==1)
				{
					$data_array=mysql_fetch_array($result);
					start_session($_GET['id'],$data_array['username']);
					
					/* Reset fb_acess cookie */
					setcookie('fb_access','yes',time()+60*60*24*30,'/','foodiescompass.com');
					
					/* Reset security cookie */
					setcookie('fb_access_id',md5($_GET['id'].SALT1),time()+60*60*24*30,'/','foodiescompass.com');
					
					echo "{'status':'success','msg':'Login successfull.'}";
				}
				else
				{
					echo "{'status':'failure','msg':'No such user exists.'}";
				}
			}
			else
			{
				echo "{'status':'failure','msg':'No fb id sent.'}";
			}
		}
		else
		{
			echo "{'status':'failure','msg':'No Facebook access.'}";
		}
	}
	else
	{
		echo "{'status':'failure', 'msg':'A session already exists.'}";
	}
?>