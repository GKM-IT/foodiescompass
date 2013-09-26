<?php

	require_once("include/config.inc.php");
	require_once("include/lib.inc.php");
	require_once("include/session.inc.php");
	require_once("include/connection.inc.php");
	require_once("include/flags.inc.php");
	require_once("include/functions.inc.php");
	

$page = $_POST["food_page"];
if(!$page) $page=1;


$food_items = array();

//SEARCH QUERIES
if(!$_POST["food_array"])
{
	$food_ids = array();
	$name_query='';
	if($_POST["query"] !=='' && $_POST["query"]!='Search Follower')
	{
		
		$name_query = mysql_real_escape_string($_POST["query"]);
		$split_words = explode( " " , $name_query );
		$look_for='';
		$c1=0;
		$string_search ='';
	
		foreach ($split_words as $word)
		{
						
			if(preg_match("/[A-Z  | 0-9 | a-z]+/", $word ) && strlen($word) >2){
			
			$c1++;
				if($c1 == 1)
				{
						$string_search =$string_search.''.$word.'';
						$look_for = $look_for.$word;
				}
				else
				{
					$string_search =$string_search.' '.$word.'';
					$look_for = $look_for.' '.$word;
				}
				$word = strtolower($word);
				
				
				}
		}
		
		if($c1==0)
		{
			
			echo "<div style='text-align: center;'><span class='result_found'><span class='result_query'>".$name_query."</span> is not a proper string to search. </span></div>";
			exit;
		}
		$query="SELECT unique_id from userinfo WHERE match(username, name,city) against( '$string_search' IN BOOLEAN MODE)";
		//echo $query;
	}
	else
	{
	$query="SELECT unique_id from userinfo order by points desc";
	}
	
	$results = mysql_query($query);
	while( $row = mysql_fetch_array($results))
	{
		array_push($food_ids, $row["unique_id"]);
	}
	$string_id = implode(",",$food_ids);
	$total_results = sizeof($food_ids);
	
	
	if($total_results==0)
	{
		$words=array();
		$query="SELECT name from userinfo ";
		$results = mysql_query($query);
		while( $row = mysql_fetch_array($results))
		{
			$split_words = explode( " " , $row["name"] );
		
			foreach ($split_words as $word)
			{
				if(in_array($word,$words))
				{
					
				}
				else
				{
					array_push($words, $word);
				
				}
			}
			
		}
		//print_r($words);
		$split_words = explode( " " , $name_query );
		$input = $split_words[0];
				// no shortest distance found, yet
			$shortest = -1;
			// loop through words to find the closest
			foreach ($words as $word) {
			// calculate the distance between the input word,
			// and the current word
			$lev = levenshtein($input, $word);
			// check for an exact match
			if ($lev == 0) {
			// closest word is this one (exact match)
			$closest = $word;
			$shortest = 0;
			// break out of the loop; we've found an exact match
			break;
			}
			// if this distance is less than the next found shortest
			// distance, OR if a next shortest word has not yet been found
			if ($lev <= $shortest || $shortest < 0) {
			// set the closest match, and shortest distance
			
			$closest = $word;
			$shortest = $lev;
			}
			}
			
			if($shortest >3)
			{
				echo "<div style='text-align: center;'><span class='result_found'>Sorry. No search results found for <span class='result_query'>".$name_query."</span></span></div>";
				exit;
			}
			else
			{
				$query="SELECT unique_id from userinfo WHERE match(username, name,city) against( '$closest' IN BOOLEAN MODE)";
				$results = mysql_query($query);
				while( $row = mysql_fetch_array($results))
				{
					array_push($food_ids, $row["unique_id"]);
				}
				$string_id = implode(",",$food_ids);
				$total_results = sizeof($food_ids);
	
				echo "<div style='text-align: center;'><span class='result_found'>Total search results found for <span class='result_query'>".$closest."</span>: ".$total_results."</span><br><span class='result_not_found'>No search results found for <span class='result_not_query'>".$name_query."</span></div>";
						
				$look_for=$closest;
			}
	
	
	
	
	
	
	
	
	}
	else
	{
		if($name_query)
		echo "<div style='text-align: center;'><span class='result_found'>Total search results found for <span class='result_query'>".$name_query."</span>: ".$total_results.'</span></div><br>';
	}
	
	
}
else
{
	
	$food_ids = explode(",",$_POST["food_array"]);
	$string_id = $_POST["food_array"];
	$total_results = sizeof($food_ids);

}

echo '<div id="wait_follow" align="center"></div>';
	echo '<input type="hidden" id="food_array" value="'.$string_id.'"/>';
	

if($total_results > 0)
{
	$max = 8;
	$start = ($page-1)*$max;
	$end = ($page-1)*$max +$max;
	if($end >= $total_results) $end = $total_results;
for( $k = $start ; $k < $end; $k++)
{

$user_id = $food_ids[$k];
$result_user = mysql_query("select * from userinfo where unique_id='$user_id' ");
$user = mysql_fetch_array($result_user);
$num_followers=($user["followers"]=="")? 0: count(explode(",",$user["followers"]));
$num_follows=($user["follows"]=="")? 0: count(explode(",",$user["follows"]));
$num_badges=($user["badges"]=="")? 0: count(explode(",",$user["badges"]));
echo '<div id="follower_item" style="width:450px;float:left; height:120px;">
								<div id="follower_pic" style="margin-top:5px;"><img src="'.get_page_url("profile_pic",array("user_id"=>$user["user_id"])).'"></div>
								<div id="follower_about">
								<h1 class="food_name follower_name" style="width:280px;">';
								
								if($name_query)
								{
									$name =$user["name"]?$user["name"]:$user["username"];
									$highlight_title = new highlight($name , $look_for);
									echo $highlight_title->output_text;
								}
								else
								{
									echo $user["name"]?$user["name"]:$user["username"];
								}
								
								echo '</h1>';
								
								
								if($user["about_me"])
								echo '<div class="italic_text" style="width:280px">'.limit_words($user["about_me"],80).'</div>';
								else echo '<br>';
								echo '<div class="clearfix"></div>';
								
								if($user["city"])
								{
								echo '
								<div style="float:left; margin:8px 10px 0 0; font-size:11px; font-family:arial;"><b>';
								if($name_query)
								{
									$highlight_title = new highlight($user["city"] , $look_for);
									echo $highlight_title->output_text;
								}
								else
								{
									echo $user["city"];
								}
								echo '</b></div>';
								}
								echo '
								<div style="float:left;"><a href="'.get_page_url("profile",array("uid"=>$user["unique_id"])).'"><img src="images/button_view_profile.png" style="margin-top:5px;"></a></div><div style="float:left; margin:5px 0 0 10px;">';
								if(is_logged_in()) render_follow_button($user["user_id"]);
								echo '</div>
									<div class="clearfix"></div>
									<p class="followers_stats" style="float:left; margin-top:2px;">
									'.$num_follows.' Following &nbsp;&nbsp;&nbsp;&nbsp;
									'.$num_followers.' Followers<br></p>
									<p>';
									
									echo '
									</p>
									

								</div>
								<div id="follower_badge" style="cursor:pointer">
								<div style="float:left">
									<div style="position: absolute;

margin: 24px 0 0 1px;
text-align: center;
width: 58px; color:#fff; font-weight:bold; font-size:11px;" class="tooltip" title="'.$user["points"].' Points">'.$user["points"].'</div>
									<img src="images/icon_star.png" class="tooltip" title="'.$user["points"].' Points">	
								</div>
								<div style="float:left; margin-top:15px; margin-left:3px">
								<div style="position: absolute;

margin: 13px 0 0 0px;
text-align: center;
width: 35px; color:#fff; font-weight:bold; font-size:11px;" class="tooltip" title="'.$num_badges.' Badges">'.$num_badges.'</div>
								<img src="images/profile_badge.png" class="tooltip" title="'.$num_badges.' Badges"></div>
								
								</div>
								
								<div class="clearfix"></div>
							</div>';
								$count++;
								
	//if($count >5) {$last_distance = $food_items[$food_id]; break;}

}

}

$total_pages= (int)($total_results/$max);
if($total_results%$max !=0) $total_pages++;
echo '<div class="clearfix"></div>';
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


	?>
<?php include 'include/tiptip.php'; ?>
<?php include 'include/only_login.php'; ?>