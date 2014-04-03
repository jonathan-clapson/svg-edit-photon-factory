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
if (!isset($_POST['svgdata']) || !isset($_POST['svgname']) ) {
	die('post fail');
}

$file = $_POST['svgname'];

$data = $_POST['svgdata'];

echo "filename: " . $file;
echo "data: " . $data;
$raw_data = urldecode($data);
echo "raw_data: ";
echo $raw_data;
$handle = fopen($file, "w");
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
?>
