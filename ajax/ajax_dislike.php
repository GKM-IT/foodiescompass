<?php
	/* Performs an ajax 'dilike' operation. */
	
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
			
			// User had previously liked the item
			if(in_array($_GET['eid'],$likes))
			{
				// Decrease like count and increase dislike count
				$food_update=mysql_query("UPDATE food_items SET likes=likes-1, dislikes=dislikes+1 WHERE food_items_id='$_GET[eid]'");
				
				// Remove food item from likes_list of user
				for($i=0;$i<count($likes);$i++)
				{
					if($likes[$i]==$_GET['eid']){ unset($likes[$i]);}
				}
				$new_likes=implode(",",$likes);
				
				// Add food item to dislikes list of user
				array_push($dislikes,$_GET['eid']);
				$new_dislikes=implode(",",$dislikes);
				
				// Update userinfo
				$user_update=mysql_query("UPDATE userinfo SET likes_list='$new_likes', dislikes_list='$new_dislikes' WHERE user_id='$_SESSION[id]'");
				
				$query=mysql_query("update like_dislike set like_dislike='0' where user_id='$_SESSION[id]' AND food_id='$_GET[eid]'");
				
				$query=mysql_query("INSERT INTO activity (activity_id, user_id, food_id,date) VALUES('5','$_SESSION[id]','$_GET[eid]','".strtotime("now")."')");
				
				if($food_update && $user_update)
				{
					echo SUCCESS2;
				}
				else
				{
					echo DB_ERROR;
				}
			}
			// If user has not liked before
			else if(!in_array($_GET['eid'],$dislikes))
			{
					// Add to array
					array_push($dislikes,$_GET['eid']);
					$new_dislikes=implode(",",$dislikes);
					
					// Increase dislike count of food item
					$food_update=mysql_query("UPDATE food_items SET dislikes=dislikes+1 WHERE food_items_id='$_GET[eid]'");
					
					// Add food item to dislike list of food item
					$user_update=mysql_query("UPDATE userinfo SET dislikes_list='$new_dislikes', points=points+".DISLIKE_POINTS." WHERE user_id='$_SESSION[id]'");
					
					$query=mysql_query("INSERT INTO activity (activity_id, user_id, food_id,date) VALUES('5','$_SESSION[id]','$_GET[eid]','".strtotime("now")."')");
				
				$query=mysql_query("INSERT INTO like_dislike ( user_id, food_id, like_dislike) VALUES ('$_SESSION[id]','$_GET[eid]', '0')");
				
					
					if($food_update && $user_update)
					{
						echo SUCCESS;
					}
					else
					{
						echo DB_ERROR;
					}
			}
			else
			{
				// User again trying to dislike. Do nothing.
				echo DUPLICATE_DISLIKE;
				
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