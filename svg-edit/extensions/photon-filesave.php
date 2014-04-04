<?php
/*
 * filesave.php
 * To be used with ext-server_opensave.js for SVG-edit
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Alexis Deveria
 *
 */

ob_start();

$storage_basepath = $_SERVER['DOCUMENT_ROOT']."svg-files/";

if (!isset($_POST['svgdata']) || !isset($_POST['svgname']) ) {
	die('post fail');
}

/* pull in wordpress */
define('WP_USE_THEMES', false);
require($_SERVER['DOCUMENT_ROOT']."wordpress/wp-blog-header.php");
header("HTTP/1.1 200 OK");

/* check if current user is logged in */
if ( get_current_user_id( ) == 0) {

	$host  = $_SERVER['HTTP_HOST'];
	$uri  = $_SERVER['DOCUMENT_ROOT'];
	$extra = 'wordpress';
	header("Location: http://$host/$extra");
	die();	
}

echo "cur user id: " . get_current_user_id();

$file = $_POST['svgname'];
$data = $_POST['svgdata'];
$raw_data = urldecode($data);
echo "b";

/* set up paths */
$username = wp_get_current_user()->user_login;
echo "c" . $username;
$dir_path = $storage_basepath . $username;
$file_path = $dir_path . "/" . $file;

/* create directory if doesn't exist */
if (!is_dir($dir_path)) {
	mkdir($dir_path, 0730, true);
}

/* create and save file */
echo "file: " . $file_path;
$handle = fopen($file_path, "w");
if (!$handle) {
	echo "Server Permissions error: I can't save file";
}
fprintf($handle, "%s", $raw_data);
fclose($handle);



 /*header("Cache-Control: public");
 header("Content-Description: File Transfer");
 header("Content-Disposition: attachment; filename=" . $file);
 header("Content-Type: " .  $mime);
 header("Content-Transfer-Encoding: binary");
 
 echo $contents;*/
echo "done!";

ob_end_flush();
?>
