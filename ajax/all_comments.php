<?php
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	
	
	$offset=5.5*60*60;
	if(!$_POST["limit"])
	{
	$result= mysql_query("select comment.user_id, comment.comment, comment.date, userinfo.username, userinfo.name, userinfo.unique_id from comment inner join userinfo on comment.user_id = userinfo.user_id where comment.food_id='$_POST[fid]' order by comment.comment_id desc");
	}
	else
	{
	$result= mysql_query("select comment.user_id, comment.comment, comment.date, userinfo.username, userinfo.name, userinfo.unique_id from comment inner join userinfo on comment.user_id = userinfo.user_id where comment.food_id='$_POST[fid]' order by comment.comment_id desc limit 5");
	}
			while($comment = mysql_fetch_assoc($result))
			{
					echo '<div class="comment-item">
					<div class="comment-strip">
						<div style="float:left;"><a href="'.get_page_url("profile",array("uid"=>$comment["unique_id"])).'" style="border:none;"><img src="'.get_page_url("profile_pic",array("user_id"=>$comment["user_id"])).'"></a></div>
						<div style="float:left;margin: 15px 0 0 5px;">by <span style="font-family:Quicksand_bold;">
						<a href="'.get_page_url("profile",array("uid"=>$comment["unique_id"])).'" style="text-decoration:none; color:inherit;">';
						echo ($comment["name"]) ? $comment["name"] : $comment["username"];
						echo '</span></a></div>
						<div style="float:right;margin: 15px 0 0 5px;">'.date("H:i:s d/m/y", $comment["date"] + $offset).' </div>
						<div class="clearfix"></div>
					</div>
					<div class="comment_data">
					'.html_entity_decode($comment['comment']).'
					</div>
					
				</div>';
				}
?>