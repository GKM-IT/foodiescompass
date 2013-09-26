<?php
	/* Performs an ajax 'like' operation. */
	
	/* Expects a food item 'eid'. */
	
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	require_once("../include/resize_img.inc.php");
	
	
	$name = mysql_real_escape_string($_POST["name"]);
	$user_id = mysql_real_escape_string($_POST["user_id"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$message = mysql_real_escape_string($_POST["message"]);
	$user_lat = mysql_real_escape_string($_POST["user_lat"]);
	$user_lng = mysql_real_escape_string($_POST["user_lng"]);
	$place = mysql_real_escape_string($_POST["place"]);
	$date = strtotime("now");
	
	$query=mysql_query("insert into message (name, email, message, user_id, lat, lng , place, date) VALUES ('$name', '$email', '$message', '$user_id', '$lat', '$lng' , '$place', '$date')");
	
	
	$to = "vishu.iitd@gmail.com";
	$subject = "Message Form";
	$message = $name.'<br>'.$email.'<br>'.$message.'<br>'.$user_id.'<br>'.$lat.'<br>'.$lng .'<br>'.$place.'<br>'.$date;
	$from = "contact@foodiescompass.com";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);
?>