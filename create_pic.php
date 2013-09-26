<?php

	$remote_file = $_GET["pic"];
    $new_width = $_GET["w"];
    $new_height = $_GET["h"];
    list($width, $height) = getimagesize($remote_file);
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $end_chars1 = substr($remote_file, -4);
    $end_chars2 = substr($remote_file, -5);
         // echo $end_chars1;     
                if( $end_chars1 == '.jpg'|| $end_chars1 == '.JPG'|| $end_chars2 == '.jpeg')
                {
                        $image = imagecreatefromjpeg($remote_file);
                }
                else
                {
                        if($end_chars1 == '.png')
                        {
                                $image = imagecreatefrompng($remote_file);
                        }
                        else
                        {
                                if($end_chars1 == '.gif')
                                {
                                        $image = imagecreatefromgif($remote_file);
                                }
                               
                        }
                }       
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    /*if( $end_chars1 == '.jpg'|| $end_chars1 == '.JPG'|| $end_chars2 == '.jpeg')
                {
                         header('Content-Type: image/jpeg'); 
                }
                else
                {
                        if($end_chars1 == '.png')
                        {
                                header('Content-Type: image/png'); 
                        }
                        else
                        {
                                if($end_chars1 == '.gif')
                                {
                                       header('Content-Type: image/gif'); 
                                }
                               
                        }
                }*/
    imagejpeg($image_p, NULL, 100);
    imagedestroy($image_p);

?>
