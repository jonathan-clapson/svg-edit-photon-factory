<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Blog Template
 *
   Template Name: Blog
 *
 * The template for Blog
 *
 * @package Catch Everest
 * @since Catch Everest 0.2
 */
 
get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
            
            	<?php 
				global $more, $wp_query, $post, $paged;
				$more = 0;
				
				if ( get_query_var( 'paged' ) ) {
					
					$paged = get_query_var( 'paged' );
					
				}
				elseif ( get_query_var( 'page' ) ) {
					
					$paged = get_query_var( 'page' );
					
				}
				else {
					
					$paged = 1;
					
				}
				
				$blog_query = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged ) );
				$temp_query = $wp_query;
				$wp_query = null;
				$wp_query = $blog_query;

				if ( $blog_query->have_posts() ) : ?>
                
                	<header class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
                  	</header><!-- .page-header -->
                    
                    <?php catcheverest_content_nav( 'nav-above' ); ?>
                    
					<?php /* Start the Loop */ ?>
					<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                    
						<?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to overload this in a child theme then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'content', get_post_format() );
                        ?>

					<?php endwhile; ?>
                        
                    <?php catcheverest_content_query_nav( 'nav-below' ); ?>	

				<?php else : ?>   

					<?php get_template_part( 'no-results', 'archive' ); ?>
					
				<?php endif; 
				$wp_query = $temp_query;
				wp_reset_postdata();
				?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>