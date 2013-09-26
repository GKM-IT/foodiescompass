<?php 
/**
 * The pop-up that is displayed for login/signin
 * 
 * @modified: 18/12/12
 * @changes : Now containes the location scroll as well.
 */

?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAlQYf89csqKsLvfVCa47155gT9qBkdi-w&sensor=true&libraries=places"></script>

<div id="location_scroll">
	<div class="wrapper">
		<table style="margin:0 auto">
			<tr>
				<td>
					<span id="location_scroll_msg" style="color:#F9AE44; font-family:Quicksand_bold;"></span>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td>
					<input id="location2" class="input3" style="color:#fff;font-family:Quicksand_Book;" value="Loading..." disabled="disabled"/>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td>
					<a id="loc_change_btn" class="button2">Change</a>
					<a id="loc_save_btn" class="button2" style="display:none;">Save</a>
				</td>
			</tr>
		</table>
		<div id="location_map"></div>
	</div>
	<img id="loc_scroll_close" src="<?php echo BASE_URL_2; ?>images/popup_close_button.png" />
</div> <!--  End of location scroll -->


<div id="popup_container">
	<div id="reg_popup">
		<div id="popup_left">
			
			<span class="font_bold" style="font-size:30px;">Sign In</span>
			<br/><br/>
			<div id="login_error_display">
			</div>
			<!-- Login form -->
			<form name="signin_form" action="" method="GET">
				<input type="text" id="signin_email" name="signin_email" value="<?php echo SIGNIN_EMAIL_DEFAULT_VAL;?>" title="<?php echo SIGNIN_EMAIL_DEFAULT_VAL;?>" class="input1" />
				<div id="email_err2" class="err2"></div>
				<!-- Password field is inially represented by a dummy text field. -->
				<input type="text" id="signin_pswd_dummy" name="signin_pswd_dummy" value="Password" title="Password" class="input1 input_dummy" />
				<input type="password" id="signin_pswd" name="signin_pswd" value="<?php echo SIGNIN_PSWD_DEFAULT_VAL;?>" class="input1 input_real" title="Password" style="display:none;" />
				<div id="pswd_err2" class="err2"></div>
				<a href="<?php echo get_page_url("forgot");?>" style="font-size:11px;color:#594b42; font-family:Quicksand_bold;">Forgot password?</a>
				
				<input type="hidden" name="after_signin" value="magick"/>
				<br/><br/>
				<input id="login_btn" type="button" value="Sign In" class="button1"/> &nbsp;&nbsp;&nbsp;
				<img id="signin_preloader" src="<?php echo BASE_URL_2; ?>images/preloader_yellow.gif" style="display:none;vertical-align:middle;"/>
			</form>
			<!-- Login form ends -->
	
			<div class="clearfix"></div>
		</div>
		
		<div id="popup_right">
			<div style="height:30px;">
				<img id="popup_close" style="float:right;cursor:pointer;margin-top:15px;" src="<?php echo BASE_URL_2; ?>images/popup_close_button.png" title="Close"/>
			</div>
			<span class="font_bold" style="font-size:30px;">Sign Up</span>
			<br/><br/>
			
			<!-- Login using Facebook or Twitter -->
			<a id="popup_fb_signin" href="https://www.facebook.com/dialog/oauth?client_id=<?php echo FB_APP_ID;?>
						&amp;redirect_uri=<?php echo get_page_url("fb_auth");?>
						&amp;scope=email,user_birthday
						&amp;state=hello"></a>
			<br/>
			<a id="popup_twitter_signin" href="<?php echo get_page_url('twitter_redirect');?>"></a>
			
			<br/>
			<span class="font_bold" style="font-size:30px;">OR</span>
			<br/>
			
			<div id="error_display" class="err">
			</div>
			
			<!-- Signup form-->
			<form name="signup_form" id="signup_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<table>
				<tr>
					<td>
						<input type="text" id="signup_name" name="signup_name" value="<?php echo SIGNUP_NAME_DEFAULT_VAL; ?>" class="input1" title="<?php echo SIGNUP_NAME_DEFAULT_VAL; ?>"/>
						<div id="name_err" class="err"></div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" id="signup_email" name="signup_email" value="<?php echo SIGNUP_EMAIL_DEFAULT_VAL; ?>" class="input1" title="<?php echo SIGNUP_EMAIL_DEFAULT_VAL; ?>"/>
						<div id="email_err" class="err"></div>
					</td>
					<td id="email_check">
					</td>
				</tr>
				<tr>
					<td>
					<!-- Password 1 dummy field -->
					<input type="text" id="signup_pswd1_dummy" name="signup_pswd1_dummy" value="Password" class="input1 input_dummy" title="Password"/>
					<input type="password" id="signup_pswd1" name="signup_pswd1" class="input1 input_real" value="<?php echo SIGNUP_PSWD1_DEFAULT_VAL; ?>" title="Password" style="display:none;"/>
					<!-- -->
					</td>
					<td class="pswd_check">
					</td>
					
				</tr>
				<tr>
					<td>
					<!-- Password 2 dummy field -->
					<input type="text" id="signup_pswd2_dummy" name="signup_pswd2_dummy" value="Re-enter Password" class="input1 input_dummy" title="Password"/>
					<input type="password" id="signup_pswd2" name="signup_pswd2" class="input1 input_real" value="<?php echo SIGNUP_PSWD2_DEFAULT_VAL; ?>" title="Re-enter Password" style="display:none;"/>
					<div id="pswd_err" class="err"></div>
					<!---->
					</td>
					<td class="pswd_check">
					</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<input type="text" id="signup_age" name="signup_age" class="input1"  value="<?php echo SIGNUP_AGE_DEFAULT_VAL; ?>" style="width:50px;" title="<?php echo SIGNUP_AGE_DEFAULT_VAL; ?>"/>
								</td>
								<td id="age_check" style="width:20px;">								
								</td>
								<td>
									 &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" class="radio1" id="signup_sex_m" name="signup_sex" value="male" checked="true"/> <label style="font-size:20px;" for="signup_sex_m">M</label> &nbsp;&nbsp; 
									<input type="radio" class="radio1"  id="signup_sex_f" name="signup_sex" value="female"/> <label style="font-size:20px;" for="signup_sex_f">F</label>
								</td>
							</tr>
						</table>
						<div id="age_err" class="err"></div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" id="signup_city" name="signup_city" value="<?php echo SIGNUP_CITY_DEFAULT_VAL; ?>" class="input1" title="<?php echo SIGNUP_CITY_DEFAULT_VAL; ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" class="radio1" id="signup_terms" name="signup_terms" value="agreed" /> <label style="font-size:12px;color:#ffffff;" for="signup_terms">By signing up you agree to our Terms of Service and Privacy Policy.</label>
						<div id="terms_err" class="err"></div>
					</td>
				</tr>
				<tr>
					<td>
						<br/>
					</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="after_signup" value="magick"/>
						<input id="signup_submit" type="button" value="Registration" class="button2"/> &nbsp;&nbsp;&nbsp;&nbsp;
						<img id="signup_preloader" src="<?php echo BASE_URL_2; ?>images/preloader.gif" style="vertical-align:middle;display:none;"/>
					</td>
				</tr>
			</table>
			</form>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script src="js/jquery.simpletip-1.3.1.pack.js"></script>
<script>
</script>