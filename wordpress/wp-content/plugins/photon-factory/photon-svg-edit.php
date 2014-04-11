<?php
/**
 * Plugin Name: Photon SVG-edit
 * Plugin URI: 
 * Description: This plugin integrates svg-edit with wordpress
 * Version: 0.1
 * Author: Jonathan Clapson
 * Author URI: 
 * License: 
 */

register_activation_hook(__FILE__, 'photon_svg_edit_activate');
register_deactivation_hook(__FILE__, 'photon_svg_edit_deactivate');

function photon_svg_edit_activate() {
	global $wpdb;

	// if the title is set to a html link, the page menu item becomes a link to the page
	$page_name = 'SVG Editor'; //the url
	//$page_title = '<a href="/svg-edit/svg-editor.php" target="_blank">SVG Editor</a>'; //the title
	$page_title = 'SVG Editor';
	$svgeditlink = plugins_url( 'svg-edit/svg-editor.php', __FILE__ );	
	
	// add the menu entry
	delete_option('svg_edit_page_title');
	add_option('svg_edit_page_title', $page_title, '', 'yes');
	
	// set the url
	delete_option('svg_edit_page_name');
	add_option('svg_edit_page_name', $page_name, '', 'yes');
	
	delete_option('svg_edit_page_id');
	add_option('svg_edit_page_id', '0', '', 'yes');
	
	$page = get_page_by_title($page_title);
	
	if (! $page ) {
		// create post object
		$_p = array();
		$_p['post_title'] = $page_title;
		$_p['post_content'] = '<a href="' . $svgeditlink . '" target="_blank">Create New File</a>[gallery ids="SVGList"]';
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1); //default is uncategorised
		
		$page_id = wp_insert_post( $_p );
	} else {
		// plugin has already been installed. 
		$page_id = $page->ID;
		
		$page->post_status = 'publish';
		$page_id = wp_update_post( $page );
		
	}
	
	delete_option('svg_edit_page_id');
	add_option('svg_edit_page_id', $page_id);		
}

function photon_svg_edit_deactivate() {
	global $wpdb;
	
	$page_title = get_option('svg_edit_page_title');
	$page_name = get_option('svg_edit_page_name');
	
	$page_id = get_option('svg_edit_page_id');
	if ( $page_id ) {
		wp_delete_post($page_id);
	}
	delete_option('svg_edit_page_title');
	delete_option('svg_edit_page_name');
	delete_option('svg_edit_page_id');	
}
?>
