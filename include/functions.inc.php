<?php
	
	
	function most_popular_food($limit)
	{
		$input = array();
		$tot = round($limit*1.5);
		$query = mysql_query("select food_items_id from food_items where dish_image != 'sample_food.jpg' order by likes desc limit ".$tot);
		while($food = mysql_fetch_array($query))
		{
			array_push($input, $food["food_items_id"]);
		}
		$rand_keys = array_rand($input, $limit);
		
		for($i =0; $i<$limit; $i++)
		{
			$restr_query = mysql_query("SELECT food_items.food_items_id, food_items.dish_url, food_items.dish_name, restaurant.res_name, restaurant.res_address, restaurant.res_city
FROM food_items
INNER JOIN restaurant
ON food_items.rest_id=restaurant.rsID where food_items.food_items_id='".$input[$rand_keys[$i]]."'");
			$restr = mysql_fetch_array($restr_query);
			$url = get_page_url("food",array("eid"=>$restr["dish_url"]));
			echo '<a href="'.$url.'" style="color:inherit" id="food_item_link"><div id="food_item">';
			
			
					echo '<div id="food_pic"><img src="'.get_page_url("food_pic",array("fid"=>$restr["food_items_id"], "size"=>"thumb")).'"></div>';
				
			
			echo '<div id="food_about">';
			echo '<h1 class="food_name">'.ucwords($restr["dish_name"]).'</h1>
				<h2 class="food_place_mp">'.stripslashes($restr["res_name"]);
				//if($restr["res_address"]) echo 	','.stripslashes($restr["res_address"]);
				if($restr["res_city"]) echo ','.$restr["res_city"].'</h2>';
				
				echo '
				</div>
					
					<div class="clearfix"></div>
			</div></a>';
						
				
		}
	
	}
	
	function most_recent_food($limit)
	{
		$input = array();
		$tot = round($limit*2);
		$query = mysql_query("select food_items_id from food_items where dish_image != 'sample_food.jpg' order by food_items_id desc limit ".$tot);
		while($food = mysql_fetch_array($query))
		{
			array_push($input, $food["food_items_id"]);
		}
		$rand_keys = array_rand($input, $limit);
		//print_r($input);
		for($i =0; $i<$limit; $i++)
		{
			
			
			$restr_query = mysql_query("SELECT food_items.food_items_id,food_items.dish_url,food_items.description, food_items.likes,food_items.dislikes, food_items.dish_image, food_items.dish_name, restaurant.res_name, restaurant.res_address, restaurant.res_city
FROM food_items
INNER JOIN restaurant
ON food_items.rest_id=restaurant.rsID where food_items.food_items_id='".$input[$rand_keys[$i]]."'");
			$restr = mysql_fetch_array($restr_query);
			$url = get_page_url("food",array("eid"=>$restr["dish_url"]));
			echo '
			<div id="recent_item">
				<div id="recent_item_pic"><a href="'.$url.'" style="color:inherit"><img src="'.get_page_url("food_pic",array("fid"=>$restr["food_items_id"], "size"=>"thumb")).'"/></a></div>
				<div id="recent_item_description">
					<a href="'.$url.'" style="color:inherit"><div id="item_name"><h1 class="food_name">'.ucwords($restr["dish_name"]).'</h1> at&nbsp;&nbsp;<span class="food_place">'.stripslashes($restr["res_name"]).'</span></a>
					</div>
					<div id="item_description">'.substr($restr["description"],0,50).' ..</div>';
			
			$str_like = render_like_pair($restr['food_items_id']);
			echo $str_like;
			
			echo '<div style="width:120px; float:right;margin-top:5px;">
				<div class="likes" id="like_'.$restr['food_items_id'].'">
				<div style="width:';
									
									$data1 = floor(1+40*$restr['likes']/($restr['likes']+$restr['dislikes']+1));
									echo $data1;
												
												echo 'px; background:#3db54a; height:6px; float:left;"></div><br>'.$restr['likes'].'<input type="hidden" id="initial_like_'.$restr['food_items_id'].'" value="'.$restr["likes"].'">
												</div>
												<div class="dislikes" id="dislike_'.$restr['food_items_id'].'" style="margin-left:5px;width:80px;">
													<div style="width:';
													$data2 = floor(1+40*$restr['dislikes']/($restr['likes']+$restr['dislikes']+1));
													
													
													echo $data2.'px; background:#ed2224; height:6px;float:left;"></div><br>'.$restr['dislikes'].'<input type="hidden" id="initial_dislike_'.$restr['food_items_id'].'" value="'.$restr["dislikes"].'">
												</div>
											</div>
											';
			//echo'		<div style="float:right; font-size:9px; color:#5A4B43; margin-top:5px;">(			<span style="color:#3db54a">'.$restr['likes'].' likes</span>, <span style="color:#ed2224">'.$restr['dislikes'].' dislikes</span>			)</div>';
			
			echo'
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div></div>';
		}
	}
	
	function followers($limit)
	{
		$input = array();
		$tot = round($limit*2); 
		$query = mysql_query("SELECT user_id FROM userinfo ORDER BY user_id desc LIMIT ".$tot);
		while($user = mysql_fetch_array($query))
		{
			array_push($input, $user["user_id"]);
		}
		$rand_keys = array_rand($input, $limit);
		
		for($i =0; $i<$limit; $i++)
		{
			
			$query = "SELECT user_id,unique_id,name,username, points, badges,followers,follows,about_me FROM userinfo where user_id='".$input[$rand_keys[$i]]."' limit 1";
			//echo $query;
			$user_query = mysql_query($query);
			$top_follower = mysql_fetch_array($user_query);
			$num_followers=($top_follower["followers"]=="")? 0: count(explode(",",$top_follower["followers"]));
			$num_follows=($top_follower["follows"]=="")? 0: count(explode(",",$top_follower["follows"]));
			$num_badges=($top_follower["badges"]=="")? 0: count(explode(",",$top_follower["badges"]));
			?>
			<div id="follower_item">
							<div id="follower_pic">
								<a href="<?php echo get_page_url("profile",array("uid"=>$top_follower["unique_id"]));?>" style="border:none;">
									<img src="<?php echo get_page_url("profile_pic",array("user_id"=>$top_follower["user_id"])); ?>">
								</a>
							</div>
							<div id="follower_about">
							<h1 class="food_name" style="width:150px;">
								<a href="<?php echo get_page_url("profile",array("uid"=>$top_follower["unique_id"]));?>" style="border:none;">
									<?php echo ($top_follower["name"]) ? $top_follower["name"] : $top_follower["username"] ;?>
								</a>
							</h1>
							<span class="italic_text"><?php echo limit_words($top_follower["about_me"],5);?></span>
								<p class="followers_stats">
								<?php echo $num_follows;?> Following
								<?php echo $num_followers;?> Followers<br></p>
								<?php render_follow_button($top_follower["user_id"]);?>
								
								</div>
								<div id="follower_badge">
								<div style="float:left">
									<div style="position: absolute;

margin: 24px 0 0 1px;
text-align: center;
width: 58px; color:#fff; font-weight:bold; font-size:11px;"><?php echo $top_follower["points"];?></div>
									<img src="<?php echo BASE_URL_2?>images/icon_star.png">	
								</div>
								<div style="float:left; margin-top:15px; margin-left:3px">
								<div style="position: absolute;

margin: 13px 0 0 0px;
text-align: center;
width: 35px; color:#fff; font-weight:bold; font-size:11px;"><?php echo $num_badges;?></div>
								<img src="<?php echo BASE_URL_2?>images/profile_badge.png"></div>
								
								</div>
								
								<div class="clearfix"></div>
						</div>
			<?php
		}
	}
	
	function limit_words($string, $limit)
	{
		$len = strlen($string);
		if($len > $limit) return substr($string, 0 , $limit).'...';
		else return $string;
	
	
	}
	
	class highlight
	{
		public $output_text;
		function __construct($text, $words)
		{
			$split_words = explode( " " , $words );
			foreach ($split_words as $word)
			{	
				$color = self::generate_colors();
				$text = preg_replace("|($word)|Ui" ,
						   "<span style=\" padding:2px; color:#5A4B43; background:".$color.";\"><b>$1</b></span>" , $text );
			}
			$this->output_text = $text;
		}
		private function rgbhex($red, $green, $blue)
		{
			return sprintf('#%02X%02X%02X', $red, $green, $blue);
		}
		private function generate_colors()
		{
			$red = rand( rand(60,100) , rand(200,252) );
			$green = rand( rand(60,100) , rand(200,252) );
			$blue = rand( rand(60,100) , rand(200,252) );
			$color = self::rgbhex(254, 245 , 232 );
			return $color;
		}
	}
?>