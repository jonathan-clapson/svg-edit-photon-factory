<?php
/**
 * The template for displaying posts in the Link post format
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-container post-format">
    
        <header class="entry-format">
        	<a href="<?php echo get_post_format_link( 'link' ); ?>" title="<?php _e( 'All Link Posts', 'catcheverest' ); ?>"><?php _e( 'Link', 'catcheverest' ); ?></a>
        </header>
        
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'catcheverest' ) ); ?>
        </div><!-- .entry-content -->
    
        <footer class="entry-meta">
            <?php catcheverest_post_format_meta(); ?>   
            <?php if ( comments_open() ) : ?>
            	<span class="sep"> | </span>
            	<span class="comments-link"><?php comments_popup_link(__('Leave a reply', 'catcheverest'), __('1 Reply', 'catcheverest'), __('% Replies;', 'catcheverest')); ?></span>
            <?php endif; ?>
            <?php edit_post_link( __( 'Edit', 'catcheverest' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
        </footer><!-- .entry-meta -->
         
  	</div><!-- .entry-container -->
    
</article><!-- #post-<?php the_ID(); ?> -->
