<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */
?>

	</div><!-- #main .site-main -->
    
	<?php 
    /** 
     * catcheverest_after_main hook
     */
    do_action( 'catcheverest_after_main' ); 
    ?> 
    
	<footer id="colophon" role="contentinfo">
		<?php
        /** 
         * catcheverest_before_footer_sidebar hook
         */
        do_action( 'catcheverest_before_footer_sidebar' );    

		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		get_sidebar( 'footer' ); 

		/** 
		 * catcheverest_after_footer_sidebar hook
		 */
		do_action( 'catcheverest_after_footer_sidebar' ); ?>   
           
        <div id="site-generator" class="container">
			<?php 
            /** 
             * catcheverest_before_site_info hook
             */
            do_action( 'catcheverest_before_site_info' ); ?>  
                    
        	<div class="site-info">
            	<?php 
				/** 
				 * catcheverest_site_info hook
				 *
				 * @hooked catcheverest_footer_content - 10
				 */
				do_action( 'catcheverest_site_generator' ); ?> 
          	</div><!-- .site-info -->
            
			<?php 
            /** 
             * catcheverest_after_site_info hook
             */
            do_action( 'catcheverest_after_site_info' ); ?>              
       	</div><!-- #site-generator --> 
        
        <?php
        /** 
		 * catcheverest_after_site_generator hook
		 */
		do_action( 'catcheverest_after_site_generator' ); ?>  
               
	</footer><!-- #colophon .site-footer -->
    
    <?php 
    /** 
     * catcheverest_after_footer hook
     */
    do_action( 'catcheverest_after_footer' ); 
    ?> 
    
</div><!-- #page .hfeed .site -->

<?php 
/** 
 * catcheverest_after hook
 */
do_action( 'catcheverest_after' );

wp_footer(); ?>

</body>
</html>