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
	

// Store the messages in a variable to prevent interfering with headers manipulation.
$msg = '';

// This is the action requested by the user
$action = @$_POST['action'];

// This is the first uLogin-specific line in this file.
// We construct an instance and pass a function handle to our
// callback functions (we have just defined 'appLogin' and
// 'appLoginFail' a few lines above).
$ulogin = new uLogin('appLogin', 'appLoginFail');

// First we handle application logic. We make two cases,
// one for logged in users and one for anonymous users.
// We will handle presentation after our logic because what we present is
// also based on the logon state, but the application logic might change whether
// we are logged in or not.
if ( isset( $_POST['submit'] ) ) {
	$user = $_POST['user'];
	$pass = $_POST['pwd'];
	$email = $_POST['email'];
	
	if ($_POST['pwd'] != $_POST['cpwd']) {
		$msg = "Passwords do not match";
	} else {
	
		// Add new account to login manager
		if ( !$ulogin->CreateUser( $user,  $pass) ) {
			$msg = 'account creation failure';
		}
		else {
			$msg = 'account created';
			
			// Add new account to user details		
			$conn = new mysqli('localhost', 'user_manager', 'az3d4jka6', 'ulogin');
 
			// check connection
			if ($conn->connect_error) {
				trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
			}
			/* FIXME: get the correct one liner in here */
			/* INSERT INTO ul_userinfo (id, email) SELECT id, 'abc@xyz.com' FROM ul_logins WHERE ul_logins.username = ?; */			
			$stmt = $conn->prepare('INSERT INTO ul_userinfo (id, email) SELECT id, ? FROM ul_logins WHERE ul_logins.username = ?');
			$stmt->bind_param('ss',$email, $user);
			$stmt->execute();			
			$stmt->close();

			// Closing connection
			$conn->close();
		}
	}
}
header('Content-Type: text/html; charset=UTF-8');  
?>
<html></html>
<h3>Create a new account</h3>

<?php
	echo $msg;
?>

<form action="createaccount.php" method="POST">
<table>
	
Please fill out the following form to create a new user. All details are required.

<tr><td>Username:</td><td><input type="text" name="user"></td></tr>
<tr><td>Password:</td><td><input type="password" name="pwd"></td></tr>
<tr><td>Confirm Password:</td><td><input type="password" name="cpwd"></td></tr>
<tr><td>Email:</td><td><input type="text" name="email"></td></tr>

<tr><td>Nonce:</td><td><input type="hidden" id="nonce" name="nonce" value="<?php echo ulNonce::Create('login');?>"></td></tr>
<tr><td><input type="submit" name="submit"></td></tr>

</table>
</form>

