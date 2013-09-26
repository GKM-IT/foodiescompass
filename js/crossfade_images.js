
var $i=0;
var $active,$next,$active_menu,$next_menu,$active_text,$next_text;
function cycleImages(){
	
      //image
	   $active = $('#cycler .active');
       $next = ($active.next().length > 0) ? $active.next() : $('#cycler div:first');
	  //thumbs
	   $active_menu = $('#slider_thumbs .current_slide');
	   $next_menu = ($active_menu.next().length > 0) ? $active_menu.next() : $('#slider_thumbs li:first');
	  //text
	   $active_text = $('#menu_text .show');
	   $next_text = ($active_text.next().length > 0) ? $active_text.next() : $('#menu_text li:first');
      
	  $next.css('z-index',2);//move the next image up the pile
	  
	  $active.fadeOut(1000,function(){//fade out the top image
	  
	 
	  $active.css('z-index',1).show().removeClass('active');//reset the z-index and unhide the image
      $next.css('z-index',3).addClass('active');//make the next image the top one
     
	 
	  });
	  
	  $active_text.fadeOut(1000,function(){//fade out the top image
	  
	 
	  $active_text.removeClass('show').addClass('hide');//reset the z-index and unhide the image
      $next_text.fadeIn('slow').removeClass('hide').addClass('show');//make the next image the top one
     
	 
	  });
	  
	 	  
	  $active_menu.animate(1000,function(){//fade out the top image
	  
		$active_menu.find('div.half_black').show().removeClass('hide');//reset the z-index and unhide the image
		$active_menu.removeClass('current_slide');
		
		$next_menu.find('div.half_black').hide().addClass('hide');//reset the z-index and unhide the image
		$next_menu.addClass('current_slide');
     
		
      //$next_menu.addClass('hide');//make the next image the top one
	 
	  });
	  
	  	  
    }


    $(window).load(function(){
      // run every 5s
	 
	  $('#cycler div').show();
      var inter = setInterval('cycleImages()', 5000);
	  
	  $('#slider_thumbs .half_black').click(function(){
				var $i = $(this).attr('rel');
				
				clearInterval(inter);//stop the timer
				
				var container = $('#cycler');
				container.find('div').css('z-index',1);
				
				//set the clicked image to the top
				container.find('div.active').removeClass('active').css('z-index',1);
				container.find('div').eq($(this).attr('rel')).css('z-index',4).addClass('active');
				
				$('#slider_thumbs').find('li').removeClass('current_slide');
				$('#slider_thumbs').find('li').eq($(this).attr('rel')).addClass('current_slide');
				
				//if the active image is not the clicked image, remove the active class 
				$('#slider_thumbs').find('li div.half_black').show();
				$('#slider_thumbs').find('li div.half_black').eq($(this).attr('rel')).hide();
								
				$('#menu_text').find('li').hide().removeClass('show');
				$('#menu_text').find('li').addClass('hide');
				$('#menu_text').find('li').eq($(this).attr('rel')).show().removeClass('show').addClass('show');
				
				inter = setInterval('cycleImages()', 5000);
				return false;

	});
    
	
	
	})
