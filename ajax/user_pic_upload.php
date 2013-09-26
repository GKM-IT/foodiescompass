<?php
	/* Performs an ajax 'like' operation. */
	
	/* Expects a food item 'eid'. */
	
	require_once("../include/config.inc.php");
	require_once("../include/lib.inc.php");
	require_once("../include/session.inc.php");
	require_once("../include/connection.inc.php");
	require_once("../include/flags.inc.php");
	require_once("../include/resize_img.inc.php");
	if(is_logged_in())
	{
		if($_GET['image']!='')
		{
			$sql = mysql_query("select image_id from images");
			$tot = mysql_num_rows($sql);
			
			$img_extension=substr($_GET["image"],strpos($_GET["image"],'.') );
			$img_filename="profile_".$tot."_".logged_username().$img_extension;
						
			if(!rename(ABS_PATH_TO_HOME.PR_DIR.$_GET["image"],ABS_PATH_TO_HOME.PR_DIR.$img_filename))
			{
				$upload_error="Unsuccessfull renaming image.";
			}
			else
			{		
				rename(ABS_PATH_TO_HOME.PR_DIR.'orig_'.$_GET["image"],ABS_PATH_TO_HOME.PR_DIR.'orig_'.$img_filename);
				//Update database with image name
				
				$resizer4=new SimpleImage();
				
				$resizer4->load(ABS_PATH_TO_HOME.PR_DIR.'orig_'.$img_filename);
				
				$img_info=getimagesize(ABS_PATH_TO_HOME.PR_DIR.$img_filename);
				$img_width=$img_info[0];
				$img_height=$img_info[1];
								
				$height4 = 800;
				$width4 = 800;
				
							
				if(($img_width/$img_height)<=($width4/$height4))
				{
					//Resize to height
					$resizer4->resizeToHeight($height4);
				}
				else
				{
					//Resize to width
					$resizer4->resizeToWidth($width4);
				}
				
				$end_chars1 = strtolower(substr($img_filename, -4));
				$end_chars2 = strtolower(substr($img_filename, -5));
         // echo $end_chars1;     
                if( $end_chars1 == '.jpg' || $end_chars2 == '.jpeg')
                {
                        $image = IMAGETYPE_JPEG;
                }
                else
                {
                        if($end_chars1 == '.png')
                        {
                                $image = IMAGETYPE_PNG;
                        }
                        else
                        {
                                if($end_chars1 == '.gif')
                                {
                                        $image = IMAGETYPE_GIF;
                                }
                               
                        }
                }  
				$resizer4->save2(ABS_PATH_TO_HOME.PR_DIR.'tn_full_'.$img_filename, $image);
				
				$query=mysql_query("insert into images (image, user_id, image_type) VALUES ('".$img_filename."', '".logged_userid()."' , '1')");
				
				$query=mysql_query("update userinfo set profile_pic_uploaded = '".$img_filename."' where user_id = '".logged_userid()."'");
			}
			echo $img_filename;
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