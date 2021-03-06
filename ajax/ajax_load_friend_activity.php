<?php
/**
 * Loads more activity on profile.php
 * 
 * @author Aakash S Bhowmick
 * @copyright FoodiesCompass
 * 
 * @var start_from The point from where to return the activities 
 * @var user_id User id of the user whose profile is being viewed
 * 
 * @return A json string in the form {'status': -1, 'output': '...some html code....' , 'count': 'number of results returned' }
 */ 

require_once("../include/config.inc.php");
require_once("../include/lib.inc.php");
require_once("../include/session.inc.php");
require_once("../include/connection.inc.php");
require_once("../include/flags.inc.php");

// Clean strings
$_GET['user_id']=mysql_real_escape_string($_GET['user_id']);
$_GET['start_from']=mysql_real_escape_string($_GET['start_from']);
$_GET['users']=mysql_real_escape_string($_GET['users']);

// Make query
$query=sprintf("SELECT * FROM activity WHERE user_id IN (%s) and activity_id IN (1,2,3,4,7,8) ORDER BY date DESC LIMIT %d,%d",$_GET['users'],$_GET['start_from'],NUM_ACTIVITIES_SHOWN_AT_A_TIME);
$result = mysql_query($query);
$num_results = mysql_num_rows($result);

if($num_results==0)
{
	$json='{ "status": "'.NO_MORE_ACTIVITES_ERROR.'" }';
}
else
{
	$json='{ "status": '.SUCCESS.' ,';
	ob_start();
	
	while($activity_data=mysql_fetch_array($result))
	{
		$query_user=mysql_query("SELECT name,unique_id,username FROM userinfo WHERE user_id=$activity_data[user_id]");
		$user_data_pre=mysql_fetch_array($query_user);
		$name = $user_data_pre["name"]?$user_data_pre["name"]:$user_data_pre["username"];
		
		if($activity_data['activity_id']==1 || $activity_data['activity_id']==2 || $activity_data['activity_id']==3 || $activity_data['activity_id']== 7)
		{
			$query=mysql_query("SELECT food_items_id,dish_name,dish_url FROM food_items WHERE food_items_id=$activity_data[food_id]");
			$dish_data=mysql_fetch_array($query);
			
			switch($activity_data['activity_id'])
			{
				case 1: 
						$text='added new discovery';
						break;
				case 2: 
						$text='liked';
						break;
				case 3: 
						$text='commented On';
						break;
				case 7: 
						$text='added Picture of';
						break;
			}
									
			$description= '<a href="'.get_page_url('profile', array('uid'=>$user_data_pre["unique_id"])).'">'.$name.'</a> '.$text.' <a href="'.get_page_url('food',array('eid'=>$dish_data['dish_url'])).'" >'.$dish_data['dish_name'].'</a>';
			if($activity_data['activity_id'] == 7)
			{
				$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));			}
				else
				{
				$imagepath=get_page_url("food_pic",array("fid"=>$dish_data["food_items_id"], "size"=>"sqaure_mini"));
				}
			$timestamp=$activity_data['date'];
		}
		else if($activity_data['activity_id']==8)
								{
									$query2=mysql_query("SELECT rsID,res_name FROM restaurant WHERE rsID='$activity_data[rest_id]'");
									$user_data=mysql_fetch_array($query2);
									$url = get_page_url('restaurant', array('eid'=>$activity_data["rest_id"]));
									$description= '<a href="'.get_page_url('profile', array('uid'=>$user_data_pre["unique_id"])).'">'.$name.'</a> added Picture of <a href="'.$url.'">'.$user_data["res_name"].'</a>';
									$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));
									$timestamp=$activity_data['date'];
									
								}
								
		else if($activity_data['activity_id']==4)
		{
			
			$query=mysql_query("SELECT name,unique_id,username FROM userinfo WHERE user_id=$activity_data[follow_id]");
			$user_data=mysql_fetch_array($query);
			$name_follow =  $user_data["name"] ? $user_data["name"] : $user_data["username"] ;
			$url = get_page_url('profile', array('uid'=>$user_data["unique_id"]));
			
			$description= '<a href="'.get_page_url('profile', array('uid'=>$user_data_pre["unique_id"])).'">'.$name.'</a> Followed  <a href="'.$url.'">'.$name_follow.'</a>';
			$imagepath=get_page_url("profile_pic",array("user_id"=>$activity_data["follow_id"]));
			$timestamp=$activity_data['date'];
		}
		
		// Output the results
		include("../activity_template.php");
	}

	$output=ob_get_contents();
	ob_end_clean();
	$output=addslashes($output);
	$output=preg_replace('/\s\s+/', ' ', $output);	//Remove new-line characters
	
	// Include more button if there are more activities to display
	$result2=mysql_query(sprintf("SELECT * FROM activity WHERE user_id IN (%s) and activity_id IN (1,2,3,4,7,8)",$_GET['users']));
	$total_rows=mysql_num_rows($result2);
	if($total_rows > ($_GET['start_from']+$num_results) )
		$output.=" ".addslashes('<a id="more_activity_friend" href="javascript:void(0);">More...</a>');
	
	$json.=' "output" : " '.$output.' " , "count" : '.$num_results.' }';
}

echo $json;
	
?>