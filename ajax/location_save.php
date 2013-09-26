<?php
	/* Performs an ajax 'like' operation. */
	
	/* Expects a food item 'eid'. */
	
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	
	
			// Check if user has already liked the item
	
	mysql_query("update restaurant set res_lat='$_GET[lat]', res_lng = '$_GET[lng]' where rsID='$_GET[rid]' ");
	
	mysql_query("insert into changes (old, new, change_id, user_id, rest_id) values ('','$_GET[lat]', '1' , '$_GET[eid]', '$_GET[rid]' )");
	
	mysql_query("insert into changes (old, new, change_id, user_id, rest_id) values ('','$_GET[lng]', '2' , '$_GET[eid]', '$_GET[rid]' )");
	
				//echo $_GET['rid'];
?>