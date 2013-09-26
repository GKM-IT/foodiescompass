<?php
 
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

define('IMG_COMPRESSION',75);
define('WATERMARK_OVERLAY_IMAGE', 'watermark/logo.png');
define('WATERMARK_OVERLAY2_IMAGE', 'watermark/logo2.png');
define('WATERMARK_OVERLAY_OPACITY', 20);
define('WATERMARK_OUTPUT_QUALITY', 90);

class SimpleImage {
 
   var $image;
   var $image_type;
   var $filepath;
	
	function merge()
	{
	$source_width =imagesx($this->image);
	$source_height =imagesy($this->image);
	
	$overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY_IMAGE);
	
	$overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);
	imagecolortransparent($overlay_gd_image,imagecolorat($overlay_gd_image,0,0));
	imagecopymerge(
        $this->image,
        $overlay_gd_image,
        ($source_width - $overlay_width)/2,
        ($source_height - $overlay_height)/2,
        0,
        0,
        $overlay_width,
        $overlay_height,
        WATERMARK_OVERLAY_OPACITY
    );
		
	}
	
	function merge2()
	{
	$source_width =imagesx($this->image);
	$source_height =imagesy($this->image);
	
	$overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY2_IMAGE);
	
	$overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);
	
	imagecolortransparent($overlay_gd_image,imagecolorat($overlay_gd_image,0,0));
	imagecopymerge(
        $this->image,
        $overlay_gd_image,
        ($source_width - $overlay_width)/2,
        ($source_height - $overlay_height)/2,
        0,
        0,
        $overlay_width,
        $overlay_height,
        WATERMARK_OVERLAY_OPACITY
    );
		
	}
	
	
   function load($filename) {
	  $filepath=$filename;
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=IMG_COMPRESSION, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   
   function save2($filename, $image_type, $compression=IMG_COMPRESSION, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   } 
	
	/* Function by 3magine ( http://www.daniweb.com/members/506079/3magine ). Slightly modified. */
	/* upload_food_pic.php is complete. Complete the function below */
	function cropImage($dest_width, $dest_height,$do_centering=false)
	{
		$width = imagesx($this->image);
		$height = imagesy($this->image);

		$original_aspect = $width / $height;
		$thumb_aspect = $dest_width / $dest_height;
		
		if ( $original_aspect >= $thumb_aspect )
		{
		   // Too wide
		   $src_height = $height;
		   $src_width = $height * $thumb_aspect;
		   
		   $org_prop_resized_height=$dest_height;
		   $org_prop_resized_width=$dest_height*$original_aspect;
		}
		else
		{
		   // Too tall
		   $src_width = $width;
		   $src_height = $width / $thumb_aspect;
		   
		   $org_prop_resized_width=$dest_width;
		   $org_prop_resized_height=$dest_width/$original_aspect;
		}

		$thumb = imagecreatetruecolor( $dest_width, $dest_height );
		
		// Resize and crop
		if(!$do_centering)
		{
			imagecopyresampled($thumb,$this->image,0,0,0,0,$dest_width, $dest_height,$src_width, $src_height);
		}
		else
		{
			//Do a centered crop
			imagecopyresampled($thumb,$this->image,0,0,($width-$src_width)/2,($height-$src_height)/2,$dest_width, $dest_height,$src_width, $src_height);
		}
		
		$this->image=$thumb;
	}
 
}



?>