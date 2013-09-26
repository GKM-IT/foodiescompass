<?php

	require_once("../include/session.inc.php");

	
	/* Explicit log out. Don't allow a direct fb login next time */
	setcookie('fb_access','no',time()+60*60*24*30,'/','foodiescompass.com');
	
	/* Unset security cookie */
	setcookie('fb_access_id','',time()-60*60*24*30,'/','foodiescompass.com');
	
	end_session();
	
	/* Redirect to home page. */
	header("Location: ".$_SESSION["current_page"]);
?>