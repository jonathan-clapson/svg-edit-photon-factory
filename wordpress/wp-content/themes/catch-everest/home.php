<?php
/**
 * The Home template file.
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */

get_header(); 

	global $post, $wp_query, $catcheverest_options_settings;

	// Getting data from Theme Options
	$options = $catcheverest_options_settings;
	$enable_post = $options[ 'enable_posts_home' ];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');
	
	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();	

if ( !empty( $enable_post ) || ( !empty( $page_id ) && $page_id == $page_for_posts ) ) { 
?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
        
        <?php if ( have_posts() ) : ?>
        
            <?php catcheverest_content_nav( 'nav-above' ); ?>
        
            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
        
                <?php
                    /* Include the Post-Format-specific template for the content.
                     * If you want to overload this in a child theme then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part( 'content', get_post_format() );
                ?>
        
            <?php endwhile; ?>
        
            <?php catcheverest_content_nav( 'nav-below' ); ?>
        
        <?php else : ?>
        
            <?php get_template_part( 'no-results', 'index' ); ?>
        
        <?php endif; ?>
        
        </div><!-- #content .site-content -->
        
    </div><!-- #primary .content-area -->

	<?php
	get_sidebar();
}
get_footer(); ?>