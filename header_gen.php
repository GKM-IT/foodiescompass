
<div id="header">
	<div id="plate">
		<img src="images/plate.png">
	</div>
	<div id="blog">
		<img src="images/logo_blog.png">
	</div>
	<div id="logo">
		<a href="<?php echo BASE_URL_2.'';?>"><img src="images/logo.png"></a>
	</div>
	
	<?php 
		require_once 'main_menu.php';
	?>
	<div style="clear:both;"></div>
	<?php
		$request_url=$_SERVER['REQUEST_URI'];
	if($title)
	{
	?>
	<div class="page_heading advanced_search_heading" >
	<?php echo $title; ?>
	</div>
	<?php
	}
	else
	{
	?>
	<div id="text_block1_gen">
		
	</div>
	<?php } ?>
</div>