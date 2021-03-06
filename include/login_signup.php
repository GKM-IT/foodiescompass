<?php
//Pop-up scripts are required only if user not logged in
if(!is_logged_in())
{
	
?>
	$("#signup_name").blur(function(){
		var name=$("#signup_name").val();
		
		if(name!='')
		{
			
		}
	});
	$("#signup_pswd2").blur(function(){
		var pswd1=$("#signup_pswd1").val();
		var pswd2=$("#signup_pswd2").val();
		
		if( pswd1!='<?php echo SIGNUP_PSWD1_DEFAULT_VAL;?>' || pswd2!='<?php echo SIGNUP_PSWD2_DEFAULT_VAL?>' )
		{
			//Either of the two passwords has been filled
			if(pswd1!=pswd2)
			{
				$(".pswd_check").html("<img class='pswd_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Passwords do not match'/>");
			}
			else
			{
				$(".pswd_check").html("<img class='pswd_check2' src='<?php echo BASE_URL_2;?>images/tick.png' title='Passwords match'/>");
			}
		}
	});
	
	$("#signup_email").blur(function(){
		var email=$("#signup_email").val();
		var pattern=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/
		if(email=="")
		{
			//Clear any check marks
			$("#email_check").html("");
		}
		else if(email!="" && !(email.match(pattern)) )
		{
			console.log("email="+email);
			console.log("Match:"+email.match(pattern))
			//Invalid email address
			$("#email_check").html("<img id='email_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Invalid email address'/>");		
		}
		else if( email!="" && (email.match(pattern)) )
		{
			//Valid email address
			$("#email_check").html("<img id='email_check2' src='<?php echo BASE_URL_2;?>images/tick.png' title='Valid email address'/>");
		}
	});
	
	$("#signup_age").blur(function(){
		var age=$("#signup_age").val();
		if(age=="")
		{
			//Clear any check marks
			$("#age_check").html(" ");
		}
		else if( isNaN(age) )
		{
			console.log(age);
			//Invalid age
			$("#age_check").html("<img id='age_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Age is not a number'/>");	
		}
	});
	
	$("#signup_submit").click(function(){
		$("#signup_preloader").css('display','inline');
		$.ajax({
		url: "<?php echo BASE_URL_2;?>ajax/ajax_signup.php",
		data: $('#signup_form').serializeArray(),
		success: function(data1){
			$("#signup_preloader").css('display','none');
			$(".input1").removeClass('has_error');
			$(".err").html('');
		
			var json=eval('('+data1+')');
			//alert(json.status);
			if(json.status=='failure')
			{
				for(i=0;i<json.err_code.length;i++)
				{
					if(json.err_code[i]==<?php echo SIGNUP_PSWD_MISMATCH;?>)
					{
						$("#pswd_err").html("Passwords don't match.");
						$("#signup_pswd1,#signup_pswd2").addClass('has_error');
						$(".pswd_check").html("<img class='pswd_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Passwords do not match'/>");
					}
					else if(json.err_code[i]==<?php echo SIGNUP_AGE_NAN;?>)
					{
						$("#age_err").html("Age must be numeric.");
						$("#signup_age").addClass('has_error');
					}
					else if(json.err_code[i]==<?php echo SIGNUP_AGE_INVALID;?>)
					{
						$("#age_err").html("You must be <?php echo MIN_AGE;?> or older to signup.");
						$("#signup_age").addClass('has_error');
						$("#age_check").html("<img id='age_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='You must be <?php echo MIN_AGE;?> or older to signup.'/>");	
					}
					else if(json.err_code[i]==<?php echo SIGNUP_EMAIL_INVALID;?>)
					{
						$("#email_err").html("Invalid email address.");
						$("#signup_email").addClass('has_error');
						$("#email_check").html("<img id='email_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Invalid email address'/>");	
					}
					else if(json.err_code[i]==<?php echo SIGNUP_EMAIL_DUPLICATE;?>)
					{
						$("#email_err").html("Email address already registered.");
						$("#signup_email").addClass('has_error');
						$("#email_check").html("<img id='email_check2' src='<?php echo BASE_URL_2;?>images/exclamation_mark.png' title='Email address already registered'/>");	
					}
					else if(json.err_code[i]==<?php echo SIGNUP_USERNAME_DUPLICATE;?>)
					{
						$("#name_err").html("Username already taken.");
						$("#signup_name").addClass('has_error');
					}
					else if(json.err_code[i]==<?php echo SIGNUP_TERMS_DISAGREE;?>)
					{
						$("#terms_err").html("You must agree to terms.");
					}
					else if(json.err_code[i]==<?php echo SIGNUP_FORM_INCOMPLETE;?>)
					{
						$("#error_display").html("Please fill out the form completely.");
					}
				}
					
			}
			else if(json.status=='success')
			{
				// Success...go to home
				window.location.href='<?php echo $_SESSION["current_page"];?>';
			}
		}
	});
		
	});
	
	$("#login_btn").click(function(){
	
		$(".err2").html();
		var email=$('#signin_email').val();
		var pswd=$('#signin_pswd').val();
			
		if(email=='<?php echo SIGNIN_EMAIL_DEFAULT_VAL;?>')
		{
			$("#email_err2").html("Enter email address.");
			$("#signin_email").addClass('has_error');
		}
		
		if(pswd=='<?php echo SIGNIN_PSWD_DEFAULT_VAL;?>')
		{
			$("#pswd_err2").html("Enter password.");
			$("#signin_pswd").addClass('has_error');
		}
		
		if( email!='<?php echo SIGNIN_EMAIL_DEFAULT_VAL;?>' && pswd!='<?php echo SIGNIN_PSWD_DEFAULT_VAL;?>')
		{
		$("#signin_preloader").css("display","inline");
		$.ajax({
		url: "<?php echo BASE_URL_2;?>ajax/ajax_login.php",
		data: {'signin_email':email,'signin_pswd':pswd},
		success: function(data1){
			$("#signin_preloader").css("display","none");
			var json=eval('('+data1+')');
			//alert(json.status);
			if(json.status=='success')
			{
				window.location.href='<?php echo $_SESSION["current_page"];?>';
			}
			else if(json.status=='failure')
			{
				console.log('here');
				if(json.err_code==<?php echo SIGNIN_VALIDATION_ERR; ?>)
				{
					$("#login_error_display").html('Email and password did not match.');
				}
				else if(json.err_code==<?php echo SIGNIN_FORM_INCOMPLETE; ?>)
				{
					$("#login_error_display").html('Please enter email and password.');
				}
			} 
		}
		});
		}
	});

<?php

}	// End of pop-up scripts (if(!is_logged_in()))
?>