<?php
/**
 * Template for showing activity on profile.php
 * 
 * Expcets $imagepath,$description and $timestamp are set before this file is included.
 */
?>
<div class="activity_item">
	<div class="activity_pic">
		<img  src="<?php echo $imagepath;?>">
	</div>
	<div class="activity_about">
		<!-- <div class="activity_delete_btn" title="Delete activity"></div> -->
		
					<p class=" food_name" style="float:left; margin-top:2px;line-height:1.5;">
						<?php echo $description; ?><br>
						<font style="font-family:arial; font-size:11px; font-style:italic;"><?php echo time_text($timestamp);?></font>
					</p>
				
	</div>
	<div class="clearfix"></div>
</div>