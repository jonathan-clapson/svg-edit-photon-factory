<?php

// This is an example that shows how to incorporate uLogin into a webpage.
// It showcases nonces, login authentication, account creation, deletion and
// remember-me functionality, all at the same time in a single page.
// Because of the number of functions shown and all the comments,
// it seems a little bit longish, but fear not.

// This is the one and only public include file for uLogin.
// Include it once on every authentication and for every protected page.
require_once('ulogin/config/all.inc.php');
require_once('ulogin/main.inc.php');

// Start a secure session if none is running
if (!sses_running())
	sses_start();
	
function appLogout(){
  // When a user explicitly logs out you'll definetely want to disable
  // autologin for the same user. For demonstration purposes,
  // we don't do that here so that the autologin function remains
  // easy to test.
  //$ulogin->SetAutologin($_SESSION['username'], false);

	unset($_SESSION['uid']);
	unset($_SESSION['username']);
	unset($_SESSION['loggedIn']);
	
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';
	header("Location: http://$host$uri/$extra");
	die();
}

appLogout();
