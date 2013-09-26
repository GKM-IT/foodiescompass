<?php
ini_set('include_path', '/home/foodiesc/public_html/beta/include');
require_once("config.inc.php");
require_once("phpmailer/class.phpmailer.php");
require_once("flags.inc.php");

function post_mail($to,$mail_flag,$params)
{
	/* The subject of the mail. */
	$subject;
	
	/* Mail body */
	$body;
	
	/* $params is an assosiative array. Encode it. */
	$encoded_params='';
	if($params)
	{
		foreach($params as $key=>$value)
		{
			$encoded_params.=$key."=".$value."&";
		}
		$encoded_params=rtrim($encoded_params,'&');
	}
	
	/* File containing the HTML content of the mail. */
	$content_file;
	
	if($mail_flag==SIGNUP_MAILER )
	{
		$subject="Signup successful";
		$content_file="/mail/contents.php";
	}
	
	/* Send mail */

	$mail= new PHPMailer();
	$mail->From="postmaster@foodiescompass.com";
	$mail->FromName="no-reply";
	$body=file_get_contents(BASE_URL.$content_file."?".$encoded_params);
	$body=eregi_replace("[\]",'',$body);
	$mail->Subject=$subject;
	$mail->AltBody="To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($body);
	$mail->AddAddress($to);
	$mail->AddAttachment("images/phpmailer.gif");             // attachment
	
	if(!$mail->Send()) {
	  error_log($mail->ErrorInfo);
	}
}

?>