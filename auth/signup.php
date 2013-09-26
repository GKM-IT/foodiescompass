<?php

ini_set('include_path', '/home/foodies1/public_html/beta/include');
require_once("connection.inc.php");
require_once("lib.inc.php");
require_once("config.inc.php");

/*____________________SIGNUP CODE__________________________*/

if(isset($_POST['after_signup']))
{
	/* Check if one or more fields are empty. */
	if( $_POST['signup_name']=="Foodies Name" ||
		$_POST['signup_email']=="Email"	||
		$_POST['signup_pswd1']==""||
		$_POST['signup_pswd2']==""||
		$_POST['signup_age']==""||
		$_POST['signup_sex']==""||
		$_POST['signup_city']=="" ){
		
		$signup_errors['form_complete']['status']=false;
	}
	else
	{
		/* Check validity of form fields. */
		
		/* Passwords must match. */
		$signup_errors['password_match']['status'] = ($_POST['signup_pswd1']==$_POST['signup_pswd2']);
		
		/* Age must be numeric and >14 */
		$signup_errors['allowed_age']['status']= ($_POST['signup_age']>14);
		
		/* Email address should be valid */
		$signup_errors['valid_email']['status']= (preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^',$_POST['signup_email']));
		
		/* Email and username should me unique */
		$signup_errors['unique_email']['status']= mysql_num_rows( mysql_query("SELECT * FROM userinfo WHERE email='$_POST[signup_email]'"))==0;
		$signup_errors['unique_username']['status']= mysql_num_rows( mysql_query("SELECT * FROM userinfo WHERE username='$_POST[signup_name]'"))==0;
		
		/* Must have agreed to signup terms. */
		$signup_errors['agree_to_terms']['status']= isset($_POST['signup_terms']);

		foreach( $signup_errors as $error ){
			$signup_errors['all_correct']['status']&=$error[status];
		}
		
		if($signup_errors['all_correct']['status'])
		{
			$_POST['signup_name']=mysql_real_escape_string($_POST['signup_name']);
			$_POST['signup_email']=mysql_real_escape_string($_POST['signup_email']);
			$_POST['signup_pswd1']=md5(mysql_real_escape_string($_POST['signup_pswd1']));
			$_POST['signup_age']=mysql_real_escape_string($_POST['signup_age']);
			$_POST['signup_city']=mysql_real_escape_string($_POST['signup_city']);
			$unique_id=getUniqueId(mysql_insert_id());
			
			/* Post data to database. */
			$query="INSERT INTO userinfo (unique_id,username,password,email,age,gender,city) 
			VALUES(	'$unique_id','$_POST[signup_name]','$_POST[signup_pswd1]','$_POST[signup_email]','$_POST[signup_age]','$_POST[signup_sex]','$_POST[signup_city]')";
			error_log($query);
			$result= mysql_query($query);
			
			if($result)
			{
				/* Start session. */
				error_log('Database insertion successfull.');
				session_start();
				header("Location: ".get_base_url().get_page_url('home'));
			}
			else
			{
				error_log('MySQL Error:'.mysql_error());
			}
			
			
		}
		else{
			/* There are some errors. */
			
		}
		
	}
}

/*____________________ SIGN-IN CODE__________________________*/

if(isset($_POST['after_signin']))
{
	/* Check if both email and password are set*/
	if( $_POST['signin_email']!="Email" && $_POST['signin_pswd']!="") 
	{
		$test_email=mysql_real_escape_string($_POST['signin_email']);
		$test_pswd=md5( mysql_real_escape_string($_POST['signin_pswd']) );
		$result=mysql_query("SELECT * FROM userinfo WHERE email='$test_email' and password='$test_pswd'");
		
		if(mysql_num_rows($result)==1)
		{
			error_log('Here8');
			/* Login successful. */
			$user_data=mysql_fetch_array($result);
			start_session($user_data['id'], $user_data['username']);
			header("Location: home.php");
		}
		else if(mysql_num_rows($result)==0)
		{
			/* Login unsuccessful*/
			header("Location: index.php#login_popup?login_success=0");
		}
		
	}
}