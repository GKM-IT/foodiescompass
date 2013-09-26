<?php
 /* Contains the part of the top menu that is present on each page. */
?>
<div id="login_area">
<?php
	if(is_logged_in())
	{
?>
	<div class="font_bold" style="font-size:10px;text-align:center;color:#ffffff;">
		Hello, <?php echo logged_username(); ?><br/>
		<a href="<?php echo get_page_url('logout');?>" style="color:#ffffff">Logout</a>
	</div>
<?php
	}
else	
	{
?>
<ul>
	<li><a class="login_popup" href="#login_popup">LOGIN</a></li>
	<li><a class="login_popup" href="#login_popup">REGISTER</a></li>
</ul>
<?php
	}
?>
</div>
<div id="mainmenu_area">
<ul id="main_menu_list">
	<li ><a href="#" class="a_selected">Home</a></li>
	<li><a href="#">Feed</a></li>
	<li><a href="#">About us</a></li>
</ul>
</div>
<div style="clear:both;"></div>