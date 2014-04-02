<?php
/**
 * @package Catch Themes
 * @subpackage Catch_Everest
 * @since Catch Everest 1.0
 */

/**
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 */
global $catcheverest_options_defaults;
$catcheverest_options_defaults = array(
	'disable_responsive'					=> '0',
 	'fav_icon'								=> get_template_directory_uri().'/images/favicon.ico',
 	'remove_favicon'						=> '1',
	'web_clip'								=> get_template_directory_uri().'/images/apple-touch-icon.png',
 	'remove_web_clip'						=> '1',	
	'disable_header_right_sidebar'			=> '0',
 	'custom_css'							=> '',	
	'sidebar_layout'						=> 'right-sidebar',
	'content_layout'						=> 'full',
	'reset_layout'							=> '2',
	'more_tag_text'							=> 'Continue Reading &rarr;',
	'excerpt_length'						=> 30,
 	'search_display_text'					=> 'Search &hellip;',
	'feed_url'								=> '',
	'homepage_headline'						=> 'Homepage Headline. ',
	'homepage_subheadline'					=> 'You can edit or disable it through Theme Options.',
	'disable_homepage_headline'				=> '0',
	'disable_homepage_subheadline'			=> '0',
	'disable_homepage_featured'				=> '0',
	'homepage_featured_headline'			=> '',
	'homepage_featured_qty'					=> 3,
	'homepage_featured_image'				=> array(),
	'homepage_featured_url'					=> array(),
	'homepage_featured_base'				=> array(),
	'homepage_featured_title'				=> array(),
	'homepage_featured_content'				=> array(),
	'enable_posts_home'						=> '0',
 	'front_page_category'					=> array(),
	'enable_slider'							=> 'enable-slider-homepage',
	'slider_qty'							=> 4,
 	'transition_effect'						=> 'fade',
 	'transition_delay'						=> 4,
 	'transition_duration'					=> 1,	
	'exclude_slider_post'					=> 0,
	'featured_slider'						=> array(),
 	'social_facebook'						=> '',
 	'social_twitter'						=> '',
 	'social_googleplus'						=> '',
 	'social_pinterest'						=> '',
 	'social_youtube'						=> '',
 	'social_vimeo'							=> '',
 	'social_linkedin'						=> '',
 	'social_slideshare'						=> '',
 	'social_foursquare'						=> '',
 	'social_flickr'							=> '',
 	'social_tumblr'							=> '',
 	'social_deviantart'						=> '',
 	'social_dribbble'						=> '',
 	'social_myspace'						=> '',
 	'social_wordpress'						=> '',
 	'social_rss'							=> '',
 	'social_delicious'						=> '',
 	'social_lastfm'							=> '',
	'social_instagram'						=> '',
	'social_github'							=> '',
	'social_vkontakte'						=> '',
	'social_myworld'						=> '',
	'social_odnoklassniki'					=> '',
	'social_goodreads'						=> '',
	'social_skype'							=> '',
	'social_soundcloud'						=> '',
 	'google_verification'					=> '',
 	'yahoo_verification'					=> '',
 	'bing_verification'						=> '',
 	'analytic_header'						=> '',
 	'analytic_footer'						=> ''
);
global $catcheverest_options_settings;
$catcheverest_options_settings = catcheverest_options_set_defaults( $catcheverest_options_defaults );

function catcheverest_options_set_defaults( $catcheverest_options_defaults ) {
	$catcheverest_options_settings = array_merge( $catcheverest_options_defaults, (array) get_option( 'catcheverest_options', array() ) );
	return $catcheverest_options_settings;
}

?>