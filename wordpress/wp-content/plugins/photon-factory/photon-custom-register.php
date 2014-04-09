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

function get_wp_installation()
{
	$path = plugin_dir_path(__FILE__);
	$tmp = explode("wp-", $path);
	return $tmp[0];
}

include_once(get_wp_installation().'/wp-includes/class-phpmailer.php' );

add_action('register_form','photon_register_form');
function photon_register_form() {
	$first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name'] : '';
	$last_name = ( isset( $_POST['last_name'] ) ) ? $_POST['last_name'] : '';
	$mail_address = ( isset( $_POST['mail_address'] ) ) ? $_POST['mail_address'] : '';
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
	<label for="po_box"><?php _e('PO Box','localhost') ?><br />
	<input type="text" name="po_box" id="po_box" class="input" value="<?php echo esc_attr(stripslashes($po_box)); ?>" size="25" /></label>
</p>

<p>
	<label for="extended_address"><?php _e('Extended address','localhost') ?><br />
	<input type="text" name="extended_address" id="extended_address" class="input" value="<?php echo esc_attr(stripslashes($extended_address)); ?>" size="25" /></label>
</p>

<p>
	<label for="street_address"><?php _e('*Street address','localhost') ?><br />
	<input type="text" name="street_address" id="street_address" class="input" value="<?php echo esc_attr(stripslashes($street_address)); ?>" size="25" /></label>
</p>

<p>
	<label for="city"><?php _e('*City','localhost') ?><br />
	<input type="text" name="city" id="city" class="input" value="<?php echo esc_attr(stripslashes($city)); ?>" size="25" /></label>
</p>

<p>
	<label for="state"><?php _e('State','localhost') ?><br />
	<input type="text" name="state" id="state" class="input" value="<?php echo esc_attr(stripslashes($state)); ?>" size="25" /></label>
</p>

<p>
	<label for="post_code"><?php _e('Post code','localhost') ?><br />
	<input type="text" name="post_code" id="post_code" class="input" value="<?php echo esc_attr(stripslashes($post_code)); ?>" size="25" /></label>
</p>

<p>
	<label for="country"><?php _e('*Country','localhost') ?><br />
	<input type="text" name="country" id="country" class="input" value="<?php echo esc_attr(stripslashes($country)); ?>" size="25" /></label>
</p>
<?php
}

add_filter('registration_errors', 'photon_registration_errors', 10, 3);
function photon_registration_errors ($errors, $sanitized_user_login, $user_email) {
	if ( empty ($_POST['first_name'] ))
		$errors->add( 'first_name_error', __('<strong>ERROR</strong>: You must include a first name.','localhost') );
		
	if ( empty ($_POST['last_name'] ))
		$errors->add( 'last_name_error', __('<strong>ERROR</strong>: You must include a last name.','localhost') );
	
	/* po box not required 
	if ( empty ($_POST['po_box'] ))
		$errors->add( 'number_error', __('<strong>ERROR</strong>: You must include a street number or PO box','localhost') );*/
	/* extended address not required 
	if ( empty ($_POST['extended_address']))*/

	if ( empty ($_POST['street_address']))
		$errors->add( 'street_address_error', __('<strong>ERROR</strong>: You must include a street address','localhost') );
	
	if ( empty ($_POST['city']))
		$errors->add( 'city_error', __('<strong>ERROR</strong>: You must include a city','localhost') );
	/* state not required 
	if ( empty ($_POST['state'])) */	
	/*post code not required
	if ( empty ($_POST['post_code']))*/
	
	if ( empty ($_POST['country']))
		$errors->add( 'country_error', __('<strong>ERROR</strong>: You must include a country','localhost') );

	return $errors;
}

add_action('user_register', 'photon_user_register');
function photon_user_register ($user_id) {	
	// get the data needed to form email
	$user = get_userdata( $user_id );
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	
	$po_box = $_POST['po_box'];
	$extended_address = $_POST['extended_address'];
	$street_address = $_POST['street_address'];
	$locality = $_POST['city'];
	$region = $_POST['state'];
	$post_code = $_POST['post_code'];
	$country = $_POST['country'];
	
	$user_login = $user->user_login;
	$user_email = $user->user_email;

	// make email
	$mail = new PHPMailer();

	$mail->From = "lasermaze@photonfactory.org.nz";
	$mail->FromName = "lasermaze";
	$mail->Subject = "User Registration: $user_login";	
	$mail->Body = "$first_name $last_name has registered with the username $user->user_login. To add their contact details please open the attached file.";
	$mail->AddAddress("lasermaze@photonfactory.org.nz", "lasermaze");
	$mail->AddAddress("jonathan.clapson@gmail.com", "lasermaze");
	
	$vcardString = "BEGIN:VCARD\r\nVERSION:4.0\r\nN:$last_name;$first_name;;;\r\nFN:$user_login\r\nNICKNAME:$user_login\r\nADR:$po_box;$extended_address;$street_address;$locality;$region;$post_code;$country\r\nEMAIL:$user_email\r\nEND:VCARD\r\n";
	
	/* using encoding or correct content type causes phpmailer to break the email... */
	//$attachment = chunk_split(base64_encode($vcardString));	
	
	$mail->AddStringAttachment($vcardString, $user_login . ".vcf");
	
	$mail->Send();
}
?>
