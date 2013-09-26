<?php

/**
 * Processes AJAX request to follow/unfollow a user.
 * 
 * Note: Session variable $_SESSION['follows'](array) always contains the list of followers of the logged in user.
 * 		 The array is updated whenever a new user is followed. This drastically reduced the redering time of the 
 * 
 * 
 * @modified 	: 17/12/2012
 * @changes		: Follows list of logged in user is now stored in a session variable, $_SESSION['follows'],
 * 				  to minimize number of SQL queries
 */
	/* Expects a parameters 'id'. */
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	
	if(is_logged_in())
	{
		if($_GET['id']!='')
		{
			if(!($_GET['id']==$_SESSION['id']) )
			{
				$_GET['id']=mysql_real_escape_string($_GET['id']);
				
				//  Search in follow list of currently logged in user
				//$result=mysql_query("SELECT follows FROM userinfo WHERE user_id='$_SESSION[id]'");
				
				// Also search if $_GET['id'] is valid, i.e. such a user exists
				$result2=mysql_query(sprintf("SELECT followers FROM userinfo WHERE user_id=%d",$_GET['id']));
								
				if(!$result2)
				{
					echo DB_ERROR;
					error_log(mysql_error());
				}
				else
				{
					if(mysql_num_rows($result2)==0)
					{
						echo SEARCH_ERROR;
					}
					else
					{
						// $_SESSION['follows'] contains follow list of the current user
						$other_userdata=mysql_fetch_array($result2);
						
						$follower_list=explode(",",$other_userdata['followers']);
						
						if(!(in_array($_GET['id'],$_SESSION['follows'])) )
						{
							// Add new user to follow list #StartFollowing
							array_push($_SESSION['follows'],$_GET['id']);
							
							// Add current user to follower list of other user 
							array_push($follower_list,$_SESSION['id']);
							
							$action='follow';
							
							$query=mysql_query("INSERT INTO activity (activity_id, user_id, follow_id,date) VALUES('4','".logged_userid()."','$_GET[id]','".strtotime("now")."')");
						}
						else
						{
							// Remove user from follow list #Unfollow
							$num_follows=count($_SESSION['follows']);
							for($i=0;$i<$num_follows;$i++)
							{
								if($_SESSION['follows'][$i]==$_GET['id'])
								{
									unset($_SESSION['follows'][$i]);
								}
							}
							
							// Remove current user from follower list of other user
							for($i=0;$i<count($follower_list);$i++)
							{
								if($follower_list[$i]==$_SESSION['id'])
								{
									unset($follower_list[$i]);
								}
							}
							
							$action='unfollow';
							$query=mysql_query("INSERT INTO activity (activity_id, user_id, follow_id,date) VALUES('6','".logged_userid()."','$_GET[id]','".strtotime("now")."')");
						}
						
						// Save new follow list
						$new_follow_list=implode(",",$_SESSION['follows']);
						$new_follow_list=trim($new_follow_list,",");
						$result=mysql_query("UPDATE userinfo SET follows='$new_follow_list' WHERE user_id='$_SESSION[id]'");
						
						// Save new follower list
						$new_follower_list=implode(",",$follower_list);
						$new_follower_list=trim($new_follower_list,",");
						$result2=mysql_query(sprintf("UPDATE userinfo SET followers='%s' WHERE user_id='%d'",$new_follower_list,$_GET['id']));
						
						if($result && $result2)
						{
							if($action=='follow')
							{
								register_activity(ACTIVITY_FOLLOWED, $_SESSION['id'],$_GET['id']);
								echo USER_FOLLOWED;
							}
							else
							{
								register_activity(ACTIVITY_UNFOLLOWED, $_SESSION['id'],$_GET['id']);
								echo USER_UNFOLLOWED;
							}
							
						}
						else
						{
							echo DB_UPDATE_ERROR;
							error_log(mysql_error());
						}
					}
				}
			}
			else
			{
				// User if trying to follow himself
				echo SELF_FOLLOW_ERROR;
			}
		}
		else
		{
			// 'id' parameter was not sent
			echo INSUFFICIENT_PARAMS_ERROR;
		}
	}
	else
	{
		// No user logged in
		echo NO_SESSION_EXISTS_ERROR;
	}
	
?>