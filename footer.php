<div id="footer">
			<div id="wrapper">
			<div id="footer_left">
			<img src="<?php echo BASE_URL_2;?>images/logo_footer.png" style="display:inline; margin-top:20px; float:left;">
				<ul>
					<li <?php echo ($title=='home')?'class="footer_menu_current"':''; ?> ><a href="<?php echo get_page_url('home');?>">Food</a></li>
					<?php
						if(is_logged_in())
						{
					?>
					<li <?php echo ($title=='profile')?'class="footer_menu_current"':''; ?> ><a href="<?php echo get_page_url('profile',array('uid'=>$_SESSION['unique_id']));?>">Profile</a></li>
					<?php
						}
					?>
					<li <?php echo ($title=='dine')?'class="footer_menu_current"':''; ?> ><a href="http://foodiescompass.wordpress.com/" target="_blank">Blog</a></li>
					<li <?php echo ($title=='about')?'class="footer_menu_current"':''; ?> ><a href="<?php echo get_page_url('about');?>">About</a></li>
					<li><a href="<?php echo get_page_url('followers');?>"  <?php echo ($title=='Foodies')?'class="a_selected"':''; ?>>Foodies</a></li>
					<li><a href="<?php echo get_page_url('faq');?>"  <?php echo ($title=='FAQs')?'class="a_selected"':''; ?>>FAQs</a></li>
					
					
				</ul>
			</div>
			
			<div id="footer_right">
				<div id="copyright">
					&copy; 2012 - All Right Reserved by FoodiesCompass.
					<br/><a href="<?php echo BASE_URL_2?>tnc.pdf" style="color:inherit" target="_blank">Terms &amp; Conditions</a> 
				</div>
				<div id="bread_img"></div>
				
			</div>
			</div>
	</div>