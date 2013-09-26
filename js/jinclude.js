$(document).ready(function(){

/* Function to check URL */
var old_hash="";
function checkURL()
{
	
	var new_hash=window.location.hash;
	if(old_hash != new_hash)
	{
		if(new_hash=='login_popup')
		{
			$('.login_popup').click();
		}
		
		old_hash=new_hash;
	}
	
	
		
	
}
checkURL();

/* On focus and blur events of registration form. */
$(".input1,.input2").focus(function(){
	if($(this).val() == $(this).attr("title")){
		$(this).val("");
		
	}
}).blur(function(){
	if($(this).val() == "" && !($(this).hasClass('input_real')) ){
		$(this).val($(this).attr("title"));
	}
});


/* On focus and blur events of dummy password fields */
$(".input_dummy").focus(function(){
	$(this).hide().next(".input_real").show().focus();
});

$(".input_real").blur(function(){
	if($(this).val()==""){
		$(this).hide().prev(".input_dummy").show();
	}
});

/* Hide popup on click outside box */
$('#popup_container').click(function(e) {
    if (e.target.id === "popup_container"){
        hide_popup();
    }
});

/* Hide popup on click on close button. */
$("#popup_close").click(function(){
	hide_popup();
});

/* Show popup */
$(".login_popup").click(function(){

	$("#popup_container").fadeIn("fast");
});
function show_popup()
{
$("#popup_container").fadeIn("fast");
}

function hide_popup()
{
	$('#popup_container').fadeOut("fast");
	console.log("Fading out...");
}

$("#profile_pic_container").click(function(event){
	var display=$("#profile_menu").css('display');
	if(display=='block')
	{
		$("#profile_menu").css('display','none');
		$("#profile_pic_container").css("border-color","transparent");
		$("#profile_pic_container").css("background","#faae40");
	}
	else
	{
		$("#profile_menu").css('display','block');
		$("#profile_pic_container").css("border-color","#594b42");
		$("#profile_pic_container").css("background","#fff");
	}
	event.stopPropagation();
});

/* Hide menus if visible */
$('html').click(function() {
 var display=$("#profile_menu").css('display');
	if(display=='block')
	{
		$("#profile_menu").css('display','none');
		$("#profile_pic_container").css("border-color","transparent");
		$("#profile_pic_container").css("background","#faae40");
	}
 });
 
 $('li.star').mouseover(function(){
	var value=$(this).attr('value');
	//console.log('Mouse on '+value);
	var stars = $(this).parent("ul").children('li.star');
	$.each(stars,function(){
		var value2=$(this).attr('value');
		if(value2<=value)
			$(this).removeClass().addClass('star1').addClass('star');
		else
			$(this).removeClass().addClass('star2').addClass('star');
	});
 });
 
 $('li.star').click(function(){
	var new_value=$(this).attr('value');
	$(this).siblings('li.hidden').attr('value',new_value);
	//alert("Value changed to "+new_value);
 });
 
 $('ul.star_panel').mouseleave(function(){
	var value=$(this).children("li.hidden").attr('value');	// Take value from hidden list-item
	var stars=$(this).children("li.star");
	$.each(stars,function(){
		var value2=$(this).attr('value');
		if(value2<=value)
			$(this).removeClass().addClass('star1').addClass('star');
		else
			$(this).removeClass().addClass('star2').addClass('star');
	});
	
 });


}); /* End of document.ready  */

function show_scroll(data)
{
	$('#scroll').html(data);
	$('#scroll').slideDown(400,function(){
		setTimeout(hide_scroll,3000);
	});
}

function hide_scroll()
{
	$('#scroll').slideUp(400);
}

function set_cookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function get_cookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name)
		{
		return unescape(y);
		}
	}
	return false;
}