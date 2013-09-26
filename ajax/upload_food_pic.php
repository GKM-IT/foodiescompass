<?php
	/*
	 *	Accepts an image file uploaded by user for ajax-like upload.
	 *
	 *	@author: Aakash Subhankar Bhowmick
	 *	@created: 9th October,2012
	 *	@last_modified:
	 *	@last_modified by:
	 *
	 */
	 
	define('BASE_DIR','../');
	define('UPLOAD_DIR','uploads/food_pics/');
	define('IMG_COMPRESSION',75); //In percentage
	define('IMG_RESIZE_WIDTH',375);
	define('IMG_RESIZE_HEIGHT',245);
	
	require_once(BASE_DIR.'include/config.inc.php');
	
	//Allowed filetypes
	$mime=array('image/jpg','image/jpeg','image/pjpeg','image/png','image/gif');
	$extensions=array('image/jpg'=>'.jpg','image/jpeg'=>'.jpeg','image/pjpeg'=>'.jpeg','image/png'=>'.png','image/gif'=>'.gif');
	
	if($_FILES['food_pic']['error']==UPLOAD_ERR_OK && $_FILES['food_pic']['size']<=MAX_UPLOAD_SIZE && in_array($_FILES['food_pic']['type'],$mime))
	{
		//Move file to uploads/food_pics with a temp name
		$ext=$extensions[$_FILES['food_pic']['type']];
		
		//Find a unique temp file name
		$temp_filename;
		do
		{
			$cal = substr(md5($_FILES['food_pic']['tmp_name'].rand()),0,10);
			$temp_filename='temp_'.$cal.$ext;
			$temp_filename_ori='orig_temp_'.$cal.$ext;
		}
		while(file_exists(BASE_DIR.UPLOAD_DIR.$temp_filename));
		
		// Move file
		move_uploaded_file($_FILES['food_pic']['tmp_name'],BASE_DIR.UPLOAD_DIR.$temp_filename);
		copy(BASE_DIR.UPLOAD_DIR.$temp_filename,BASE_DIR.UPLOAD_DIR.$temp_filename_ori);
		
		// Resize moved image
		require_once(BASE_DIR."include/resize_img.inc.php");
		$resizer=new SimpleImage();
		$resizer->load(BASE_DIR.UPLOAD_DIR.$temp_filename);
		$img_info=getimagesize(BASE_DIR.UPLOAD_DIR.$temp_filename);
		$img_width=$img_info[0];
		$img_height=$img_info[1];
		
		if(($img_width/$img_height)>=(IMG_RESIZE_WIDTH/IMG_RESIZE_HEIGHT))
		{
			//Resize to height
			$resizer->resizeToHeight(IMG_RESIZE_HEIGHT);
		}
		else
		{
			//Resize to width
			$resizer->resizeToWidth(IMG_RESIZE_WIDTH);
		}
		
		//Crop image to size,centered
		//$resizer->cropImage(IMG_RESIZE_WIDTH,IMG_RESIZE_HEIGHT,true);
		
		$resizer->save(BASE_DIR.UPLOAD_DIR.$temp_filename);
		
		$json="{'status':'".$_FILES['food_pic']['error']."','img_name':'".$temp_filename."','filepath':'".UPLOAD_DIR.$temp_filename."','msg':'Upload successfull.'}";
		echo $json;
		
	}
	else if($_FILES['food_pic']['error']==0)
	{
		//Upload successful but error in file size or file type.
		if($_FILES['food_pic']['size']>MAX_UPLOAD_SIZE)
		{
			//File exceeds max size
			$json="{'status':'".UPLOAD_ERR_FORM_SIZE."','msg':'Uploaded file exceeds the max allowed size.'}";
			echo $json;
		}
		else if(!in_array($_FILES['food_pic']['type'],$mime))
		{
			//Error value 9 : File not of expected type
			$json="{'status':'9','msg':'Uploaded file is not an image.'}";
			echo $json;
		}
		
	}
	else
	{
		$json="";
		switch($_FILES['food_pic']['error'])
		{
			case UPLOAD_ERR_INI_SIZE: $json="{'status':'".UPLOAD_ERR_INI_SIZE."','msg':'The uploaded file exceeds the upload_max_filesize.'}"; break;
			case UPLOAD_ERR_FORM_SIZE: $json="{'status':'".UPLOAD_ERR_FORM_SIZE."','msg':'Uploaded file exceeds the max allowed size.'}"; break;
			case UPLOAD_ERR_PARTIAL: $json="{'status':'".UPLOAD_ERR_PARTIAL."','msg':'File could not be uploaded completely.'}"; break;
			case UPLOAD_ERR_NO_FILE: $json="{'status':'".UPLOAD_ERR_NO_FILE."','msg':'No file was uploaded.'}"; break;
			case UPLOAD_ERR_NO_TMP_DIR: $json="{'status':'".UPLOAD_ERR_NO_TMP_DIR."','msg':'Missing a temporary folder.'}"; break;
			case UPLOAD_ERR_CANT_WRITE: $json="{'status':'".UPLOAD_ERR_CANT_WRITE."','msg':'Failed to write file to disk.'}"; break;
			case UPLOAD_ERR_EXTENSION: $json="{'status':'".UPLOAD_ERR_EXTENSION."','msg':'A PHP extension stopped the file upload.'}"; break;
			default: $json="{'status':'10','msg':'Unknown error.'}"; break;
		}
		echo $json;
	}
	
?>