<?php

// Size limits for text fields
define('USERNAME_MIN_LENGTH',3);
define('USERNAME_MAX_LENGTH',40);
define('PASSWORD_MIN_LENGTH',4);
define('PASSWORD_MAX_LENGTH',100);

// Error codes
define('STATUS_OK',0);
define('UNAME_ILLEGAL_CHAR',1);
define('UNAME_DUPLICATE',2);
define('PSWD_ILLEGAL_LEN',1);
define('AGE_NOT_NUMERIC',1);
define('AGE_UNDERAGE',2);
define('EMAIL_INVALID',1);
define('EMAIL_DUPLICATE',2);

ini_set('include_path','/home/foodies1/public_html/beta/include');

require_once('lib.inc.php');
require_once("connection.inc.php");

class BasicAuth
{

	
	/* Returns an array, containing status codes for each data field. */
	function check_fields($data)
	{
		$status_array;
		
		foreach($data as $field=>$value)
		{
			if($field=='username')
			{
				/* 
				 * Rule1: Username should only contain allowed characters [A-Z][a-z][0-9][_] and have length between USERNAME_MIN_LENGTH & 
				 * USERNAME_MIN_LENGTH
				 */
				 
				if(preg_match("/^[A-Za-z0-9_.]{".USERNAME_MIN_LENGTH.",".USERNAME_MAX_LENGTH."}$/",$value)===0)
				{
					$status_array[$field]=UNAME_ILLEGAL_CHAR; 
				}
				// Rule2:User should not be registered before.
				else if(mysql_num_rows(mysql_query("SELECT * FROM userinfo WHERE username='$value'"))!=0)
				{
					$status_array[$field]=UNAME_DUPLICATE;
				}
				else
				{
					$status_array[$field]=STATUS_OK;
				}
			}
			else if($field=='password')
			{
				//Rule1: Password must be between PASSWORD_MIN_LEGNTH and PASSWORD_MAX_LENGTH
				if(strlen($value)<PASSWORD_MIN_LENGTH || strlen($value)>PASSWORD_MAX_LENGTH )
				{
					$status_array[$field]=PSWD_ILLEGAL_LEN;
				}
				
				else
				{
					$status_array[$field]=STATUS_OK;
				}
			}
			else if($field=='age')
			{
				//Rule1: Age must be numeric and >14
				if(!is_numeric($value))
				{
					$status_array[$field]=AGE_NOT_NUMERIC;
				}
				else if($value<14)
				{
					$status_array[$field]=AGE_UNDERAGE;
				}
				else
				{
					$status_array[$field]=STATUS_OK;
				}
			}
			else if($field=='email')
			{
				//Rule1: Email must be valid
				if(preg_match('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^',$value)===0)
				{
					$status_array[$field]=EMAIL_INVALID;
				}
				//Rule2: Email must not be already registered
				else if(mysql_num_rows(mysql_query("SELECT * FROM userinfo WHERE email='$value'"))!=0)
				{
					$status_array[$field]=EMAIL_DUPLICATE;
				}
				else
				{
					$status_array[$field]=STATUS_OK;
				}
				
			}
			
		}
		
		return $status_array;
	}
	/* End of check_fields(). */
	
	

			
	/* Attempts signup. Accepts an array with keys as form fields and values as data values. 
	 * Returns: True on success, False on database error, and a status_array when 'data' array does not contain allowed data.
	 * Note: '===' must be used here.
	 */
	function signup($data_array)
	{
		$status_array=$this->check_fields($data_array);
		foreach($status_array as $value)
		{
			if($value!=STATUS_OK)
			{
				return $status_array;	// Invalid data
			}
		}
		
		$sql_fields="";
		$sql_data="";
		
		foreach($data_array as $key=>$value)
		{
			$sql_fields.=$key.",";
			if($key=='password')
			{
				$sql_data.="'".mysql_real_escape_string(md5($value))."',";
			}
			else
			{
				$sql_data.="'".mysql_real_escape_string(trim($value))."',";
			}
		}
				
		/* Add unique_id field. */
		
		$sql_fields.="unique_id";
		$sql_data.="'".getUniqueId()."'";		
		
		echo $sql_fields.'<br/>';
		echo $sql_data."<br/>";
		
		/* Database action. */
		$query="INSERT INTO userinfo ($sql_fields) VALUES($sql_data)";
		return mysql_query($query);
	}
	/* End of signup() */
	
	
	
	
	/* Attempts to verify login data.
	 * Returns true on success, false on failure.
	 */
	 	
	
} // End of class
?>