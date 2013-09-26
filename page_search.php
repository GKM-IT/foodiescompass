<?php

?>
<script type="text/javascript" src="<?php echo BASE_URL_2?>js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo BASE_URL_2?>css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<div id="page_search">
<form action="<?php echo get_page_url('map_search');?>" method="get">
						<div id="page_search_form_div">
							<input type="text" id="food_search" name="food_search" value="<?php echo (isset($_GET['food_search'])?$_GET['food_search']:FOOD_SEARCH_DEFAULT_VAL);?>" title="Food Item" class="input1 searchfood" style="display:inline; width:220px;"/>
							<span style="margin:0 5px 0 5px;"><b>IN</b></span>
							<input type="text" id="food_place_x" name="food_place" value="<?php echo (isset($_GET['food_place'])?$_GET['food_place']:FOOD_PLACE_DEFAULT_VAL);?>" title="Location/Place" class="input1 searchfood" style="display:inline; width:220px;"/>
<input type="hidden" id="food_place" name="food_place_x" value="<?php echo (isset($_GET['food_place'])?$_GET['food_place']:FOOD_PLACE_DEFAULT_VAL);?>" title="Location/Place" class="input1 searchfood" style="display:inline; width:220px;"/>							
							<input type="hidden" id="food_lat" name="food_lat"  style="display:inline; width:220px;" value="<?php echo $_GET['food_lat'];?>"/>	
							<input type="hidden" id="food_lng" name="food_lng" style="display:inline; width:220px;" value="<?php echo $_GET['food_lng'];?>"/>	
							
							<input type="hidden" id="food_page" name="food_page" style="display:inline; width:220px;" value="<?php echo $_GET['p'];?>"/>	
							
							<input type="hidden" id="food_place_name" name="food_place_name" style="display:inline; width:220px;" value="<?php echo $_GET['food_place_name'];?>"/>
							
							<input type="hidden" id="food_locality" name="food_locality" style="display:inline; width:220px;" value="<?php echo $_GET['food_locality'];?>"/>
							
							<input type="hidden" id="food_city" name="food_city"style="display:inline; width:220px;" value="<?php echo $_GET['food_city'];?>"/>
							
							<input type="hidden" id="user_place_name" name="user_place_name"style="display:inline; width:220px;" value=""/>
							<input type="hidden" id="user_lat" name="user_lat" style="display:inline; width:220px;" value=""/>
							
							<input type="hidden" id="user_lng" name="user_lng" style="display:inline; width:220px;" value=""/>
							<input type="submit" id="search_submit" value="" align="right" style="margin-top:2px; display:inline;" />
						</div>
						
						<div id="page_upload_pic">
							<?php get_upload_link();?>
								<img src="<?php echo BASE_URL_2?>images/button_upload_photos_small.png" align="right" style="margin-top:2px; display:inline;" >
							</a>
						</div>
</form>					
</div>
<script type="text/javascript">
		var options = {
			script:"<?php echo BASE_URL_2?>ajax/autosuggest.php?json=true&limit=10&",
			varname:"input",
			json:true,
			delay: "100",
			timeout: "20000",
			shownoresults:false,
			maxresults:10,
			callback: function (obj) { document.getElementById('testid').value = obj.id; }
		};
		var as_json = new bsn.AutoSuggest('food_search', options);
	
		var options1 = {
			script:"<?php echo BASE_URL_2?>ajax/autosuggest_place.php?json=true&limit=10&",
			varname:"input",
			json:true,
			delay: "100",
			timeout: "20000",
			shownoresults:false,
			maxresults:10,
			callback: function (obj) { document.getElementById('testid').value = obj.id; }
		};
		var as_json = new bsn.AutoSuggest('food_place_x', options1);
	
</script>