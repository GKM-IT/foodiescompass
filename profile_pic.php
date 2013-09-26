<?php
/**
 * Redirects to profile pic of user at foodiescompass.com
 * 
 * Expects ('unique_id' or 'user_id') and 'size' parameters.
 * 
 * Note : Both, unique_id and user_id should not be set. Gives error
 * 
 * 
 * @author Aakash S Bhowmick
 * 
 */

include 'include/connection.inc.php';

if($_GET['unique_id']=='' && $_GET['user_id']=='')
{
	echo "{ 'error':'Insufficient parameters'}";
}
else if($_GET['unique_id']!='' && $_GET['user_id']!='')
{
	echo "{ 'error':'Too many parameters'}";
}
else
{
	if($_GET['unique_id']!="")
	{
		$result=mysql_query(sprintf("SELECT profile_pic,profile_pic_uploaded FROM userinfo WHERE unique_id='%s'",$_GET['unique_id']));
	}
	else
	{
		$result=mysql_query(sprintf("SELECT profile_pic,profile_pic_uploaded FROM userinfo WHERE user_id='%d'",$_GET['user_id']));
	}
	
	
	if(mysql_num_rows($result)==0)
	{
		echo "{ 'error':'Bad data'}";
	}
	else
	{
		$data=mysql_fetch_array($result);
		
		$url=$data['profile_pic'];
		
		if(!$data['profile_pic_uploaded'])
		{
		if($url) // profile pic is on either facebook or twitter
		{
			if(!(strpos($url,"facebook.com")===false))	// Profile pic from Facebook
			{
				if($_GET['size']=="bigger")
				{
					$url.="?width=160&height=160";
				}
				else if($_GET['size']=="smaller")
				{
					$url.="?width=75&height=75";
				}
				else
				{
					//$url.="?width=90&height=90";
				}
			}
			else 	// Profile pic from Twitter
			{	
				
				if($_GET['size']=="bigger")
				{
					$url.="?size=bigger";
				}
				else if($_GET['size']=="smaller")
				{
					$url.="?size=mini";
				}
				else
				{
					$url.="?size=normal";
				}
			}
			/*else	// Uploaded image to foodies compass
			{
				$url="uploads/profile_pics/".$url;				
			}*/
			
		}
		else
		{
			$url= 'create_pic.php';
			$img ='images/profile_pic.png';
			if($_GET['size']=="bigger")
				{
					$url.="?pic=".$img."&w=160&h=160";
				}
				else if($_GET['size']=="smaller")
				{
					$url.="?pic=".$img."&w=75&h=75";
				}
				else
				{
					$url.="?pic=".$img."&w=50&h=50";
				}
		
		}
		
		//echo $url;
		header("Location: ".$url);
		}
		else
		{
			$url= 'create_pic.php';
			$img =PR_DIR.$data['profile_pic_uploaded'];
			if($_GET['size']=="bigger")
				{
					$url.="?pic=".$img."&w=160&h=160";
				}
				else if($_GET['size']=="smaller")
				{
					$url.="?pic=".$img."&w=75&h=75";
				}
				else
				{
					$url.="?pic=".$img."&w=50&h=50";
				}
			//echo $url;	
		header("Location: ".$url);
		}
	}
}
?>