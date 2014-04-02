<?php
/**
 * Catch Everest functions and definitions
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */

if ( ! function_exists( 'catcheverest_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_setup() {
	
	global $content_width;
	/**
	 * Global content width.
	 */
	 if (!isset($content_width))
     	$content_width = 690;
				
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Catch Everest, use a find and replace
	 * to change 'catcheverest' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catcheverest', get_template_directory() . '/languages' );	
	
	/**
	 * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
	 * @see http://codex.wordpress.org/Function_Reference/add_editor_style
	 */
	add_editor_style();	
	
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );	
	
	/**
	 * Theme Options Defaults
	 */	
	require( get_template_directory() . '/inc/catcheverest-theme-options-defaults.php' );	

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/panel/theme-options.php' );	
	
	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/catcheverest-functions.php' );	
	
	/**
	 * Metabox
	 */
	require( get_template_directory() . '/inc/catcheverest-metabox.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Register Sidebar and Widget.
	 */
	require( get_template_directory() . '/inc/widgets.php' );
	
	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-image'	=> get_template_directory_uri() . '/images/noise.png',
		
	) );	

	/**
     * This feature enables custom-menus support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */		
	register_nav_menu( 'primary', __( 'Primary Menu', 'catcheverest' ) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );
	
	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */		
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',										
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page'
    ) );
		
	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'slider', 1140, 450, true); //Featured Post Slider Image
	add_image_size( 'featured', 690, 462, true); //Featured Image
	add_image_size( 'small-featured', 390, 261, true); //Small Featured Image

}
endif; // catcheverest_setup
add_action( 'after_setup_theme', 'catcheverest_setup' );


/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );