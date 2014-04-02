<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>

<?php 
/** 
 * catcheverest_above_secondary hook
 */
do_action( 'catcheverest_before_secondary' ); 

	//Getting Ready to load data from Theme Options Panel
	global $post, $catcheverest_options_settings;
	$options = $catcheverest_options_settings;
	$themeoption_layout = $options['sidebar_layout'];

	if( $post) {
 		if ( is_attachment() ) { 
			$parent = $post->post_parent;
			$layout = get_post_meta( $parent,'catcheverest-sidebarlayout', true );
		} else {
			$layout = get_post_meta( $post->ID,'catcheverest-sidebarlayout', true ); 
		}
	}	
		
	if( empty( $layout ) || ( !is_page() && !is_single() ) )
		$layout='default';
		
	
	if ( ( $layout == 'left-sidebar' || $layout == 'right-sidebar' || ( $layout=='default' && $themeoption_layout == 'left-sidebar') || ( $layout=='default' && $themeoption_layout == 'right-sidebar') ) ) { ?>

		<div id="secondary" class="widget-area" role="complementary">
			<?php 
			/** 
			 * catcheverest_before_widget_start hook
			 */
			do_action( 'catcheverest_before_widget_start' );
			
			if ( is_active_sidebar( 'sidebar-1' ) ) {
				dynamic_sidebar( 'sidebar-1' ); 
			}
			else { ?>
				<aside id="search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>
		
				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'catcheverest' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>
			
			<?php 
			} // end sidebar widget area ?>
			
			<?php 
			/** 
			 * catcheverest_after_widget_ends hook
			 */
			do_action( 'catcheverest_after_widget_ends' ); ?>    
		</div><!-- #secondary .widget-area -->
        
		<?php
	} 
	
/** 
 * catcheverest_after_secondary hook
 */
do_action( 'catcheverest_after_secondary' );