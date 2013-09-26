<?php

	/**
	 *	@GET uid Unique ID of user
	 *
	 *
	 *
	 *
	 *	@author Aakash S Bhowmick
	 *
	 *	@param $_GET['uid']	The unique_id of the user in question
	 *	@param $_GET['user_id'] The user_id of the user
	 *
	 *
	 *	@update Profile.php now accepts user_id as a parameters as well. Either 'user_id' or 'uid' must be set, not both.
	 *
	 *	@to-do Make a profile_pic url in get_page_url
	 * 
	 */

	$time1=microtime(true);	// To test page performance
	
	/* Includes */
	require_once("./include/config.inc.php");
	require_once("./include/lib.inc.php");
	require_once("./include/session.inc.php");
	require_once("./include/connection.inc.php");
	require_once("./include/flags.inc.php");
	
	/* Defines */
	define('MAX_FOLLOWERS_TO_SHOW',10);		// Show at the max, this number number of recent followers
	define('MAX_FOLLOWS_TO_SHOW',10);		// 
	
		
	$title='';
	
	
	/* Allow to view this page only if the person is logged in */
	/*if(is_logged_in())
	{*/
		if($_GET['uid']=="" && $_GET['user_id']=="")
		{
			echo "{'error':'Insufficient data'}";
		}
		else if($_GET['uid']!='' && $_GET['user_id']!="")
		{
			echo "{'error':'Too many parameters'}";
		}
		else	// Only one of user_id or uid is set
		{
			if($_GET['uid']!="")
			{
				$_GET['uid']=mysql_real_escape_string($_GET['uid']);
				$result=mysql_query(sprintf("SELECT * FROM userinfo WHERE unique_id='%s'",$_GET['uid']));
			}
			else
			{
				$_GET['user_id']=mysql_real_escape_string($_GET['user_id']);
				$result=mysql_query(sprintf("SELECT * FROM userinfo WHERE user_id='%d'",$_GET['user_id']));
			}
			
			if(!$result)
			{
				echo 'DB ERROR';
			}
			else
			{
				$userdata=mysql_fetch_array($result);
				$title = $userdata['name'] ? $userdata['name'] :$userdata['username']; 
				$title = $title.'\'s Profile'; 
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title>FoodiesCompass</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="shorcut icon" href="images/favicon.gif" />
		<link href="css/template.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script src="js/crossfade_images.js" type="text/javascript"></script>
		<script src="js/jinclude.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.selectbox-0.2.min.js"></script>
		<script type="text/javascript" src="js/jquery.tipTip.minified.js"></script>
		<link href="css/tipTip.css" type="text/css" rel="stylesheet" />
		<link href="css/jquery.selectbox.css" type="text/css" rel="stylesheet" />
	</head>
		
	<body class="home" onload="initialize()">
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>
	
	<?php require_once("./include/fb_js.inc.php"); ?>
	
	<!-- The popup --->
		<?php require_once 'pop_up.php'; ?>
		
		<div style="width:50%; position:absolute; height:80px; background-image:url('images/yellow_grad.png');">
		
		</div>
			<div id="wrapper" class="white">
				<?php require_once 'header_gen.php'; ?>
								
				<div id="content" style="min-height:500px; margin-bottom:20px" >
					<?php require_once 'page_search.php'; ?>
						
						<div style="border-bottom: 1px solid #F9AE44; margin:0 5px 0 5px;"></div>
						
						<div style="margin:0 10px 0 10px;padding-bottom:5px; margin-top:15px;">
							<div id="follower_left" style="width:480px;float:left;">
								<div id="follower" style=" padding-bottom:4px; ">
									<div id="follower_pic"><img src="<?php echo get_page_url("profile_pic",array("user_id"=>$userdata["user_id"], "size"=> "bigger")); ?>">
									</div>
									<div style="border-bottom: 1px dashed #555; padding-bottom:5px; float:left; width:300px;">
										<div id="follower_about">
										<h1 class="food_name follower_name"><?php echo $userdata['name'] ? $userdata['name'] :$userdata['username'];?></h1><span class="italic_text"><?php echo $userdata['about_me'];?></span><br>
										<div style="margin-top:5px; font-weight:bold;" class="text_orange"><?php echo $userdata['city'];?></div>
									</div>
										
										<div class="clearfix"></div>
									</div>
									<div style="margin-top:10px; float:left; min-width:200px;">
										
										<div id="follower_badge" class="tooltip" title="<?php echo $userdata['num_discoveries'];?> Discoveries">
											<div style=" color:#5A4B43; font-weight:bold; padding-top:19px;height:50px; width:69px; background-image:url('images/icon_dis.png'); background-repeat:no-repeat;"
											align="center"><?php echo $userdata['num_discoveries'];?></div>
										</div>
										
										<div id="follower_badge" class="tooltip" title="<?php echo $userdata['num_reviews'];?> Comments">
											<div style=" color:#5A4B43; font-weight:bold; padding-top:22px;height:47px; width:69px; background-image:url('images/icon_comment.png'); background-repeat:no-repeat;"
											align="center"><?php echo $userdata['num_reviews'];?></div>
										</div>
										
										<div id="follower_badge" class="tooltip" title="<?php echo $userdata['points'];?> Points"> 
											<div style=" color:#fff; font-weight:bold; padding-top:24px;height:47px; width:68px; background-image:url('images/icon_star_big.png'); background-repeat:no-repeat;"
											align="center"><?php echo $userdata['points'];?></div>
										</div>
										
										
										<div id="follower_badge" style="display:none;">
										<p class="food_name">Discoveries: <?php echo $userdata['num_discoveries'];?><br></p>
										<p class="food_name">Reviews: <?php echo $userdata['num_reviews'];?><br></p>
										<p class="food_name">Pictures: <?php echo $userdata['num_pictures'];?><br></p>
										</div>
										<div class="clearfix"></div>
									</div>
									
									<div style="margin-top: 30px;float: right;width: 70px;">
										
										<?php render_follow_button($userdata['user_id']);?>
									</div>
									<div class="clearfix"></div>
								</div>
						
						<?php
						if(logged_userid() == $userdata["user_id"])
						{
						?>
						<div style="">
							<div id="f_act" class="border_fo food_name" style="font-size:20px; float:left; cursor:pointer ">Friends' Activities </div>
							<div id="m_act" class="border_mc food_name" style="font-size:20px; float:left;  cursor:pointer; width:250px;">My Activities</div>
							<div class="clearfix"></div>
						</div>
						<?php
						}
						else
						{
						?>
						<div style="">
							<div id="" class="border_fo food_name" style="font-size:20px; float:left; cursor:pointer ">Activities </div>
							<div id="" class="border_mc food_name" style="font-size:20px; float:left;  cursor:pointer; width:300px;">&nbsp;</div>
							<div class="clearfix"></div>
						</div>
						
						
						<?php } ?>
						<!-- Friend's Activities -->
						<?php
						if(logged_userid() == $userdata["user_id"])
						{
						
						$total_users ='';
						
							if($userdata['follows'])
							{
								$total_users = $userdata['follows'];
							}
							if($userdata['followers'])
							{
								if($total_users)
								{
									$total_users = $total_users.','.$userdata['followers'];
								}
								else
								{
									$total_users = $userdata['followers'];
								}
							}
							if(!$total_users)$total_users =0;
							$sql ="SELECT * FROM activity WHERE user_id IN (".$total_users." ) AND activity_id IN (1,2,3,4,7,8) ORDER BY date DESC";
							
							$query=mysql_query($sql);	// Latest activity first
							$num_rows=mysql_num_rows($query);
							
							$num_activities_shown= ($num_rows < NUM_ACTIVITIES_SHOWN_AT_A_TIME) ? $num_rows: NUM_ACTIVITIES_SHOWN_AT_A_TIME;	// min of these two; NUM_ACTIVITIES_SHOWN_AT_A_TIME defined in flags.inc.php
						?>
						
								<div id="friend_activities" style="">
								<span id="friend_activity_count" style="visibility:hidden;"><?php echo $num_activities_shown; ?></span>
									
						<?php
						if($num_activities_shown>0)
						{		
							for($i=0; $i<$num_activities_shown ; $i++)
							{
								$activity_data=mysql_fetch_array($query);
								$query_user=mysql_query("SELECT name,unique_id,username FROM userinfo WHERE user_id=$activity_data[user_id]");
								$user_data_pre=mysql_fetch_array($query_user);
								$name = $user_data_pre["name"]?$user_data_pre["name"]:$user_data_pre["username"];
								
								if($activity_data['activity_id']==1 || $activity_data['activity_id']==2 || $activity_data['activity_id']==3 || $activity_data['activity_id']==7)
								{
									/**
									 * TO DO: SQL Query in a loop! CHANGE THIS.
									 */
									$query2=mysql_query("SELECT food_items_id, dish_name, dish_url FROM food_items WHERE food_items_id=$activity_data[food_id]");
									$dish_data=mysql_fetch_array($query2);
									
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
									
									$description='<a href="'.get_page_url('profile', array('uid'=>$user_data_pre["unique_id"])).'">'.$name."</a> ".$text." <a href='".get_page_url('food',array('eid'=>$dish_data['dish_url']))."'>".$dish_data['dish_name']."</a>";
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
									$query2=mysql_query("SELECT name,unique_id,username FROM userinfo WHERE user_id=$activity_data[follow_id]");
									$user_data=mysql_fetch_array($query2);
									$name =  ($user_data["name"]) ? $user_data["name"] : $user_data["username"] ;
									$description= '<a href="'.get_page_url('profile', array('uid'=>$user_data_pre["unique_id"])).'">'.$name."</a> followed <a href='".get_page_url('profile', array('uid'=>$user_data["unique_id"]))."'>".$name."</a>";
									$imagepath=get_page_url("profile_pic",array("user_id"=>$activity_data["user_id"]));
									$timestamp=$activity_data['date'];
								}

								
								// Output the results
								include("activity_template.php");
							}
							
							if($num_rows > $num_activities_shown)
							{
						?>								
								<a id="more_activity_friend" href="javascript:void(0);">More...</a>
						<?php
							}
						}
						else
						{ 
						?>
							<div  class='no_res'>No activities by friends yet.</div>
						<?php
						} 
						?>
								</div>
						<?php } ?>
								
								
								
								<!-- Activities -->
						<?php
							
							$query=mysql_query("SELECT * FROM activity WHERE user_id=".$userdata['user_id']." and activity_id IN (1,2,3,4,7,8) ORDER BY date DESC");	// Latest activity first
							$num_rows=mysql_num_rows($query);
							
							$num_activities_shown= ($num_rows < NUM_ACTIVITIES_SHOWN_AT_A_TIME) ? $num_rows: NUM_ACTIVITIES_SHOWN_AT_A_TIME;	// min of these two; NUM_ACTIVITIES_SHOWN_AT_A_TIME defined in flags.inc.php
						?>
								<div id="activities" <?php if(logged_userid() == $userdata["user_id"]) echo 'style="display:none"';?>>
								<span id="activity_count" style="visibility:hidden;"><?php echo $num_activities_shown; ?></span>
									
						<?php
						if($num_activities_shown>0)
						{		
							for($i=0; $i<$num_activities_shown ; $i++)
							{
								
								$activity_data=mysql_fetch_array($query);
								
								if($activity_data['activity_id']== 1 || $activity_data['activity_id']== 2 || $activity_data['activity_id']== 3 || $activity_data['activity_id']== 7)
								{
								
									/**
									 * TO DO: SQL Query in a loop! CHANGE THIS.
									 */
									$query2=mysql_query("SELECT food_items_id, dish_name, dish_url FROM food_items WHERE food_items_id=$activity_data[food_id]");
									$dish_data=mysql_fetch_array($query2);
									switch($activity_data['activity_id'])
									{
										case 1: 
												$text='Added new discovery';
												break;
										case 2: 
												$text='Liked';
												break;
										case 3: 
												$text='Commented On';
												break;
										case 7: 
												$text='Added Picture of';
												break;
									}
									
									$description= $text." <a href='".get_page_url('food',array('eid'=>$dish_data['dish_url']))."'>".$dish_data['dish_name']."</a>";
									if($activity_data['activity_id'] == 7)
									{
										$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));			}
										else
										{
										$imagepath=get_page_url("food_pic",array("fid"=>$dish_data["food_items_id"], "size"=>"sqaure_mini"));
										}
									$timestamp=$activity_data['date'];
									//echo $description;
									include("activity_template.php");
								}
								
								else if($activity_data['activity_id']==8)
								{
									$query2=mysql_query("SELECT rsID,res_name FROM restaurant WHERE rsID='$activity_data[rest_id]'");
									$user_data=mysql_fetch_array($query2);
									$description= "Added Picture of <a href='".get_page_url('restaurant', array('eid'=>$user_data["rsID"]))."'>".$user_data["res_name"]."</a>";
									$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));
									$timestamp=$activity_data['date'];
									include("activity_template.php");
								}
								
								else if($activity_data['activity_id']==4)
								{
									$query2=mysql_query("SELECT name,unique_id,username FROM userinfo WHERE user_id=$activity_data[follow_id]");
									$user_data=mysql_fetch_array($query2);
									$name =  ($user_data["name"]) ? $user_data["name"] : $user_data["username"] ;
									$description= "Followed <a href='".get_page_url('profile', array('uid'=>$user_data["unique_id"]))."'>".$name."</a>";
									$imagepath=get_page_url("profile_pic",array("user_id"=>$activity_data["follow_id"]));
									$timestamp=$activity_data['date'];
									include("activity_template.php");
								}
								
								

								
								// Output the results
								
							}
							
							if($num_rows > $num_activities_shown)
							{
						?>								
								<a id="more_activity" href="javascript:void(0);">More...</a>
						<?php
							}
						}
						else
						{ 
						?>
							<div  class='no_res'>No activity by <?php echo personalise($userdata['user_id'],"you","this user"); ?> yet.</div>
						<?php
						} 
						?>
								</div>
								
								
							</div>
					
					<!-- Likes -->
					<?php
					$followers = explode(",",$userdata['likes_list']);
					$num_followers = ($userdata['likes_list']=="") ? 0 : count($followers);
					
					$dislikes = explode(",",$userdata['dislikes_list']);
					$num_dislikes = ($userdata['dislikes_list']=="") ? 0 : count($dislikes);
					?>
					
							<div id="follower_right" style="width:480px;float:right; border-left:1px solid #bbb; padding-left:10px;">
									
								<div id="follower1" style="">
									<h1 class="food_name" style="font-size:20px;">Likes(<?php echo $num_followers; ?>)</h1>
									<div id="people_item" style="float:left">
									<!-- <img src="images/followers.png"> -->
									<div class="clearfix"></div>
								</div>
					<?php
						
						if($num_followers > 0)
						{
							for($i=0; $i<min($num_followers,MAX_FOLLOWERS_TO_SHOW); $i++)
							{
							$sql_name_query=mysql_query("select dish_name, dish_url from food_items where food_items_id='".$followers[$num_followers-1-$i]."' limit 1");
							$result_name= mysql_fetch_array($sql_name_query);
							$follow_name =$result_name['dish_name'];
					?>
							<div id="people_item" style="float:left" class="tooltip" title="<?php echo $dish_name?>">
								<a href="<?php echo get_page_url('food', array('eid'=>$result_name["dish_url"])); // Choose followers from the end of the list ?>" class="tooltip" title="<?php echo $follow_name;?>">
									<img src="<?php echo get_page_url('food_pic', array('fid'=>$followers[$num_followers-1-$i], 'size'=>'sqaure_mini'));?>">
								</a>
								<div class="clearfix"></div>
							</div>
					<?php 
							}
						}
						else
							echo "<div  class='no_res'>No Likes right now.</div>"
					?>
							<div class="clearfix"></div>
						</div>
						
					<!-- Badges -->
					
					<?php
						$badges=explode(",",$userdata['badges']);
						$badges = array_reverse($badges);
						$num_badges= ($userdata['badges']=="")?0:count($badges);						
					?>
								<div id="follower3" style="margin-top:20px;">
									<h1 class="food_name" style="font-size:20px;">Badges(<?php echo $num_badges; ?>)</h1>
									<div id="badge_item" style="float:left">
								<a class="tooltip" title="<?php echo $num_followers;?> Likes" href="<?php echo $url?>" style="float:left"><img src="images/flag_like.png" width="50" height="50"></a>
								<div class="clearfix"></div>
							</div>
							<div id="badge_item" style="float:left">
								<a class="tooltip" title="<?php echo $num_dislikes;?> Dislikes" href="<?php echo $url?>" style="float:left"><img src=" images/flag_dislike.png" width="50" height="50"></a>
								<div class="clearfix"></div>
							</div>
					<?php
						if($num_badges > 0)
						{
							for($i=0;$i<$num_badges;$i++)
							{
								$badge_query=mysql_query("select food_id, rest_id, activity_id from activity where id='".$badges[$i]."'");
								$activity_data = mysql_fetch_array($badge_query);
								
								if($activity_data['activity_id']== 1  || $activity_data['activity_id']== 3 || $activity_data['activity_id']== 7)
								{
								
									/**
									 * TO DO: SQL Query in a loop! CHANGE THIS.
									 */
									$query2=mysql_query("SELECT food_items_id, dish_name, dish_url FROM food_items WHERE food_items_id=$activity_data[food_id]");
									$dish_data=mysql_fetch_array($query2);
									switch($activity_data['activity_id'])
									{
										case 1: 
												$text='Added new discovery';
												break;
										case 3: 
												$text='Commented On';
												break;
										case 7: 
												$text='Added Picture of';
												break;
									}
									
									$description= $text." ".$dish_data['dish_name'];
									$url = get_page_url('food',array('eid'=>$dish_data['dish_url']));
									if($activity_data['activity_id'] == 7)
									{
										$activity_image = 'images/flag3.png';
										//$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));			
										}
										else
										{
										$activity_image = 'images/flag1.png';
										//$imagepath=get_page_url("food_pic",array("fid"=>$dish_data["food_items_id"], "size"=>"sqaure_mini"));
										}
									$timestamp=$activity_data['date'];
									//echo $description;
								}
								
								else if($activity_data['activity_id']==8)
								{
									$query2=mysql_query("SELECT rsID,res_name FROM restaurant WHERE rsID='$activity_data[rest_id]'");
									$user_data=mysql_fetch_array($query2);
									$description= "Added Picture of ".$user_data["res_name"];
									$url= get_page_url('restaurant', array('eid'=>$user_data["rsID"]));
									//$imagepath=get_page_url("food_image",array("fid"=>$activity_data["image_id"], "size"=>"sqaure_mini"));
									$activity_image = 'images/flag3.png';
									$timestamp=$activity_data['date'];
								}
								
								
								

								
								$activity_title = $description;
					?>
							<div id="badge_item" style="float:left">
								<a class="tooltip" title="<?php echo $activity_title;?>" href="<?php echo $url?>" style="float:left"><img src="<?php echo $activity_image;?>" width="50" height="50"></a>
								<div class="clearfix"></div>
							</div>
					<?php 
							}
						}
						else
						{ 
					?>
						
					<?php
						} 
					?>
								<div class="clearfix"></div>
								</div>
								
						
					<!-- Followers -->
					<?php
					$followers = explode(",",$userdata['followers']);
					$num_followers = ($userdata['followers']=="") ? 0 : count($followers);
										 
					?>
					
							<div id="follower_right" style="width:480px;float:right; border-left:1px solid #bbb; padding-left:10px;">
									
								<div id="follower1" style="margin-top:10px;">
									<h1 class="food_name" style="font-size:20px;">Followers(<?php echo $num_followers; ?>)</h1>
									<div id="people_item" style="float:left">
									<!-- <img src="images/followers.png"> -->
									<div class="clearfix"></div>
								</div>
					<?php
						
						if($num_followers > 0)
						{
							for($i=0; $i<min($num_followers,MAX_FOLLOWERS_TO_SHOW); $i++)
							{
							$sql_name_query=mysql_query("select name, username, unique_id from userinfo where user_id='".$followers[$num_followers-1-$i]."' limit 1");
							$result_name= mysql_fetch_array($sql_name_query);
							$follow_name =$result_name['name'] ? $result_name['name'] :$result_name['username'];
					?>
							<div id="people_item" style="float:left">
								<a href="<?php echo get_page_url('profile', array('uid'=>$result_name["unique_id"])); // Choose followers from the end of the list ?>" class="tooltip" title="<?php echo $follow_name;?>">
									<img src="<?php echo get_page_url("profile_pic",array("user_id"=>$followers[$num_followers-1-$i]));?>">
								</a>
								<div class="clearfix"></div>
							</div>
					<?php 
							}
						}
						else
							echo "<div class='no_res'>No followers right now.</div>"
					?>
							<div class="clearfix"></div>
						</div>
								
					<!--  Follows -->
					<?php
					$follows = explode(",", $userdata['follows']);
					$num_follows = ($userdata['follows']=="") ? 0 : count($follows);
					
					?>
							<div id="follower2" style="margin-top:20px;">
								<h1 class="food_name" style="font-size:20px;">Follows(<?php echo $num_follows; ?>)</h1>
								<div id="people_item" style="float:left">
								<!-- <img src="images/followers.png"> -->
								<div class="clearfix"></div>
							</div>
					<?php
					
						if($num_follows > 0)
						{
							for($i=0; $i<min($num_follows, MAX_FOLLOWS_TO_SHOW); $i++)
							{
							$sql_name_query=mysql_query("select name, username, unique_id from userinfo where user_id='".$follows[$num_follows-1-$i]."' limit 1");
							$result_name= mysql_fetch_array($sql_name_query);
							$follow_name =$result_name['name'] ? $result_name['name'] :$result_name['username'];
					?>
							<div id="people_item" style="float:left">
								<a href="<?php echo get_page_url('profile', array('uid'=>$result_name["unique_id"])); // Take users from the end of the array ?>" class="tooltip" title="<?php echo $follow_name;?>">	
									<img src="<?php echo get_page_url("profile_pic",array("user_id"=>$follows[$num_follows-1-$i]));?>">
								</a>
								<div class="clearfix"></div>
							</div>
					<?php
							}
						}
						else
							echo "<div class='no_res'>Following no users right now.</div>";
					?>
								<div class="clearfix"></div>
								</div>
								
					
								
							</div>
							
							<div class="clearfix"></div>
							
						</div>
						
						
					
				</div>
		
				<script>
				$("#more_activity_friend").live('click',function(){
					var more_button=this;
					$(more_button).html('Loading...');
					var start_from=$("#friend_activity_count").html();
					$.ajax({
						url:'ajax/ajax_load_friend_activity.php',
						data : {'user_id': '<?php echo $userdata['user_id']; ?>', 'users' : '<?php echo $total_users;?>', 'start_from': start_from },
						success : 	function(json_text)
									{
										$(more_button).remove(); 	// Remove 'load more' button
										var json = $.parseJSON(json_text);
																				
										if(json.status==<?php echo NO_MORE_ACTIVITES_ERROR; ?>)
										{
											//alert('No more to load');
										}
										else if(json.status==<?php echo SUCCESS; ?>)
										{
											$("div#friend_activities").append(unescape(json.output));
											$("#friend_activity_count").html(parseInt($("#friend_activity_count").html())+json.count);
										}
										
									}
					});
				});
				
				$("#more_activity").live('click',function(){
				
					var more_button=this;
					$(more_button).html('Loading...');
					var start_from=$("#activity_count").html();
					//alert(start_from);
					$.ajax({
						url:'ajax/ajax_load_activity.php',
						data : {'user_id': '<?php echo $userdata['user_id']; ?>', 'start_from': start_from },
						success : 	function(json_text)
									{
										$(more_button).remove(); 	// Remove 'load more' button
										var json = $.parseJSON(json_text);
																				
										if(json.status==<?php echo NO_MORE_ACTIVITES_ERROR; ?>)
										{
											//alert('No more to load');
										}
										else if(json.status==<?php echo SUCCESS; ?>)
										{
											$("div#activities").append(unescape(json.output));
											$("#activity_count").html(parseInt($("#activity_count").html())+json.count);
										}
										
									}
					});
				});
				
				$('#f_act').click(function(){
					$('#friend_activities').show();
					$('#activities').hide();
					$('#f_act').removeClass('border_fc').addClass('border_fo');
					$('#m_act').removeClass('border_mo').addClass('border_mc');
				});
				$('#m_act').click(function(){
					$('#activities').show();
					$('#friend_activities').hide();
					$('#f_act').removeClass('border_fo').addClass('border_fc');
					$('#m_act').removeClass('border_mc').addClass('border_mo');
				});
				</script>
		
		</div>
		</div>
		<br>
		<?php include('footer.php'); ?>
	</body>
	<script type="text/javascript">
	
	var autocomplete, autocomplete_rest;
		var countryRestrict = { 'country': 'in' };
		
	function initialize() 
		{
			
			autocomplete = new google.maps.places.Autocomplete(	document.getElementById('food_place'), {
				componentRestrictions: countryRestrict
			});
			google.maps.event.addListener(autocomplete, 'place_changed', function() 			{
			place_changed();
			});
		}
		function place_changed() {
    var place = autocomplete.getPlace();
  $('#food_lat').val(place.geometry.location.lat());
  $('#food_lng').val(place.geometry.location.lng());
  $('#food_place_name').val(place.name);
  
  //alert(place.geometry.location.lat());
  for (var i=0; i<place.address_components.length; i++) {
            for (var b=0;b<place.address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
			
                if (place.address_components[i].types[b] == "administrative_area_level_2") {
                    //this is the object you are looking for
                    city= place.address_components[i];
                    break;
                }
				
				if (place.address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    state= place.address_components[i];
                    break;
                }
				
				if (place.address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    locality= place.address_components[i];
                    break;
                }
				
            }
        }
		//$('#food_place').val(place.name);
		$('#food_place').val(place.name + ', ' + locality.long_name);
		$('#food_locality').val(locality.long_name);
		$('#food_city').val(city.long_name);
  }
  
  function follow(id,context)
{
	if(id==-1)
	{
		alert('Login to follow this user');
	}
	else
	{
		$(context).removeClass().addClass('follow_inactive');	// Make button inactive
		$(context).attr('onclick','');	// Disable on-click event
		$.ajax({
			url: "<?php echo BASE_URL_2;?>ajax/ajax_follow.php",
			data: {'id':id},
			success: function(data){
				if(data=='<?php echo USER_FOLLOWED;?>')
				{
					$(context).removeClass().addClass('unfollow');
					//alert('User Followed');
				}
				else if(data=='<?php echo USER_UNFOLLOWED;?>')
				{
					$(context).removeClass().addClass('follow');
					//alert('User Unfollowed');
				}
				else
				{
					// Some error has occurred
					var msg;
					switch(data)
					{
						case '<?php echo DB_ERROR;?>': msg='Database error.'; break;
						case '<?php echo SEARCH_ERROR;?>': msg='User does not exist.'; break;
						case '<?php echo DB_UPDATE_ERROR;?>': msg='Database update error.'; break;
						case '<?php echo SELF_FOLLOW_ERROR;?>': msg='You cannot follow yourself.'; break;
						case '<?php echo INSUFFICIENT_PARAMS_ERROR;?>': msg='Insufficient parameters.'; break;
						case '<?php echo NO_SESSION_EXISTS_ERROR;?>': msg='No user logged in.'; break;
					}
					alert(msg);
				}
			}
		});
	}
}	// End of follow
  </script>
	
	
	<?php require_once('include/tiptip.php'); ?>
	<?php include 'include/only_login.php'; ?>
</html>
<?php
			}	// if userdata is available
		}  // if either 'uid' or 'user_id' is set
		
	/*}	// If is_logged_in
	else
	{
		header('Location: '.get_page_url('landing'));
	}*/
	
	$time2=microtime(true);
	error_log("Render time:".(($time2-$time1))." milliseconds")
?>