<?php
/**
 * Plugin Name: Photon Custom Register
 * Plugin URI: 
 * Description: This plugin modifies wordpress's registration procedures to add additional data
 * Version: 0.1
 * Author: Jonathan Clapson
 * Author URI: 
 * License: 
 */

add_action('register_form','photon_register_form');
function photon_register_form() {
	$first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name'] : '';
?>
<p>
	<label for="first_name"><?php _e('First Name','localhost') ?><br />
	<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" /></label>
</p>
<p>
	<label for="last_name"><?php _e('Last Name','localhost') ?><br />
	<input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" /></label>
</p>
<p>
	<label for="mail_address"><?php _e('Mail Address','localhost') ?><br />
	<input type="text" name="mail_address" id="mail_address" class="input" value="<?php echo esc_attr(stripslashes($mail_address)); ?>" size="25" /></label>
</p>
<?php
}

add_filter('registration_errors', 'photon_registration_errors', 10, 3);
function photon_registration_errors ($errors, $sanitized_user_login, $user_email) {
	if ( empty ($_POST['first_name'] ))
		$errors->add( 'first_name_error', __('<strong>ERROR</strong>: You must include a first name.','localhost') );
		
	if ( empty ($_POST['last_name'] ))
		$errors->add( 'last_name_error', __('<strong>ERROR</strong>: You must include a last name.','localhost') );
		
	if ( empty ($_POST['mail_address'] ))
		$errors->add( 'mail_address_error', __('<strong>ERROR</strong>: You must include a mail address.','localhost') );

	return $errors;
}

add_action('user_register', 'photon_user_register');
function photon_user_register ($user_id) {
	if ( isset( $_POST['first_name'] ) )
		update_user_meta($user_id, 'first_name', $_POST['first_name']);
	if ( isset( $_POST['last_name'] ) )
		update_user_meta($user_id, 'last_name', $_POST['last_name']);
	if ( isset( $_POST['mail_address'] ) )
		update_user_meta($user_id, 'mail_address', $_POST['mail_address']);
}
?>
