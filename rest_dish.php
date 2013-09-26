<?php

	//sleep(5);
	require_once("include/config.inc.php");
	require_once("include/lib.inc.php");
	require_once("include/session.inc.php");
	require_once("include/connection.inc.php");
	require_once("include/flags.inc.php");
	

$rest = $_POST["rest_id"];

$page = $_POST["food_page"];
if(!$page) $page=1;


$food_items = array();

//SEARCH QUERIES
if(!$_POST["food_array"])
{
	
		$query="SELECT food_items_id from food_items where rest_id='$rest'";	
	//echo $query;
	$food_ids = array();
	$results = mysql_query($query);
	while( $row = mysql_fetch_array($results))
	{
		array_push($food_ids, $row["food_items_id"]);
	}
//print_r($food_ids);
	$total_results = sizeof($food_ids);
	 $string_id=implode(",", $food_ids);
}
else
{
	$food_ids = explode(",",$_POST["food_array"]);
	$string_id = $_POST["food_array"];
	$total_results = sizeof($food_ids);

}
echo '<div id="wait" ></div>';
	
	echo '
	<div id="map_result_left" style="float:left; width:400px;  margin-top:10px">';
echo '<input type="hidden" id="food_array" value="'.$string_id.'"/>';
$count =1;
$lat_array = array();
$lng_array =array();
$place_name =array();

if($total_results > 0)
{
	$start = ($page-1)*5;
	$end = ($page-1)*5 +5;
	if($end >= $total_results) $end = $total_results-1;
for( $k = $start ; $k <= $end; $k++)
{

$food_id = $food_ids[$k];
$result_food = mysql_query("select * from food_items where food_items_id='$food_id' ");
$food = mysql_fetch_array($result_food);
$rest_id = $food["rest_id"];

$result_rest = mysql_query("select * from restaurant where rsID='$rest_id' ");
$restr = mysql_fetch_array($result_rest);

array_push($lat_array, $restr["res_lat"]);
array_push($lng_array, $restr["res_lng"]);
array_push($place_name, $restr["res_name"]);

echo '<a href="'.get_page_url('food',array('eid'=>$food['food_items_id'])).'" style="text-decoration:none;"><div class="map_food_item" id="food_item'.$count.'" style="cursor:pointer; opacity:1" ">
	<div id="left" style="float:left; width:140px;">
		<img src="'.get_page_url("food_pic",array("fid"=>$food["food_items_id"], "size"=>"thumb_mini")).'" />
	</div>
	<div id="right" style="float:right; width:255px;">
		<h1 class="food_name">
			<a href="'.get_page_url('food',array('eid'=>$food['food_items_id'])).'" style="text-decoration:none;">
				<span style="color:#000000;text-decoration:none;">'.$food['dish_name'].'</span>
				
			</a>
		</h1>
		
		<div >';
		
		$str_like = render_like_pair($food['food_items_id']);
			echo $str_like;
			echo'
			<div style="width:180px; float:right;margin-top:5px;">
				<div class="likes">
				<div style="width:';
									
									$data1 = floor(1+70*$food['likes']/($food['likes']+$food['dislikes']+1));
									echo $data1;
												
												echo 'px; background:#3db54a; height:6px; float:left;"></div><br>'.$food['likes'].' Likes
												</div>
												<div class="dislikes" style="margin-left:5px;width:80px;">
													<div style="width:';
													$data2 = floor(1+70*$food['dislikes']/($food['likes']+$food['dislikes']+1));
													
													
													echo $data2.'px; background:#ed2224; height:6px;float:left;"></div><br>'.$food['dislikes'].' Dislikes
												</div>
											</div>
											<div class="clearfix"></div>
											
										</div>
									</div>
									<div class="clearfix"></div>
								</div></a>';
								$count++;
								
	//if($count >5) {$last_distance = $food_items[$food_id]; break;}

}

}
if($total_results > 40) $total_results =40; 
$total_pages= (int)($total_results/5);
if($total_results%5 !=0) $total_pages++;

echo '<div id="pagination">';
for($i=1; $i<=$total_pages;$i++)
{
echo '<a href="javascript:void(0)" ';
	if($page == $i) echo ' class="selected_page">';
	else
	{
		echo ' onclick="change_page('.$i.');">';
	}
	echo $i.'</a>';
}
	
echo '</div>
								
</div>';

	//}
	
	?>