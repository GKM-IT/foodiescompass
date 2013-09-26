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


	$result=mysql_query(sprintf("SELECT dish_image, dish_default FROM food_items WHERE food_items_id='%d'",$_GET['fid']));
	
	
	
	if(mysql_num_rows($result)==0)
	{
		echo "{ 'error':'Bad data'}";
	}
	else
	{
		$data=mysql_fetch_array($result);
		
		$img=$data['dish_image'];
		$default=$data['dish_default'];
		$url= 'create_food_pic.php';
		
		if($img) // profile pic is on either facebook or twitter
		{

			$img = $img;
		}
		else
		{
			switch($default)
			{
				case 0: $img='food_item142.jpeg';
						break;
				case 1: $img='food_item142.jpeg';
						break;	
			}
			
		}
		
		if($_GET['size']=="sqaure_mini")
				{
					$url.="?pic=".IMG_DIR."tn2_".$img."&w=50&h=50";
				}
		else if($_GET['size']=="sqaure")
		{
			$url.="?pic=".IMG_DIR."tn2_".$img."&w=100&h=100";
		}
		else if($_GET['size']=="thumb")
		{
			$url=IMG_DIR."tn1_".$img;
		}
		else if($_GET['size']=="thumb_mini")
		{
			$url.="?pic=".IMG_DIR."tn1_".$img."&w=103&h=67";
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