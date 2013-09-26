<?php
/* Library of frequently used functions.
 *
 * @author: Aakash Subhankar Bhowmick
 */

require_once('config.inc.php');
require_once('session.inc.php');
require_once("connection.inc.php");
require_once("flags.inc.php");

/* Returns a unique 6-character ID, given any initial seed value.
 * Try to keep seed as random as possible to increase speed 
 * of generation of unique id. This function is unidirectional and
 * it is not possible to get back the value of seed from the generated ID.
 */   
function getUniqueId($seed=false)
{
	$list=str_split("0123456789ABCDFGHJKLMNPQRSTVWXYZabcdfghjklmnpqrstvwxyz");
	if(!$seed){ $seed=rand(); }
	for(;;)
	{		
		$id="";
		for($i=0;$i<6;$i++)
		{
			$index=rand(0,count($list)-1);
			if($i==0 && $index>51){ $i=-1; continue; } //First char should not be special char
			$id.=$list[$index];
		}
		
		//Check if it already exists
		$query=mysql_query("SELECT * FROM userinfo WHERE unique_id='$id'");
		if(mysql_num_rows($query)==0)
			return($id);
		else
			continue;
		}
}

function getUniquePassword($seed=false)
{
	$list=str_split("0123456789ABCDEFGHJKLMNPQRSTVWXYZabcdefghijklmnopqrstuvwxyz");
	if(!$seed){ $seed=rand(); }
		$id="";
		for($i=0;$i<10;$i++)
		{
			$index=rand(0,count($list)-1);
			if($i==0 && $index>51){ $i=-1; continue; } //First char should not be special char
			$id.=$list[$index];
		}
		
		return($id);
		
}


/* Function to make cURL requests. */
function curl($url)
{
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	/* For KGP */
	//curl_setopt($ch,CURLOPT_PROXY,"144.16.192.245");
	//curl_setopt($ch,CURLOPT_PROXYPORT,"8080");

	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

	
/* Returns page paths, relative to base url. Parameters, if required, must be passed as */
function page_path($identifier)
{
	switch($identifier)
	{
		case 'landing': return '/index.php';
		case 'forgot': return '/forgot-password';
		case 'home':return '/home';
		case 'fb_direct_login': return '/auth/fb_direct_login.php';
		case 'fb_auth': return '/auth/fb_auth.php';
		case 'logout': return '/auth/logout.php';
		case 'signup': return '/auth/signup.php';
		case 'upload': return '/upload';
		case 'about': return '/about';
		case 'faq': return '/faq';
		case 'food': return '/';
		case 'map_search': return '/search';
		case 'edit_profile': return '/profile-settings';
		case 'profile': return '/profile-';
		case 'restaurant': return '/restaurant.php';
		case 'food_pic': return '/food_pic.php';
		case 'food_image': return '/food_image.php';
		case 'profile_pic' : return '/profile_pic.php';
		case 'rest_pic' : return '/res_pic.php';
		case 'rest_image' : return '/rest_image.php';
		case 'add_pic' : return '/add_pic.php';
		case 'add_res_pic' : return '/add_res_pic.php';
		case 'followers' : return '/allfoodies';
		case 'twitter_redirect': return '/auth/twitter_oauth/redirect.php';
		default: return '/404.php';
	}
}
	
/* Returns absolute page urls. */
function get_page_url($page, $params=false)
{
	$url=BASE_URL.page_path($page);
	
	switch($page)
	{
		case 'food':
					if($params)
					{
						$url.="";
						foreach($params as $key=>$value)
						{
							$url.= (urlencode($value)."&");
						}
						$url=rtrim($url,"&");
					}
					break;
		case 'profile':
					if($params)
					{
						$url.="";
						foreach($params as $key=>$value)
						{
							$url.= (urlencode($value)."&");
						}
						$url=rtrim($url,"&");
					}
					break;
					
		default:
					{
						if($params)
						{
							$url.="?";
							foreach($params as $key=>$value)
							{
								$url.= ($key."=".urlencode($value)."&");
							}
							$url=rtrim($url,"&");
						}
					}
					break;
	
	
	}


	
	return $url;
}
	
function is_an_integer($input)
{
	if(is_numeric($input))
	{
		if(floor($input)==$input)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	else
	{
		return false;
	}
}

/* Renders a follow or unfollow button based on follow status of the current user. 
 * Parameter: id - user id of user to be followed.
 * 
 * Note: No button is rendered if 'id' passed is same as that of logged in user.
 * 
 * @modified : 17/12/12
 * @changes : Removed SQL query; Follows list is instead fetched from the session variable $_SESSION['follows']
 */
function render_follow_button($id)
{
	if(is_logged_in())
	{
		$id=mysql_real_escape_string($id);
		
		if($_SESSION['id']==$id)
		{
			// No button rendered
		}
		else if(!(in_array($id,$_SESSION['follows'])) )
		{
			echo "<a class='follow' onclick='follow($id,this);'></a>";
		}
		else
		{
			echo "<a class='unfollow' onclick='follow($id,this);'></a>";
		}

	}
	else
	{
		// Login to follow this user
		echo "<a class='follow login_popup' ></a>";
	}
}

/* Renders a food item 'like' button.
 * Expects an entry id.
 */

function render_like_pair($eid)
{

	if(is_logged_in())
	{
		$eid=mysql_real_escape_string($eid);
		
		//  Search in likes_list,dislikes_list of currently logged in user
		$result=mysql_query("SELECT likes_list,dislikes_list FROM userinfo WHERE user_id='$_SESSION[id]'");
		
		if(!$result)
		{
			// Render a simple like/dislike button pair, without highlighting
			echo "<a class='like_button' name='like_$eid' onclick='like($eid,",ACTION_LIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_up.png' title='Like' class='tooltip'></a>";  
			echo "<a class='like_button' name='dislike_$eid' onclick='like($eid,",ACTION_DISLIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_down.png' title='Dislike' class='tooltip'></a>";  
			error_log('Error while rendering like button - '.mysql_error());
		}
		else
		{
			if(mysql_num_rows($result)==0)
			{
				error_log('Error while rendering follow button - No such user');
			}
			else
			{
				
				$userdata=mysql_fetch_array($result);
				$likes=explode(",",$userdata['likes_list']);
				$dislikes=explode(",",$userdata['dislikes_list']);
				
				if(in_array($eid,$likes))
				{
					// Highlight like button
					echo "<a class='like_button' name='like_$eid' onclick='like($eid,",ACTION_LIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_up_shadow.png' title='Like' class='tooltip'></a>";  
					echo "<a class='like_button' name='dislike_$eid' onclick='like($eid,",ACTION_DISLIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_down.png' title='Dislike' class='tooltip'></a>";  
				}
				else if(in_array($eid,$dislikes))
				{
					// Highlight dislike button
					echo "<a class='like_button' name='like_$eid' onclick='like($eid,",ACTION_LIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_up.png' title='Like' class='tooltip'></a>";  
					echo "<a class='like_button' name='dislike_$eid' onclick='like($eid,",ACTION_DISLIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_down_shadow.png' title='Dislike' class='tooltip'></a>";  
				}
				else
				{
					// User has neither liked or disliked previously. Render unhighlighted buttons
					// Highlight dislike button
					echo "<a class='like_button' name='like_$eid' onclick='like($eid,",ACTION_LIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_up.png' title='Like' class='tooltip'></a>";  
					echo "<a class='like_button' name='dislike_$eid' onclick='like($eid,",ACTION_DISLIKE,",this);'><img src='".BASE_URL_2."images/icon_thumb_down.png' title='Dislike' class='tooltip'></a>";  
				}
			}
		}
	}
	else
	{
		// Login to follow this user
		echo "<a class='like_button login_popup' name='like_$eid' ><img src='".BASE_URL_2."images/icon_thumb_up.png' title='Like' class='tooltip'></a>";  
		echo "<a class='like_button login_popup' name='dislike_$eid' ><img src='".BASE_URL_2."images/icon_thumb_down.png' title='Dislike' class='tooltip'></a>";  
	}
}

/**
 * Returns a textual description of a given timestamp wrt to the current time.
 * E.g., A given timestamp is converted to 'A few second ago', '10 minutes ago', '3 months ago', etc.
 * 
 * Function assumes ($present-$past)>0
 */
function time_text($past_timestamp)
{
	$present_timestamp=time();
	$diff=$present_timestamp-$past_timestamp;
	
	if($diff<60)	// less than a minute
	{
		return "A few seconds ago";
	}
	else if($diff < 60*60)	// less than an hour
	{
		$count=floor($diff/60);
		return "$count minute".($count>1?"s":"")." ago";
	}
	else if($diff < 60*60*24)		// less than a day
	{
		$count=floor($diff/(60*60));
		return "$count hour".($count>1?"s":"")." ago";
	}
	else if($diff < 60*60*24*7)	// less than a week
	{
		$count=floor($diff/(60*60*24));
		return "$count day".($count>1?"s":"")." ago";
	}
	else if($diff < 60*60*24*30)	// less than a month
	{
		$count=floor($diff/(60*60*24*7));
		return "$count week".($count>1?"s":"")." ago";
	}
	else if($diff < 60*60*24*30*365)	// less than a year
	{
		$count=floor($diff/(60*60*24*30));
		return "$count month".($count>1?"s":"")." ago";
	}
	else	// greater than a year
	{
		$count=floor($diff/(60*60*24*30*365));
		return "$count year".($count>1?"s":"")." ago";
	}
}

/**
 * Registers users activity and takes appropriate action corresponding the to activity, like increase points, 
 * provide badges to ther user, etc.
 * 
 * This function has been made because the number of activities will increase with time
 * 
 * Activity types are defined in flags.inc.php
 */

function register_activity($activity_type, $user_id, $data)
{
	if($activity_type == ACTIVITY_FOLLOWED)
	{
		// Store activity in database
		mysql_query(sprintf("INSERT INTO user_activity (user_id,activity_type,detail1,timestamp) VALUES('%s','%s','%s','%d')", $user_id, $activity_type, $data,time()));
	}
	else if($activity_type == ACTIVITY_UNFOLLOWED)
	{
		// Delete activity
		mysql_query(sprintf("UPDATE user_info SET deleted=1 WHERE ( user_id='%d' AND activity_type='%d' AND detail1='%d' )",$user_id, $activity_type, $data));
	}
	else if($activity_type == ACTIVITY_UPLOADED)
	{
		// To do.
	}
}

/**
 * Any form of textual communication to the user about some user is said to be 'personalised' if the code is aware
 * of the situation when the user being talked about is the logged-in user himself.
 * 
 * E.g. "This user does not have any badges" should be written as "You don't have any badges", if the user being talked about
 * is the logged in user himself.
 * 
 * Takes a user_id and compares with current logged in user. If same, second argument is returned, else third.
 */

function personalise($user_id,$second_person,$third_person)
{
	if(is_logged_in())
	{
		if($_SESSION['id']==$user_id)
		{
			return $second_person;
		}
		else
		{
			return $third_person;
		}
	}
	else
	{
		return $third_person;
	}
}

/**
 * Renders review stars
 */
function render_star_panel( $point, $out_of)
{
	$value=round($point/$out_of*5,0);
	$output="<ul class='star_panel'>";
	for($i=1;$i<=5;$i++)
	{
		if($i<=$value)
			$output.="<li class='star1 star' value='$i'></li>";
		else
			$output.="<li class='star2 star' value='$i'></li>";
	}
	
	$output.="<li class='hidden' value='$value' style='clear:both;'></li></ul>";
	
	echo $output;
}

function get_upload_link()
{
	if(is_logged_in())
	{
			echo "<a class='link_no_style' href='".get_page_url("upload")."'>";
	}
	else
	{
		// Login to follow this user
		echo '<a class="login_popup" href="javascript:void(0);">';
	}

}
?>