<?php

//sleep(5);
require_once("include/config.inc.php");
require_once("include/lib.inc.php");
require_once("include/session.inc.php");
require_once("include/connection.inc.php");
require_once("include/flags.inc.php");
function distance($lat1, $lon1, $lat2, $lon2, $unit) { 

  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}


$food = $_POST["food_name"];
$place = explode(',',$_POST["food_place"]);
//echo $food.' '.$place.' '.FOOD_SEARCH_DEFAULT_VAL;
//FOOOOD
$split_words = explode( " " , $food );
$look_for_food=''; $c1=0; $string_search_food ='';
foreach ($split_words as $word)
{
if(preg_match("/[A-Z  | 0-9 | a-z]+/", $word ) && strlen($word) >1){
$c1++;
if($c1 == 1)
{
$string_search_food_main =$string_search_food_main.'+'.$word.'*';
$string_search_food =$string_search_food.''.$word.'';
$look_for_food = $look_for_food.$word;
}
else
{
$string_search_food_main =$string_search_food_main.' +'.$word.'*';
$string_search_food =$string_search_food.' '.$word.'';
$look_for_food = $look_for_food.' '.$word;
}
$word = strtolower($word);
}
}

//PLACE
$split_words = explode( " " , $place[0] );
$look_for_place=''; $c1=0; $string_search_place ='';
foreach ($split_words as $word)
{
if(preg_match("/[A-Z  | 0-9 | a-z]+/", $word ) && strlen($word) >1){
$c1++;
if($c1 == 1)
{
$string_search_place =$string_search_place.''.$word.'';
$look_for_place = $look_for_place.$word;
}
else
{
$string_search_place =$string_search_place.' '.$word.'';
$look_for_place = $look_for_place.' '.$word;
}
$word = strtolower($word);
}
}

//locatility
$locality= $place[1];
$split_words = explode( " " , $locality );
$look_for_food_locality=''; $c1=0; $string_search_food_locality ='';
foreach ($split_words as $word)
{
if(preg_match("/[A-Z  | 0-9 | a-z]+/", $word ) && strlen($word) >1){
$c1++;
if($c1 == 1)
{
$string_search_food_locality =$string_search_food_locality.''.$word.'';
$look_for_food_locality = $look_for_food_locality.$word;
}
else
{
$string_search_food_locality =$string_search_food_locality.' '.$word.'';
$look_for_food_locality = $look_for_food_locality.' '.$word;
}
$word = strtolower($word);
}
}


$user_lat =$_POST["user_lat"];

$user_lng =$_POST["user_lng"];
$user_place = $_POST["user_place"];

//USER PLACE
$split_words = explode( " " , $user_place );
$look_for_user_place=''; $c1=0; $string_search_user_place ='';
foreach ($split_words as $word)
{
if(preg_match("/[A-Z  | 0-9 | a-z]+/", $word ) && strlen($word) >1){
$c1++;
if($c1 == 1)
{
$string_search_user_place =$string_search_user_place.''.$word.'';
$look_for_user_place = $look_for_user_place.$word;
}
else
{
$string_search_user_place =$string_search_user_place.' '.$word.'';
$look_for_user_place = $look_for_user_place.' '.$word;
}
$word = strtolower($word);
}
}


$page = $_POST["food_page"];
if(!$page) $page=1;

if(!$user_lat) { $user_lat = $_POST["food_lat"];$user_lng =$_POST["food_lng"];$user_place = $_POST["food_place"];}

$food_items_main = array();
$food_items_main_like = array();
$food_items = array();
$food_items_like = array();
$flag=0;
//SEARCH QUERIES
if(!$_POST["food_array"] && !$_POST["food_array_main"])
{
if($food == FOOD_SEARCH_DEFAULT_VAL && ($place[0] == FOOD_PLACE_DEFAULT_VAL || $place[0]==''))
{
	$query="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(res_name, res_address, res_city) against ('$string_search_user_place' IN BOOLEAN MODE) order by likes limit 40";
	//echo $query;
	$results = mysql_query($query);
	$num_results=mysql_num_rows($results);


	$query2="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID order by likes limit ".(40-$num_results)." ";
	//echo $query2;
	$results2 = mysql_query($query2);

}


else if($food == FOOD_SEARCH_DEFAULT_VAL && ($place[0] != FOOD_PLACE_DEFAULT_VAL && $place[0]!=''))
{
	//echo "YESSS3";
	$query="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(res_name, res_address, res_city) against ('$string_search_place' IN BOOLEAN MODE) order by likes desc limit 40";
	//echo $query;
	$results = mysql_query($query);
	$num_results=mysql_num_rows($results);
 //echo "initial:".$num_results;
 if($string_search_food_locality)
	$query2="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(res_name, res_address, res_city) against ('$string_search_food_locality' IN BOOLEAN MODE) order by likes desc limit 40";
	else
	$query2="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID order by likes desc limit 40";
	
	//echo $query2;
	$results2 = mysql_query($query2);

}


else if($food != FOOD_SEARCH_DEFAULT_VAL && ($place[0] == FOOD_PLACE_DEFAULT_VAL || $place[0]==''))
{
	//echo "YESSS3";
	$query="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(dish_name) against ('$string_search_food_main' IN BOOLEAN MODE) ORDER BY likes desc limit 40";
	//echo $query;
	$results = mysql_query($query);
	$num_results=mysql_num_rows($results);

	if($num_results < 40) {
	$query2="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(dish_name) against ('$string_search_food' IN BOOLEAN MODE) order by likes desc limit 40";
	//echo $query2;
	$results2 = mysql_query($query2); }
}
else
{
	//echo "YESSS4";
	$query="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(dish_name) against ('$string_search_food_main' IN BOOLEAN MODE) AND match(res_name, res_address, res_city) against ('$string_search_place' IN BOOLEAN MODE) order by likes desc limit 40";
	//echo $query;
	$results = mysql_query($query);
	$num_results=mysql_num_rows($results);

	if($num_results < 40)
	$query2="SELECT food_items.food_items_id, food_items.dish_name,food_items.likes, restaurant.res_lat, restaurant.res_lng, restaurant.res_city, restaurant.res_name, restaurant.res_address FROM food_items inner join restaurant on food_items.rest_id = restaurant.rsID where match(dish_name) against ('$string_search_food_main' IN BOOLEAN MODE) OR match(res_name, res_address, res_city) against ('$string_search_place' IN BOOLEAN MODE) order by likes desc limit 40";
	//echo $query2;
	$results2 = mysql_query($query2);



}
$food_ids_main =array();
//ARRAY 1
while( $row = mysql_fetch_array($results))
{
array_push($food_ids_main, $row["food_items_id"]);
if($row["res_lat"]){
$distance = distance( (double)$row["res_lat"], (double)$row["res_lng"], $user_lat, $user_lng, "k" );
//if($distance <20)
$food_items_main[$row["food_items_id"]] = $distance;
}
else
{
$food_items_main[$row["food_items_id"]] = '80000';
}
array_push($food_items_main_like,$row["food_items_id"]);
}

//ARRAY 2
if($results2) {
while( $row = mysql_fetch_array($results2))
{
if(!in_array($row["food_items_id"],$food_items_main_like)) {
if($row["res_lat"]){
$distance = distance( (double)$row["res_lat"], (double)$row["res_lng"], $user_lat, $user_lng, "k" );
if($distance ==0) $distance=0.01;
$food_items[$row["food_items_id"]] = $distance;
}
else
{
$food_items[$row["food_items_id"]] = '80000';
}
array_push($food_items_like,$row["food_items_id"]);
}
}
}

//SORTING THE MAIN FINAL ARRAY
if($flag == 0)asort($food_items_main);

$food_ids = array();

$string_id_main='';
$count_id=0;
while ($food_id = current($food_items_main)) {
array_push($food_ids, key($food_items_main));
  
if($count_id==0) $string_id_main=$string_id_main.key($food_items_main);
else $string_id_main=$string_id_main.','.key($food_items_main);
$count_id++;
next($food_items_main);
}

$total_results_main = sizeof($food_ids);
$string_id_main_like = implode(',',$food_items_main_like);
// sorting the other array
if($flag == 0)asort($food_items);

$string_id='';
$count_id=0;
while ($food_id = current($food_items)) {
array_push($food_ids, key($food_items));
  
if($count_id==0) $string_id=$string_id.key($food_items);
else $string_id=$string_id.','.key($food_items);
$count_id++;
next($food_items);
}

$total_results = sizeof($food_ids);
$string_id_like = implode(',',$food_items_like);

}



// if arrays are already set
else
{
if($_POST["value"] == 'distance')
{

if($_POST["food_array"])$food_ids = explode(",",$_POST["food_array"]);
else $food_ids =array();
if($_POST["food_array_main"])$food_ids_main = explode(",",$_POST["food_array_main"]);
else $food_ids_main =array();
if($_POST["food_array"])$food_ids_rev = array_reverse($food_ids);
else $food_ids_rev =array();
if($_POST["food_array_main"])$food_ids_rev_main = array_reverse($food_ids_main);
else $food_ids_rev_main =array();
//print_r($food_ids);
}
else
{
if($_POST["food_array"])$food_ids = explode(",",$_POST["food_array_like"]);
else $food_ids =array();
if($_POST["food_array_main"])$food_ids_main = explode(",",$_POST["food_array_main_like"]);
else $food_ids_main =array();
if($_POST["food_array"])$food_ids_rev = array_reverse($food_ids);
else $food_ids_rev =array();
if($_POST["food_array_main"])$food_ids_rev_main = array_reverse($food_ids_main);
else $food_ids_rev_main =array();
//print_r($food_ids);
}
$food_ids = array_merge($food_ids_main,$food_ids);
$food_ids_rev = array_merge($food_ids_rev_main,$food_ids_rev);

//print_r($food_ids_rev);
$string_id = $_POST["food_array"];
$string_id_like = $_POST["food_array_like"];
$string_id_main_like = $_POST["food_array_main_like"];
$string_id_main = $_POST["food_array_main"];
$total_results = sizeof($food_ids);

}
echo '<div style="margin-bottom:10px; float:right">';
echo '<a href="javascript:void(0)" onclick="change_page(1,\'distance\',\'up\',1)" class="sorting '.(($_POST["sortby"]==1 || !$_POST["sortby"])?'selected':'').'">by location <img src="images/menu_triangle.png"></a>';
echo '<a href="javascript:void(0)" class="sorting">|</a>';
echo '<a href="javascript:void(0)" onclick="change_page(1,\'distance\',\'down\',2)" class="sorting '.(($_POST["sortby"]==2)?'selected':'').'">by location <img src="images/menu_triangle_dn.png"></a>';
echo '<a href="javascript:void(0)" class="sorting">|</a>';
echo '<a href="javascript:void(0)" onclick="change_page(1,\'likes\',\'up\',3)" class="sorting '.(($_POST["sortby"]==3)?'selected':'').'">by likes <img src="images/menu_triangle.png"></a>';
echo '<a href="javascript:void(0)" class="sorting">|</a>';
echo '<a href="javascript:void(0)" onclick="change_page(1,\'likes\',\'down\',4)" class="sorting '.(($_POST["sortby"]==4)?'selected':'').'">by likes <img src="images/menu_triangle_dn.png"></a>';
echo '</div>';
echo '<div class="clearfix"></div>';
echo '<div id="wait" ></div>';

echo '
<div id="map_result_left" style="float:left; width:400px;">';
echo '<input type="hidden" id="food_array" value="'.$string_id.'"/>';
echo '<input type="hidden" id="food_array_main" value="'.$string_id_main.'"/>';
echo '<input type="hidden" id="food_array_main_like" value="'.$string_id_main_like.'"/>';
echo '<input type="hidden" id="food_array_like" value="'.$string_id_like.'"/>';
$count =1;
$lat_array = array();
$lng_array =array();
$place_name =array();

if($total_results > 0)
{
$start = ($page-1)*5;
$end = ($page-1)*5 +5;
if($end >= $total_results) $end = $total_results;
//print_r($food_items);
for( $k = $start ; $k < $end; $k++)
{

if($_POST["updown"] == 'down') $food_id = $food_ids_rev[$k];
else $food_id = $food_ids[$k];

$result_food = mysql_query("select * from food_items where food_items_id='$food_id' ");
$food = mysql_fetch_array($result_food);
$rest_id = $food["rest_id"];

$result_rest = mysql_query("select * from restaurant where rsID='$rest_id' ");
$restr = mysql_fetch_array($result_rest);

array_push($lat_array, $restr["res_lat"]);
array_push($lng_array, $restr["res_lng"]);
array_push($place_name, $restr["res_name"]);

echo '<a href="'.get_page_url('food',array('eid'=>$food['dish_url'])).'"><div class="map_food_item" id="food_item'.$count.'" style="cursor:pointer;';
if(in_array($food['food_items_id'],$food_ids_main)) echo "background:#fff; opacity:1";

echo '" onmouseover="set_icon(marker'.$count.','.$count.', '.$restr["res_lat"].', '.$restr["res_lng"].' )" onmouseout="remove_icon(marker'.$count.')">
<div id="left" style="float:left; width:140px;">
<img src="'.IMG_DIR.'/tn1_'.$food["dish_image"].'" />
</div>
<div id="right" style="float:right; width:255px;">
<h1 class="food_name">
<a href="'.get_page_url('food',array('eid'=>$food['food_items_id'])).'" style="text-decoration:none;">
<span style="color:#000000;text-decoration:none;">'.ucwords($food['dish_name']).'</span>
<font class="text_orange"> @ '.stripslashes($restr['res_name']).'</font>
</a>
</h1>
<div id="res_address">'.$restr["res_address"].', '.$restr["res_city"].'</div>
<div >';
$str_like = render_like_pair($food['food_items_id']);
echo $str_like;
echo'
<div style="width:180px; float:right;margin-top:5px;">
<div class="likes" id="like_'.$food['food_items_id'].'">
<div style="width:';
$data1 = floor(1+70*$food['likes']/($food['likes']+$food['dislikes']+1));
echo $data1;
echo 'px; background:#3db54a; height:6px; float:left;"></div><br>'.$food['likes'].' Likes<input type="hidden" id="initial_like_'.$food['food_items_id'].'" value="'.$food["likes"].'">
</div>
<div class="dislikes" id="dislike_'.$food['food_items_id'].'" style="margin-left:5px;width:80px;">
<div style="width:';
$data2 = floor(1+70*$food['dislikes']/($food['likes']+$food['dislikes']+1));
echo $data2.'px; background:#ed2224; height:6px;float:left;"></div><br>'.$food['dislikes'].' Dislikes <input type="hidden" id="initial_dislike_'.$food['food_items_id'].'" value="'.$food["dislikes"].'">
</div>
</div>
<div class="clearfix"></div>
<ul>
<li>
<span class="distance_data">
<span class="distance">';
if(!$_POST["food_array"])
{
if(in_array($food['food_items_id'],$food_ids_main))
$dis = $food_items_main[$food_id];
else
$dis = $food_items[$food_id];
if($dis < 80000) echo round($dis ,1).'</span>&nbsp;KM from current location';
else echo 'Location is not known';
}
else
{
if($restr["res_lat"])
echo round(distance( (double)$restr["res_lat"], (double)$restr["res_lng"], $user_lat, $user_lng, "k" ),1).'</span>&nbsp;KM from current location';
else
echo 'Location is not known';
}
echo '.
</span>
</li>
</ul>
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
echo ' onclick="change_page('.$i.', \'';
echo ($_POST["value"])? $_POST["value"] : 'distance';
echo '\',\'';
echo ($_POST["updown"])? $_POST["updown"] : 'up';
echo '\',';
echo (!$_POST["sortby"])? '1':$_POST["sortby"];
echo ');">';
}
echo $i.'</a>';
}
echo '</div>
</div>';

echo '
<div id="map_result_right" style="float:left; width:570px; margin-left:10px; ">
<div style="background:#faaf42; padding:20px; color:#fff; font-weight:bold; font-size:18px;">
<div style="width:280px; float:left;">You @ '.$user_place.'</div>
<div style="float:right;">
<input type="text" id="change_user_loc" placeholder ="Change Location" title="User Location" class="input1 searchfood" style="display:inline; width:200px; margin-top: -8px;"/>
</div>
<div class="clearfix"></div>
</div>
<div id="map_display">
</div>
<div id="map_display_bottom" style=" ">
<div style="float:right;">
<img src="images/map_icon_you.png" width="14" height="20"> You&nbsp;&nbsp;&nbsp;&nbsp; <img src="images/map_icon.png" width="14" height="20"> Your Food
</div>
<div class="clearfix"></div>
</div>
</div>

<script>
var autocomplete_user;
var icon1 = "images/map_icon.png";
var icon2 = "images/map_icon_you.png";
var map;
var marker;
autocomplete_user = new google.maps.places.Autocomplete(document.getElementById(\'change_user_loc\'), {
      
componentRestrictions: countryRestrict
    });
google.maps.event.addListener(autocomplete_user, \'place_changed\', function() {
place_changed_user();
});
function place_changed_user() {
    var place = autocomplete_user.getPlace();
  set_cookie(\'latitude\',place.geometry.location.lat(),7);
  set_cookie(\'longitude\',place.geometry.location.lng(),7);
  set_cookie(\'user_place\',place.name,7);
  
location.reload();

  }

var mapOptions = {
 center: new google.maps.LatLng('.$user_lat.','.$user_lng.'),
 zoom: 12,
 mapTypeId: google.maps.MapTypeId.ROADMAP
};
map = new google.maps.Map(document.getElementById("map_display"),mapOptions);
var myBounds = new google.maps.LatLngBounds(); 
var userlatlng = new google.maps.LatLng('.$user_lat.','.$user_lng.');
myBounds.extend(userlatlng);

// Set marker
marker = new google.maps.Marker({
map: map,
animation: google.maps.Animation.DROP,
position: new google.maps.LatLng('.$user_lat.','.$user_lng.'),
draggable:false,
title:\'You are here\',
icon: icon2
});
 
';
$count =1;
foreach($lat_array as $lat)
{
if($lat){
echo '
var p'.$count.' = new google.maps.LatLng('.$lat.','.$lng_array[$count-1].');
myBounds.extend(p'.$count.');
var marker'.$count.' = new google.maps.Marker({
 position: p'.$count.', 
 map: map,
 title: \''.$place_name[$count - 1].'\',
 clickable: true,
 animation: google.maps.Animation.DROP,
 draggable:true,
  });';
  
  }

  $count++;
if($count > 5) {}
  
  } 
echo'
map.fitBounds(myBounds);
map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
 new FullScreenControl(map));
</script>';
//}
?>