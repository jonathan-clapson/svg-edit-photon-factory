<?php
/**
 * The template for displaying search forms in Catch Everest
 *
 * @package Catch Everest
 * @since Catch Everest 1.0
 */
 
// get the data value from theme options
global $catcheverest_options_settings;
$options = $catcheverest_options_settings;

$catcheverest_search_text = $options[ 'search_display_text' ]; 
?>
<div style="width:1000px">
<div style="float: left>
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	
		<label for="s" class="assistive-text"><?php _e( 'Search', 'catcheverest' ); ?></label>
		<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php printf( __( '%s', 'catcheverest' ) , $catcheverest_search_text ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'catcheverest' ); ?>" />
	</form>
</div>

<div style="float: right;">
<?php 
if ( is_user_logged_in() ) {
	$url = wp_logout_url( '/wordpress' );
	$linktext = "Logout";
} else {
	$url = wp_login_url( $redirect );
	$linktext = "Login";
	$url2 = wp_registration_url();	
	$linktext2 = "Register";
}

echo '<div style="float: left;">';
$button = '<a href="'.$url.'" class="photon-button">'.$linktext.'</a>';
echo $button;
echo '</div>';

echo '<div style="float: right;">';
if ( isset ($url2) ) {
	$button = '<a href="'.$url2.'" class="photon-button">'.$linktext2.'</a>';
	echo $button;
}
echo '</div>';
?>
</div>
</div>
