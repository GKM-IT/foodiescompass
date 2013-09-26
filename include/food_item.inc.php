<?php

$query = mysql_query( "select dish_name, dish_url, cost, vegetarian, rest_id, dish_image,description from food_items where food_items_id = '$food_id'");
$food = mysql_fetch_array($query);
$rest_id = $food["rest_id"];
$query_rest = mysql_query( "select * from restaurant where rsID= '$rest_id'");
$restr = mysql_fetch_array($query_rest);

$check_restr =0;
$check_cost =0;

if($restr["res_address"] && ( $restr["res_city"] && $restr["res_state"] ))
{
	$check_restr = 1;
}

if($food["cost"] == 0)
{
	$check_cost = 1;
}

?>