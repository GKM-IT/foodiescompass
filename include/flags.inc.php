<?php
	/* ajax_signup.php */
	define('SIGNUP_PSWD_MISMATCH',0);
	define('SIGNUP_AGE_NAN',1);
	define('SIGNUP_AGE_INVALID',2);
	define('SIGNUP_EMAIL_INVALID',3);
	define('SIGNUP_EMAIL_DUPLICATE',4);
	define('SIGNUP_USERNAME_DUPLICATE',5);
	define('SIGNUP_TERMS_DISAGREE',6);
	
	define('SIGNUP_FORM_INCOMPLETE',7);
	define('SIGNUP_SUCCESS',8);
	define('SIGNUP_DB_ERROR',9);
	define('SIGNUP_FORM_HAS_ERRORS',10);
	
	/* pop_up.php */
		
	define('SIGNUP_NAME_DEFAULT_VAL','Foodies Name');
	define('SIGNUP_EMAIL_DEFAULT_VAL','Email');
	define('SIGNUP_PSWD1_DEFAULT_VAL','');
	define('SIGNUP_PSWD2_DEFAULT_VAL','');
	define('SIGNUP_AGE_DEFAULT_VAL','Age');
	define('SIGNUP_CITY_DEFAULT_VAL','City');
	
	define('SIGNIN_EMAIL_DEFAULT_VAL','Email');
	define('SIGNIN_PSWD_DEFAULT_VAL','');
	
	define('SIGNIN_VALIDATION_ERR',0);
	define('SIGNIN_FORM_INCOMPLETE',1);
	
	/* Map search */
	define('FOOD_SEARCH_DEFAULT_VAL','Food Item');
	define('FOOD_PLACE_DEFAULT_VAL','Location/Place');
	define('NUM_SEARCH_RESULTS',7);		// Number of search results to display at a time
	
	define('SEARCH_OK',0);
	define('NO_FOOD_ITEM_ERROR',1);
	define('NO_FOOD_PLACE_ERROR',2);
	define('INVALID_PAGE_ERROR',3);
	define('DB_SEARCH_ERROR',4);
	
	/* General flags */
	define('SUCCESS',-1);
	define('SUCCESS2',-2);
	define('DB_ERROR',10);
	define('NO_SESSION_EXISTS_ERROR',11);	// The user is not logged in
	define('SEARCH_ERROR',12);   // Issued whenever a search is made in the DB and no match is found
	define('SELF_FOLLOW_ERROR',13);   // When current user tries to follow himself
	define('INSUFFICIENT_PARAMS_ERROR',14);   // Enough parameters were not passed to ajax page as GET data
	define('DB_UPDATE_ERROR',15);   // Enough parameters were not passed to ajax page as GET data
	define('USER_FOLLOWED',16);   // User successfully followed
	define('USER_UNFOLLOWED',17);   //  User successfully unfollowed
	
	/* Map search */
	define('ACTION_LIKE',18);
	define('ACTION_DISLIKE',19);
	define('DUPLICATE_LIKE',20);
	define('DUPLICATE_DISLIKE',21);
	define('ASK_USER_LOGIN',22);
	
	/* postmaster.php */
	define('SIGNUP_MAILER',23);
	
	/* Profile.php */	

	define('NO_MORE_ACTIVITES_ERROR',24);
	
	// User activity flags	** flags 25-55 reserved for activities**
	define('ACTIVITY_FOLLOWED',25);
	define('ACTIVITY_UNFOLLOWED',26);
	define('ACTIVITY_UPLOADED',27);
	
	//USER ACTIVITIES POINTS
	define('UPLOAD_POINTS', 3);
	define('LIKE_POINTS', 1);
	define('DISLIKE_POINTS', 1);
	define('COMMENT_POINTS', 2);
	define('ADD_PIC_POINTS', 2);
	define('ADD_RES_PIC_POINTS', 2);
	
	
	
	
	
	// Number of activities to show at a time
	define('NUM_ACTIVITIES_SHOWN_AT_A_TIME',5);
	
	/* Activity points  ** flags 56-100 reserved for this */
	//define('UPLOAD_POINTS',50);	// +50 points
	
?>