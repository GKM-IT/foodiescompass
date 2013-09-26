<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
 
/* Redirect to page with the connect to Twitter option. */
header('Location: ../../index.php?success=0');
?>