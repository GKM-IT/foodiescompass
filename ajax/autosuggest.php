<?php
require_once("../include/connection.inc.php");

	require_once("../include/config.inc.php");

/*
note:
this is just a static test version using a hard-coded countries array.
normally you would be populating the array out of a database

the returned xml has the following structure
<results>
	<rs>foo</rs>
	<rs>bar</rs>
</results>
*/
$aUsers = array();


		
	$input = mysql_real_escape_string(strtolower( $_GET['input'] ));
	$result = mysql_query("select distinct dish_name from food_items where dish_name LIKE '%$input%'");
	while($row = mysql_fetch_array($result))
	{
		array_push($aUsers, $row["dish_name"]);
	}
	$len = strlen($input);
	$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
	
	
	$aResults = array();
	$count = 0;
	
	if ($len)
	{
		for ($i=0;$i<count($aUsers);$i++)
		{
			// had to use utf_decode, here
			// not necessary if the results are coming from mysql
			//
			
				$count++;
				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars($aUsers[$i]), "info"=>htmlspecialchars($aInfo[$i]) );
			
			
			if ($limit && $count==$limit)
				break;
		}
	}
	
	
	
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\": \"\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	
	
?>