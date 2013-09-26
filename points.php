<?php 
/**
 * Define the points scheme of badging here.
 * 
 * @author Aakash S Bhowmick
 * @date 13th Dec 2012
 * 
 * 
 * 
 * ---Point scheme---
 * Every upload : +10
 * Every review : +2
 * 
 */


include 'include/flags.inc.php';
include 'include/session.inc.php';

if(is_logged_in())
{
	$query=mysql_query(sprintf("SELECT followers, follows, points, num_reviews, num_discoveries, num_pictures FROM userinfo WHERE user_id=%d",$_SESSION['id']));
	$userdata=mysql_fetch_array($query);
	
	
}
else
{
	echo "User not logged in.";
	error_log("User not logged in");
}

?>
