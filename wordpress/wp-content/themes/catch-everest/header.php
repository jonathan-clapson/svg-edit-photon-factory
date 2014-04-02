<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
if ( defined( 'WPSEO_VERSION' ) ) {
    // WordPress SEO is activated
        wp_title();

} else { 
	
    // WordPress SEO is not activated
	wp_title( '&#124;', true, 'right' );
}
?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<?php 
/** 
 * catcheverest_before hook
 */
do_action( 'catcheverest_before' ); ?>

<div id="page" class="hfeed site">

	<?php 
    /** 
     * catcheverest_before_header hook
     */
    do_action( 'catcheverest_before_header' ); ?>
    
	<header id="masthead" role="banner">
    
    	<?php 
		/** 
		 * catcheverest_before_hgroup_wrap hook
		 */
		do_action( 'catcheverest_before_hgroup_wrap' ); ?>
        
    	<div id="hgroup-wrap" class="container">
        
       		<?php 
			/** 
			 * catcheverest_hgroup_wrap hook
			 *
			 * HOOKED_FUNCTION_NAME PRIORITY
			 *
			 * catcheverest_header_left 10
			 * catcheverest_header_right 15
			 */
			do_action( 'catcheverest_hgroup_wrap' ); ?>
            
        </div><!-- #hgroup-wrap -->
        
        <?php 
		/** 
		 * catcheverest_after_hgroup_wrap hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * catcheverest_header_menu 10
		 */
		do_action( 'catcheverest_after_hgroup_wrap' ); ?>
        
	</header><!-- #masthead .site-header -->
    
	<?php 
    /** 
     * catcheverest_after_header hook
     */
    do_action( 'catcheverest_after_header' ); ?> 
        
	<?php 
    /** 
     * catcheverest_before_main hook
	 *
	 * HOOKED_FUNCTION_NAME PRIORITY
	 *
	 * catcheverest_slider_display 10
	 * catcheverest_homepage_headline 15
     */
    do_action( 'catcheverest_before_main' ); ?>
    
    
    <div id="main" class="container">
    
		<?php 
        /** 
         * catcheverest_main hook
         *
         * HOOKED_FUNCTION_NAME PRIORITY
         *
	 	 * catcheverest_homepage_featured_display 10
         */
        do_action( 'catcheverest_main' ); ?>
