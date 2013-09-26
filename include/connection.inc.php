<?php
require_once("config.inc.php");

if(!(mysql_connect('localhost',MYSQL_USERNAME,MYSQL_PASSWORD)))
{
	echo  "Could not connect to database server. ".mysql_error();
	exit;
}

if(!mysql_select_db(MYSQL_DB))
{
	echo "Could not connect to database. ".mysql_error();
	exit;
}

?>