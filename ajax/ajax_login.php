<?php
	require_once("../include/connection.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/config.inc.php");
	require_once("../include/flags.inc.php");
	require_once("../include/session.inc.php");
	
	if( $_GET['signin_email']==SIGNIN_EMAIL_DEFAULT_VAL || $_GET['signin_email']=='' || $_GET['signin_pswd']==SIGNIN_PSWD_DEFAULT_VAL || $_GET['signin_pswd']=='' )
	{
		echo "{'status':'failure','err_code':",SIGNIN_FORM_INCOMPLETE,"}";
	}
	else
	{
		/* Search database */
		$_GET['signin_email']=mysql_real_escape_string($_GET['signin_email']);
		$_GET['signin_pswd']=md5(mysql_real_escape_string($_GET['signin_pswd']));
		
		$result=mysql_query("SELECT * FROM userinfo WHERE email='$_GET[signin_email]' AND password='$_GET[signin_pswd]'");
		if(mysql_num_rows($result)>0)
		{
			/* Login successfull */
			
			$data_array=mysql_fetch_array($result);
			
			/* Update last_login_time */
			mysql_query("UPDATE userinfo SET last_login_time='".time()."' WHERE user_id=$data_array[user_id]");
			
			/* Store session variables */
			start_session($data_array["user_id"],$data_array["username"]);
			echo "{'status':'success'}";
		}
		else
		{
			echo "{'status':'failure','err_code':",SIGNIN_VALIDATION_ERR,"}";
		}
		
	}
?>