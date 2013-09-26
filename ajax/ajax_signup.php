<?php

	require_once("../include/connection.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/config.inc.php");
	require_once("../include/flags.inc.php");
	require_once("../include/session.inc.php");

	$signup_errors;
	
	/* Clean get variables */
	$_GET['signup_name']=mysql_real_escape_string($_GET['signup_name']);
	$_GET['signup_email']=mysql_real_escape_string($_GET['signup_email']);
	$_GET['signup_pswd1']=md5(mysql_real_escape_string($_GET['signup_pswd1']));
	$_GET['signup_pswd2']=md5(mysql_real_escape_string($_GET['signup_pswd2']));
	$_GET['signup_age']=mysql_real_escape_string($_GET['signup_age']);
	$_GET['signup_sex']=mysql_real_escape_string($_GET['signup_sex']);
	$_GET['signup_city']=mysql_real_escape_string($_GET['signup_city']);

	/* Check if one or more fields are empty. */
	if( $_GET['signup_name']==SIGNUP_NAME_DEFAULT_VAL || $_GET['signup_email']==SIGNUP_EMAIL_DEFAULT_VAL || $_GET['signup_pswd1']==SIGNUP_PSWD1_DEFAULT_VAL || $_GET['signup_pswd2']==SIGNUP_PSWD2_DEFAULT_VAL|| $_GET['signup_age']==SIGNUP_AGE_DEFAULT_VAL|| $_GET['signup_sex']==""|| $_GET['signup_city']==SIGNUP_CITY_DEFAULT_VAL )
	{		
		echo "{'status':'failure','err_code':[", SIGNUP_FORM_INCOMPLETE,"]}";
	}
	else
	{
		// Check validity of form fields.
		
		// Passwords must match.
		$signup_errors[SIGNUP_PSWD_MISMATCH] = ($_GET['signup_pswd1']!=$_GET['signup_pswd2']);
		
		// Age must be numeric
		$signup_errors[SIGNUP_AGE_NAN]= !is_numeric($_GET['signup_age']);
		
		// Age must be greater than 14
		$signup_errors[SIGNUP_AGE_INVALID]= $_GET['signup_age']<MIN_AGE;
		
		// Email address should be valid
		$signup_errors[SIGNUP_EMAIL_INVALID]= (preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^',$_GET['signup_email'])===0);
		
		// Email should me unique 
		$signup_errors[SIGNUP_EMAIL_DUPLICATE]= mysql_num_rows( mysql_query("SELECT * FROM userinfo WHERE email='$_GET[signup_email]'"))>0;
		
		// Username should be unique
		$signup_errors[SIGNUP_USERNAME_DUPLICATE]= mysql_num_rows( mysql_query("SELECT * FROM userinfo WHERE username='$_GET[signup_name]'"))>0;
		
		// Must have agreed to signup terms.
		$signup_errors[SIGNUP_TERMS_DISAGREE]= !isset($_GET['signup_terms']);

		$form_correctness=true;
		$error_count=0;
		
		foreach( $signup_errors as $error ){
			$form_correctness=($form_correctness)&(!$error);
		}
		
		if($form_correctness)
		{
			$unique_id=getUniqueId(rand());
			
			// Post data to database. 
			$sql= "INSERT INTO userinfo (unique_id,username,password,email,dob,gender,city,first_login_time,last_login_time) 
			VALUES(	'$unique_id','$_GET[signup_name]','$_GET[signup_pswd1]','$_GET[signup_email]','$_GET[signup_age]','$_GET[signup_sex]','$_GET[signup_city]','".time()."','".time()."')";
			
			$result = mysql_query($sql);
			$insert_id = mysql_insert_id();
			if($result)
			{
				// Start session. 
				start_session($insert_id,$_GET['signup_name']);
				echo "{'status':'success','err_code':[",SIGNUP_SUCCESS,"]}";
				
				/* Send email confirmation. */
				//include('../mail/postmaster.php');
				//post_mail( $_GET[signup_email],SIGNUP_MAILER,array('name'=>$_GET['signup_name']) );
			}
			else
			{
				echo "{'status':'failure','err_code':[",SIGNUP_DB_ERROR,"]}";
			}
		}
		else
		{
			/* There are some errors. */
			$errors="";
			for($i=0;$i<count($signup_errors);$i++)
			{
				if($signup_errors[$i])
				{
					$errors.=$i.",";
				}
			}
			echo "{'status':'failure','err_code':[",rtrim($errors,","),"]}";
		}
		
	}