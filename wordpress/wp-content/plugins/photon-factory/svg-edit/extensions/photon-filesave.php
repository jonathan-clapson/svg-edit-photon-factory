<?php
/*
 * filesave.php
 * To be used with ext-server_opensave.js for SVG-edit
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Alexis Deveria
 * Copyright(c) 2014 Photon Factory
 *
 */

ob_start();

$storage_basepath = $_SERVER['DOCUMENT_ROOT'];
$storage_left_trim = 'wordpress'; //cut of everything before first match of this when constructing file path

if (!isset($_POST['svgdata']) || !isset($_POST['svgtitle']) ) {
	die('post fail');
}

// pull in wordpress
define('WP_USE_THEMES', false);
require($_SERVER['DOCUMENT_ROOT']."wordpress/wp-blog-header.php");
header("HTTP/1.1 200 OK");

// check if current user is logged in
if ( get_current_user_id( ) == 0) {

	$host  = $_SERVER['HTTP_HOST'];
	$uri  = $_SERVER['DOCUMENT_ROOT'];
	$extra = 'wordpress';
	header("Location: http://$host/$extra");
	die();	
}

$title = $_POST['svgtitle'];
$data = $_POST['svgdata'];
$raw_data = urldecode($data);

// find wordpress post which references the title
$images = get_posts( array(
	'post_author' => get_current_user_id(),
	'post_type' => 'attachment',
	'numberposts' => -1,
	'post_status' => null,
	'post_parent' => null, // any parent
	'post_mime_type' => 'image/svg+xml',
	'name' => $title,
	)
);

// if we have a match
if (count($images)) {
	// remove ALL entries. There should only ever be one, we are about to overwrite it
	foreach ($images as $image) {
		wp_delete_post( $image->ID );
	}
}
// this is a new image
$filename = basename( $title );
$file_info = pathinfo($filename);
if (strcasecmp($file_info['extension'], "svg") != 0)
{
	$filename = $filename . ".svg";
}
$filetype = wp_check_filetype( $filename , null );
$wp_upload_dir = wp_upload_dir();
$file_path = $wp_upload_dir['path'] . '/' . $filename;
$file_url = $wp_upload_dir['url'] . '/' . $filename;

$attachment = array(
	'guid'			=> $file_url,
	'post_mime_type'	=> $filetype['type'],
	'post_title'		=> $filename,
	'post_content'		=> '',
	'post_status'		=> 'inherit'
);

$attach_id = wp_insert_attachment($attachment, $file_path);

// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Generate the metadata for the attachment, and update the database record.
$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
wp_update_attachment_metadata( $attach_id, $attach_data );
// create/overwrite and save file contents
$handle = fopen($file_path, "w");
if (!$handle) {
	echo "Server Permissions error: I can't save file";
	die();
}
fprintf($handle, "%s", $raw_data);
fclose($handle);

ob_end_flush();
?>
