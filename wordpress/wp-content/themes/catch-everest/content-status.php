<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-container post-format">
    
		<div class="entry-header">
            <header>
                <?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'catcheverest_status_avatar', '60' ) ); ?>
                <h1 class="entry-title"><?php the_author(); ?></h1>
            </header>
            <h2 class="entry-format"><a href="<?php echo get_post_format_link( 'status' ); ?>" title="<?php _e( 'All Status Posts', 'catcheverest' ); ?>"><?php _e( 'Status', 'catcheverest' ); ?></a></h2>
		</div><!-- .entry-header -->

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