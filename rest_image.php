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
include 'include/config.inc.php';


	$result=mysql_query(sprintf("SELECT image FROM images WHERE image_id='%d'",$_GET['fid']));
	
	
	
	if(mysql_num_rows($result)==0)
	{
		echo "{ 'error':'Bad data'}";
	}
	else
	{
		$data=mysql_fetch_array($result);
		
		$img=$data['image'];
		$url= 'create_food_pic.php';
		
			
		if($_GET['size']=="sqaure_mini")
				{
					$url.="?pic=".IMG_DIR."tn2_".$img."&w=50&h=50";
				}
				if($_GET['size']=="sqaure_mid")
				{
					$url.="?pic=".IMG_DIR."tn2_".$img."&w=70&h=70";
				}
		else if($_GET['size']=="sqaure")
		{
			$url.="?pic=".IMG_DIR."tn2_".$img."&w=100&h=100";
		}
		else if($_GET['size']=="thumb")
		{
			$url=IMG_DIR."tn1_".$img;
		}
		else if($_GET['size']=="normal")
		{
			$url=IMG_DIR.$img;
		}	
			else if($_GET['size']=="full")
		{
			$url=IMG_DIR."tn_full_".$img;
		}
			
		
		
		//echo $_GET['size'].$url;
		header("Location: ".$url);
		
	}

?>