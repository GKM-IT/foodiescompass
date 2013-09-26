<?php
	require_once('include/config.inc.php');
	require_once('include/lib.inc.php');
	$email = $_POST["email"];
	$pattern = '/^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/';
	preg_match($pattern, $email, $matches);
	if(empty($matches))
	{
		die("Invalid email address");
	}
	else
	{
		$sql = mysql_query("select user_id,username, name from userinfo where email='$email' and  (facebook_id='-1' and twitter_id='-1') ");
		if(mysql_num_rows($sql) > 0)
		{
			$user = mysql_fetch_array($sql);
			$new_pass = getUniquePassword();
			$to = $email;
			$subject = "Password Reset Request";
			$message = "Dear ".$user["username"].", ";
			$message = $message."Your password has been reset to ".$new_pass;
			$from = "info@foodiescompass.com";
			$headers = "From:" . $from;
			if(mail($to,$subject,$message,$headers))
			{
				$pass = md5($new_pass);
				mysql_query("update userinfo set password='$pass' where user_id='$user[user_id]'");
				die("Your Password has been reset and new password has been emailed to your email id.");
			}
			else
				die("Something wrong with email");
		}
		else
		{
			die("Your are using either facebook or twitter account with us, so password can not be changed.");
		}
	}
?>
