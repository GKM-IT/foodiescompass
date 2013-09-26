<?php	
require_once('config.inc.php');
?>
<div id="fb-root"></div>
<script src="//connect.facebook.net/en_US/all.js"></script>
<script>

FB.init({
	appId      : '<?php echo FB_APP_ID ?>', // App ID
	channelUrl : '<?php echo BASE_URL; ?>/channel.php', // Channel File
	status     : true, // check login status
	cookie     : true, // enable cookies to allow the server to access the session
	xfbml      : true  // parse XFBML
});



/* If user is logged in and has authorized the app, log him in directly. */
FB.getLoginStatus(function(response) 
{	
	if(response.status === 'connected')
	{
		/* If user is currently logged in using Facebook */
		$.ajax({
			url: "auth/fb_direct_login.php",
			data: {'id':response.authResponse.userID},
			success: function(data1){
				var json=eval('('+data1+')');
				console.log(json.msg);
				if(json.status=='success')
				{
					window.location.replace("<?php echo get_page_url('home');?>");
				}
			}
		});
		
	}
});
</script>