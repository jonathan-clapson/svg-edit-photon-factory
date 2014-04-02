<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
	<div class="entry-container post-format">
    
        <header class="entry-header">
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'catcheverest' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
            <h2 class="entry-format"><a href="<?php echo get_post_format_link( 'Gallery' ); ?>" title="<?php _e( 'All Gallery Posts', 'catcheverest' ); ?>"><?php _e( 'Gallery', 'catcheverest' ); ?></a></h2>
        </header><!-- .entry-header -->  
    
    	<div class="entry-content">    
			<?php
            $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
            if ( $images ) :
                $total_images = count( $images );
                $image = array_shift( $images );
                $image_img_tag = wp_get_attachment_image( $image->ID, 'featured' );
            ?>
                <p><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'catcheverest' ), the_title_attribute( 'echo=0' ) ) ); ?>">	
                    <?php echo $image_img_tag; ?>
                </a></p>
                <p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'catcheverest' ),
                    'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'catcheverest' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
                    number_format_i18n( $total_images )
                ); ?></em></p> 
			<?php else :
            	the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'catcheverest' ) ); 
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'catcheverest' ), 'after' => '</div>' ) ); 
          	endif; ?> 
    
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
