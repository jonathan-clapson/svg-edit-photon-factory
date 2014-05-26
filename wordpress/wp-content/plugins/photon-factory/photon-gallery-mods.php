<?php
/**
 * Plugin Name: Photon Gallery Mods
 * Plugin URI: 
 * Description: Modify links and display svg correctly
 * Version: 0.1
 * Author: Jonathan Clapson
 * Author URI: 
 * License: 
 */
 
/**
 * Generates SVG thumbnails in html format
 *
 * @param form_num the next form number to use for javascript submission
 * @param svg_title the title of the svg file
 * @param svg_path the path of the svg file on the server
 * @return returns an html string
 */
function generate_svg_thumbnail($form_num, $svg_title, $svg_path)
{
	$gallery_content_string = '
		<form target="_blank" name="svgedit%s" method="post" action="' . plugin_dir_url(__FILE__) .'svg-edit/svg-editor.php' . '">
		<input type="hidden" name="svg_path" value="%s" />
		<input type="hidden" name="action" value="load" />
		</form>
		<a href="#" onClick="document.svgedit%s.submit();">
		<img width="84%%" height="84%%" src="%s" class="attachment-thumbnail" alt="%s">
		</a>';
		
	return sprintf ($gallery_content_string, $form_num, $svg_path, $form_num, $svg_path, $svg_title);	
}
 
function my_gallery_shortcode( $output = '', $atts, $content = false, $tag = false ) {
	/* check this is for us. If its not return '' which allows normal handling to take place */
	if ($atts['ids'] != "SVGList")
		return '';
	
	/* strings to use for html output */
	$gallery_style = "
		<style type='text/css'>
			#gallery-1 {
				margin: auto;
			}
			#gallery-1 .gallery-item {
				float: left;
				margin-top: 10px;
				text-align: center;
				width: 33%;
			}
			#gallery-1 img {
				border: 2px solid #cfcfcf;
			}
			#gallery-1 .gallery-caption {
				margin-left: 0;
			}
		</style>";

	$gallery_start = '<div id="gallery-1" class="gallery galleryid-39 gallery-columns-3 gallery-size-thumbnail">';
	$gallery_end = '</div>';

	$gallery_item_start = '<dl class="gallery-item"><dt class="gallery-icon ">';
	$gallery_item_end = '</dt></dl>';

	$gallery_new_line = '<br style="clear: both">';
	
	/* get the list of images associated with this id */
	//XXX: Mimetype matching doesn't seem to work???
	$filter = array(
		'post_author' => get_current_user_id(),
		'post_type' => 'attachment',
		'numberposts' => -1,
//		'post_status' => null,
//		'post_parent' => null, // any parent
		'post-mime-type' => 'image/svg+xml',
		);

	$images = get_posts( $filter );
	
	// construct html
	$gallery_return = $gallery_style;
	$gallery_return .= $gallery_start;	

	$i = 0;
	//for each image the user has
	foreach($images as $image) {
		//for some reason user id is ignored in filter?!
		if ($image->post_author != get_current_user_id() ) continue;
		//for some reason mime type is also ignored...
		if ( strcasecmp($image->post_mime_type, "image/svg+xml") ) continue;
		$gallery_return .= $gallery_item_start;
		$gallery_return .= generate_svg_thumbnail($i, $image->post_title, $image->guid);
		$gallery_return .= $gallery_item_end;
		//the gallery start string defines 3 columns, so every 3rd picture should have a new line

		$i++;
		if (!($i%3)) {
			$gallery_return .= $gallery_new_line;
		}

	}
	$gallery_return .= $gallery_new_line;
	$gallery_return .= $gallery_end;
	
	return $gallery_return;
}

add_filter( 'post_gallery', 'my_gallery_shortcode', 10, 4 );
?>
