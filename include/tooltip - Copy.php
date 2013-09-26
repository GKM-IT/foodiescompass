<script type="text/javascript">

$(document).ready(function() {

	var style = 'easeOutExpo';
	//Select all anchor tag with rel set to tooltip
	$('a[rel=tooltip]').mouseover(function(e) {
		
		//Grab the title attribute's value and assign it to a variable
		var tip = $(this).attr('title');	
		
		//Remove the title attribute's to avoid the native tooltip from the browser
		$(this).attr('title','');
		
		//Append the tooltip template and its value
		$(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div><div class="tipFooter"></div></div>').animate({duration:500, easing: style});	;		
				
		//Show the tooltip with faceIn effect
		var default_top = $(this).height();
		var default_left = ($(this).width() -180)/2;
		$('#tooltip').fadeIn('500');
		$('#tooltip').fadeTo('10',0.9);
		$('#tooltip').css('top', default_top );
		$('#tooltip').css('left', default_left );
		
	}).mouseout(function() {
	
		//Put back the title attribute's value
		$(this).attr('title',$('.tipBody').html());
	
		//Remove the appended tooltip template
		$(this).children('div#tooltip').remove();
		
	});

});

</script>

<style>


/* Tooltip */

#tooltip {
	position:absolute;
	z-index:9999;
	color:#fff;
	font-size:10px;
	width:180px;
	
}

#tooltip .tipHeader {
	border:1px solid #f00;
	height:8px;
	background:url(images/tipHeader.gif) no-repeat;
}


#tooltip .tipBody {
	background-color:#000;
	padding:5px 5px 5px 15px;
}

#tooltip .tipFooter {
	height:8px;
	background:url(images/tipFooter.gif) no-repeat;
}

</style>