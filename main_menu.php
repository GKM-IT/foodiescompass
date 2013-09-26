<?php
 /* Contains the part of the top menu that is present on each page. */
 
 require_once("include/lib.inc.php");
 require_once("include/session.inc.php");
 $_SESSION["current_page"] = 'http://'.$_SERVER["SERVER_NAME"].''.$_SERVER["REQUEST_URI"];
?>
<div id="login_area">
<?php

	if(is_logged_in())
	{
?>
		<div id="profile_pic_container" >
			<div id="profile_pic" style="background:url('<?php echo get_page_url("profile_pic",array("user_id"=>$_SESSION["id"]))?>') no-repeat center center;"></div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>		
		<ul id="profile_menu" style="margin:0px 0px 0px 0px;padding:0px;">
			<li style="border-width:0px; border-left-width:1px; border-right-width:1px; border-style:solid;border-color:#594b42;">
				<a id="set_location" href="javascript:void(0);">Set location...</a>
			</li>
			<li style="border-width:0px; border-left-width:1px; border-right-width:1px; border-style:solid;border-color:#594b42;">
				<a href="<?php echo get_page_url('profile',array('uid'=>$_SESSION['unique_id']));?>">Profile</a>
			</li>
			<li style="border-width:0px; border-left-width:1px; border-right-width:1px; border-style:solid;border-color:#594b42;">
				<a href="<?php echo get_page_url('edit_profile');?>">Profile settings</a>
			</li>
			<li style="border-width:0px; border-left-width:1px; border-right-width:1px; border-bottom-width:1px; border-style:solid;border-color:#594b42;">
				<a href="<?php echo get_page_url('logout');?>">Logout</a></li>
		</ul>
<?php
	}
else	
	{
?>
<ul>
	<li><a class="login_popup" href="javascript:void(0);">LOGIN</a></li>
	<li><a class="login_popup" href="javascript:void(0);">REGISTER</a></li>
</ul>
<?php
	}
?>
</div>
<!-- <div id="mainmenu_area">  -->
<ul id="main_menu_list">
	<li ><a href="<?php echo get_page_url('home');?>" <?php echo ($TITLE=='home')?'class="a_selected"':''; ?> >Food</a></li>
	<li><a href="<?php echo get_page_url('followers');?>"  <?php echo ($title=='Foodies')?'class="a_selected"':''; ?>>Foodies</a></li>
	<li><a href="<?php echo get_page_url('about');?>"  <?php echo ($title=='About')?'class="a_selected"':''; ?> >About</a></li>
</ul>
<!-- </div> -->
<div style="clear:both;"></div>