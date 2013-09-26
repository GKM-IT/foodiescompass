<?php
	/* Performs an ajax 'like' operation. */
	
	/* Expects a food item 'eid'. */
	
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	
	if(is_logged_in())
	{
		if($_GET['eid']!='')
		{
			// Check if user has already liked the item
			$result=mysql_query("SELECT likes_list,dislikes_list FROM userinfo WHERE user_id='$_SESSION[id]'");
			$results_array=mysql_fetch_array($result);
			
			if($results_array['likes_list'])
			{
			$likes=explode(",",$results_array['likes_list']);
			}
			else
			{
				$likes = array();
			}
			if($results_array['dislikes_list'])
			{
			$dislikes=explode(",",$results_array['dislikes_list']);
			}
			else
			{
			$dislikes = array();
			}
			
			if(in_array($_GET['eid'],$likes))
			{
				// User attempting to like again. Do nothing.
				echo DUPLICATE_LIKE;
			}
			// User had originally disliked but is now liking the food item
			else if(in_array($_GET['eid'],$dislikes))
			{
				// Increase like count amd decrease dislike count of food item
				$food_update=mysql_query("UPDATE food_items SET likes=likes+1, dislikes=dislikes-1 WHERE food_items_id='$_GET[eid]'");
				
				// Remove food item from dislikes list of user
				for($i=0;$i<count($dislikes);$i++)
				{
					if($dislikes[$i]==$_GET['eid']){ unset($dislikes[$i]);}
				}
				$new_dislikes=implode(",",$dislikes);
				
				// Add food item to likes_list of user
				array_push($likes,$_GET['eid']);
				$new_likes=implode(',',$likes);
				$user_update=mysql_query("UPDATE userinfo SET likes_list='$new_likes',dislikes_list='$new_dislikes' WHERE user_id='$_SESSION[id]'");
				$query=mysql_query("update like_dislike set like_dislike='1' where user_id='$_SESSION[id]' AND food_id='$_GET[eid]'");
				$query=mysql_query("INSERT INTO activity (activity_id, user_id, food_id,date) VALUES('2','$_SESSION[id]','$_GET[eid]','".strtotime("now")."')");
				
				
				if($food_update && $user_update)
				{
					echo SUCCESS2;
				}
				else
				{
					echo DB_ERROR;
				}
			}
			// User had neither liked nor disliked. 
			else
			{
			
				array_push($likes,$_GET['eid']);
				$new_likes=implode(',',$likes);
				// Increase like count of food item by one
				$food_update=mysql_query("UPDATE food_items SET likes=likes+1 WHERE food_items_id='$_GET[eid]'");
				
				// Add food item to likes_list of user
				$user_update=mysql_query("UPDATE userinfo SET likes_list='$new_likes', points=points+".LIKE_POINTS." WHERE user_id='$_SESSION[id]'");
				
				$query=mysql_query("INSERT INTO activity (activity_id, user_id, food_id,date) VALUES('2','$_SESSION[id]','$_GET[eid]','".strtotime("now")."')");
				
				$query=mysql_query("INSERT INTO like_dislike ( user_id, food_id, like_dislike) VALUES ('$_SESSION[id]','$_GET[eid]', '1')");
				
				if($food_update && $user_update)
				{
					echo SUCCESS;
				}
				else
				{
					echo DB_ERROR;
				}
			}
		}
		else
		{
			echo INSUFFICIENT_PARAMS_ERROR;
		}
	}
	else
	{
		echo NO_SESSION_EXISTS_ERROR;
	}
?>