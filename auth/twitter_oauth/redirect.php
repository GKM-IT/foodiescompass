<?php
/* Start session and load library. */
require_once("../../include/session.inc.php");
require_once('twitteroauth.php');
require_once('../../include/config.inc.php');

/* Build TwitterOAuth object with client credentials. */
$oauth = new TwitterOAuth(TWITTER_APP_ID,TWITTER_APP_SECRET);
 
/* Get temporary credentials. */
$request_token = $oauth->getRequestToken(TWITTER_OAUTH_CALLBACK);

/* Save temporary credentials to session cookies. */
$_SESSION['twt_oauth_token']=$token = $request_token['oauth_token'];
$_SESSION['twt_oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($oauth->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $oauth->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}

?>