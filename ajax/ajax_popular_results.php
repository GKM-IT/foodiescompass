<?php

/**
 * Returns popular results for a given location
 *
 * Note: Expects 'latitude' and 'longitude' cookies are set.
 * 
 * Returns JSON string in the format { "status": <status_code> , ["output": <html_output>] , ["count": <num_of_results_returned>] }
 * 
 * @author  Aakash S Bhowmick
 * @created  17th December 2012
 * 
 */

/* Includes */
require_once("../include/config.inc.php");
require_once("../include/lib.inc.php");
require_once("../include/session.inc.php");
require_once("../include/connection.inc.php");
require_once("../include/flags.inc.php");

/* Defines */
define('LATLANG_MARGIN',2);	// Latitudes and longitudes are searched within this margin
define('NUM_POPULAR_RESULTS',6);	// NUmber of results to return

$lat_max=$_COOKIE['latitude'] + LATLANG_MARGIN;
$lat_min=$_COOKIE['latitude'] - LATLANG_MARGIN;
$lng_max=$_COOKIE['longitude'] + LATLANG_MARGIN;
$lng_min=$_COOKIE['longitude'] - LATLANG_MARGIN;


/* Fetches results between the given lat-lang margins, sorted by number of likes */
$result=mysql_query("SELECT food_items.food_items_id, food_items.dish_name,food_items.dish_image,restaurant.res_name, restaurant.res_address, restaurant.res_city
					FROM food_items JOIN restaurant ON food_items.rest_id=restaurant.rsID 
					WHERE (food_items.food_lat BETWEEN $lat_min AND $lat_max) AND (food_items.food_lng BETWEEN $lng_min AND $lng_max) 
					ORDER BY food_items.likes DESC LIMIT 0,".NUM_POPULAR_RESULTS);

$num_rows = mysql_num_rows($result);

if($num_rows == 0)
{
	$json_to_echo='{ "status": '.SEARCH_ERROR.' }';
}
else
{
	$json_to_echo='{ "status" : '.SUCCESS.' , "output" : ';
	ob_start();		// Start output-buffering
	while($food=mysql_fetch_array($result))
	{
		$food_url=get_page_url("food",array("eid"=>$food["food_items_id"]));
?>
	<div id="food_item">
		<div id="food_pic">
			<a href="<?php echo $food_url; ?>" style="border:none;" >
				<img src="<?php echo BASE_URL.'/'.IMG_DIR.'tn1_'.$food["dish_image"]; ?>" >
			</a>
		</div>
		<div id="food_about">
			<h1 class="food_name">
				<a href="<?php echo $food_url; ?>" style="border:none;" ><?php echo $food["dish_name"]; ?></a>
			</h1>
			<h2 class="food_place_mp"><?php echo $food["res_name"].','.$food["res_address"].','.$food["res_city"]; ?></h2>
				<a href="<?php echo $food_url; ?>" style="border:none;" >
					<img src="images/button_find_out_more.png">
				</a>
			</a>
		</div>
		<div class="clearfix"></div>
	</div>
<?php
	}
	
	$output=ob_get_contents();
	$output=trim($output);
	ob_end_clean();
	$output=addslashes($output);
	$output=preg_replace('/\s\s+/', ' ', $output);	//Remove new-line characters
	
	$json_to_echo.='"'.$output.'" , "count" : '.$num_rows.'}';
}

echo $json_to_echo;


?>