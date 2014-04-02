<?php
/**
 * The Footer widget areas.
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>
<?php 
/** 
 * catcheverest_before_header_right hook
 */
do_action( 'catcheverest_before_header_right' ); ?> 
<?php 
global $catcheverest_options_settings;
$options = $catcheverest_options_settings;

if ( $options[ 'disable_header_right_sidebar' ] == "0" ) {	?>
    <div id="header-right" class="widget-area">
    	<?php if ( function_exists( 'catcheverest_social_networks' ) ) { ?>
			<aside class="widget widget_catcheverest_social_widget">
           		<?php catcheverest_social_networks(); ?>
           	</aside>
		<?php
        } ?>
        <aside class="widget widget_search" id="search-5">	
        	<?php echo get_search_form(); ?>
		</aside>
    </div><!-- #header-right .widget-area -->
<?php 
}
/** 
 * catcheverest_after_header_right hook hook
 */
do_action( 'catcheverest_after_header_right' ); ?> 