<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Catch Themes
 * @subpackage Catch Everest
 * @since Catch Everest 1.0
 */

/**
 * Enqueue scripts and styles
 */
function catcheverest_scripts() {
	
	//Getting Ready to load data from Theme Options Panel
	global $post, $wp_query, $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	
	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts'); 

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();	
	
	/**
	 * Loads up main stylesheet.
	 */
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	
	/**
	 * Loads up Responsive stylesheet and Menu JS
	 */
	$disable_responsive = $options[ 'disable_responsive' ];
	
	if ( $disable_responsive == "0" ) {	
		wp_enqueue_style( 'catcheverest-responsive', get_template_directory_uri() . '/css/responsive.css' );
		wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/catcheverest-menu.min.js', array( 'jquery' ), '20130224', true );
	}
	
	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	/**
	 * Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
	 */			
	wp_register_script( 'jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );
	
	/**
	 * Loads up catcheverest-slider and jquery-cycle set up as dependent on catcheverest-slider
	 */	
	$enableslider = $options[ 'enable_slider' ];
	if ( ( $enableslider == 'enable-slider-allpage' ) || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && $enableslider == 'enable-slider-homepage' ) ) {
		wp_enqueue_script( 'catcheverest-slider', get_template_directory_uri() . '/js/catcheverest-slider.js', array( 'jquery-cycle' ), '20130114', true );
	}
	
	/**
	 * Browser Specific Enqueue Script
	 */		
	$catcheverest_ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/(?i)msie [1-8]/',$catcheverest_ua)) {
	 	wp_enqueue_script( 'catcheverest-ieltc8', get_template_directory_uri() . '/js/catcheverest-ielte8.min.js', array( 'jquery' ), '20130114', false );	
		wp_enqueue_style( 'catcheverest-iecss', get_template_directory_uri() . '/css/ie.css' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'catcheverest_scripts' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_responsive() {
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	$disable_responsive = $options[ 'disable_responsive' ];
	
	if ( $disable_responsive == "0" ) {	
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
	}
}
add_action( 'wp_head', 'catcheverest_responsive', 5 );


/**
 * Hooks the Custom Inline CSS to head section
 *
 * @since Catch Everest 1.0
 */
function catcheverest_inline_css() {
	//delete_transient( 'catcheverest_inline_css' );	
	
	if ( ( !$catcheverest_inline_css = get_transient( 'catcheverest_inline_css' ) ) ) {
		// Getting data from Theme Options
		global $catcheverest_options_settings;
   		$options = $catcheverest_options_settings;

		echo '<!-- refreshing cache -->' . "\n";
		if( !empty( $options[ 'custom_css' ] ) ) {
			$catcheverest_inline_css	.= '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
	        $catcheverest_inline_css .= '<style type="text/css" media="screen">' . "\n";
			$catcheverest_inline_css .=  $options['custom_css'] . "\n";
			$catcheverest_inline_css .= '</style>' . "\n";
		}
			
	set_transient( 'catcheverest_inline_css', $catcheverest_inline_css, 86940 );
	}
	echo $catcheverest_inline_css;
}
add_action('wp_head', 'catcheverest_inline_css');


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'catcheverest_page_menu_args' );


/**
 * Removes div from wp_page_menu() and replace with ul.
 *
 * @since Catch Everest 1.0 
 */
function catcheverest_wp_page_menu ($page_markup) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup; }

add_filter('wp_page_menu', 'catcheverest_wp_page_menu');


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'responsive' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'catcheverest_wp_title', 10, 2 );


/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 * @uses filter excerpt_length
 */
function catcheverest_excerpt_length( $length ) {
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;

	return $options[ 'excerpt_length' ];
}
add_filter( 'excerpt_length', 'catcheverest_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
function catcheverest_continue_reading() {
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
    
	$more_tag_text = $options[ 'more_tag_text' ];
	
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' .  sprintf( __( '%s', 'catcheverest' ) , $more_tag_text ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with catcheverest_continue_reading().
 *
 */
function catcheverest_excerpt_more( $more ) {
	return catcheverest_continue_reading();
}
add_filter( 'excerpt_more', 'catcheverest_excerpt_more' );


/**
 * Adds Continue Reading link to post excerpts.
 *
 * function tied to the get_the_excerpt filter hook.
 */
function catcheverest_custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= catcheverest_continue_reading();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'catcheverest_custom_excerpt' );


/**
 * Replacing Continue Reading link to post content more.
 *
 * function tied to the the_content_more_link filter hook.
 */
function catcheverest_more_link( $more_link, $more_link_text ) {
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	
	$more_tag_text = $options[ 'more_tag_text' ];
	
	return str_replace( $more_link_text, $more_tag_text, $more_link );
}
add_filter( 'the_content_more_link', 'catcheverest_more_link', 10, 2 );


/**
 * Redirect WordPress Feeds To FeedBurner
 */
function catcheverest_rss_redirect() {	
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	
    if ($options['feed_url']) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT']))
		{
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}
add_action('template_redirect', 'catcheverest_rss_redirect');


/**
 * Adds custom classes to the array of body classes.
 *
 * @since Catch Everest 1.0
 */
function catcheverest_body_classes( $classes ) {
	global $post;
	
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	
	if( $post) {
 		if ( is_attachment() ) { 
			$parent = $post->post_parent;
			$layout = get_post_meta( $parent,'catcheverest-sidebarlayout', true );
		} else {
			$layout = get_post_meta( $post->ID,'catcheverest-sidebarlayout', true ); 
		}
	}

	if( empty( $layout ) || ( !is_page() && !is_single() ) ) {
		$layout='default';
	}
	
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
		
	$themeoption_layout = $options['sidebar_layout'];
	
	if( ( $layout == 'no-sidebar' || ( $layout=='default' && $themeoption_layout == 'no-sidebar') ) ) {
		$classes[] = 'no-sidebar';
	}
	elseif( ( $layout == 'no-sidebar-full-width' || ( $layout=='default' && $themeoption_layout == 'no-sidebar-full-width') ) ) {
		$classes[] = 'no-sidebar-full-width';
	}		
	elseif( ( $layout == 'left-sidebar' || ( $layout=='default' && $themeoption_layout == 'left-sidebar') ) ){
		$classes[] = 'left-sidebar';
	}
	elseif( ( $layout == 'right-sidebar' || ( $layout=='default' && $themeoption_layout == 'right-sidebar') ) ){
		$classes[] = 'right-sidebar';
	}	
	
	$current_content_layout = $options['content_layout'];
	if( $current_content_layout == 'full' ) {
		$classes[] = 'content-full';
	}
	elseif ( $current_content_layout == 'excerpt' ) {
		$classes[] = 'content-excerpt';
	}
	
	return $classes;
}
add_filter( 'body_class', 'catcheverest_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Catch Everest 1.0
 */
function catcheverest_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'catcheverest_enhanced_image_navigation', 10, 2 );


/**
 * Get the favicon Image from theme options
 *
 * @uses favicon 
 * @get the data value of image from theme options
 * @display favicon
 *
 * @uses default favicon if favicon field on theme options is empty
 *
 * @uses set_transient and delete_transient 
 */
function catcheverest_favicon() {
	//delete_transient( 'catcheverest_favicon' );	
	
	if( ( !$catcheverest_favicon = get_transient( 'catcheverest_favicon' ) ) ) {
		global $catcheverest_options_settings;
   		$options = $catcheverest_options_settings;
		
		echo '<!-- refreshing cache -->';
		if ( empty( $options[ 'remove_favicon' ] ) ) :
			// if not empty fav_icon on theme options
			if ( !empty( $options[ 'fav_icon' ] ) ) :
				$catcheverest_favicon = '<link rel="shortcut icon" href="'.esc_url( $options[ 'fav_icon' ] ).'" type="image/x-icon" />'; 	
			else:
				// if empty fav_icon on theme options, display default fav icon
				$catcheverest_favicon = '<link rel="shortcut icon" href="'. get_template_directory_uri() .'/images/favicon.ico" type="image/x-icon" />';
			endif;
		endif;
		
		set_transient( 'catcheverest_favicon', $catcheverest_favicon, 86940 );	
	}	
	echo $catcheverest_favicon ;	
} // catcheverest_favicon

//Load Favicon in Header Section
add_action('wp_head', 'catcheverest_favicon');

//Load Favicon in Admin Section
add_action( 'admin_head', 'catcheverest_favicon' );


if ( ! function_exists( 'catcheverest_header_left' ) ) :
/**
 * Shows Header Left content
 *
 * Shows the site logo, title and description
 * @uses catcheverest_header action to add it in the header
 */ 
function catcheverest_header_left() { ?>
    
        <div id="header-left">
            <?php
            // Check to see if the header image has been removed
			global $_wp_default_headers;
            $header_image = get_header_image();
            if ( ! empty( $header_image ) ) : ?>
                <h1 id="site-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                    <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
                </a></h1>
                <div id="hgroup" class="with-logo">
            <?php else :
                echo '<div id="hgroup">';   
            endif; // end check for removed header image ?>
                    
                <h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
            </div><!-- #hgroup -->
        </div><!-- #header-left -->

<?php 
}
endif; // catcheverest_header_left

add_action( 'catcheverest_hgroup_wrap', 'catcheverest_header_left', 10 );


/**
 * Shows Header Right Sidebar
 */
function catcheverest_header_right() { 

	/* A sidebar in the Header Right 
	*/
	get_sidebar( 'header-right' ); 

}
add_action( 'catcheverest_hgroup_wrap', 'catcheverest_header_right', 15 );


/**
 * Shows header right content
 *
 * Shows the Primary Menu
 * @uses catcheverest_header action to add it in the header
 */
function catcheverest_header_menu() { ?>
	<div id="header-menu">
        <nav id="access" role="navigation">
            <h2 class="assistive-text"><?php _e( 'Primary Menu', 'catcheverest' ); ?></h2>
            <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'catcheverest' ); ?>"><?php _e( 'Skip to content', 'catcheverest' ); ?></a></div>
            <?php
                if ( has_nav_menu( 'primary', 'catcheverest' ) ) { 
                    $args = array(
                        'theme_location'    => 'primary',
                        'container_class' 	=> 'menu-header-container', 
                        'items_wrap'        => '<ul class="menu">%3$s</ul>' 
                    );
                    wp_nav_menu( $args );
                }
                else {
                    echo '<div class="menu-header-container">';
                    wp_page_menu( array( 'menu_class'  => 'menu' ) );
                    echo '</div>';
                }
            ?> 	         
        </nav><!-- .site-navigation .main-navigation -->  
	</div>
<?php
}
add_action( 'catcheverest_after_hgroup_wrap', 'catcheverest_header_menu', 10 );


/**
 * Function to pass the slider effectr parameters from php file to js file.
 */
function catcheverest_pass_slider_value() {
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;

	$transition_effect = $options[ 'transition_effect' ];
	$transition_delay = $options[ 'transition_delay' ] * 1000;
	$transition_duration = $options[ 'transition_duration' ] * 1000;
	wp_localize_script( 
		'catcheverest-slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}// catcheverest_pass_slider_value


if ( ! function_exists( 'catcheverest_post_sliders' ) ) :
/**
 * Shows Featured Post Slider
 *
 * @uses catcheverest_header action to add it in the header
 */
function catcheverest_post_sliders() { 
	//delete_transient( 'catcheverest_post_sliders' );
	
	global $post, $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;

	
	if( ( !$catcheverest_post_sliders = get_transient( 'catcheverest_post_sliders' ) ) && !empty( $options[ 'featured_slider' ] ) ) {
		echo '<!-- refreshing cache -->';
		
		$catcheverest_post_sliders = '
		<div id="main-slider" class="container">
        	<section class="featured-slider">';
				$get_featured_posts = new WP_Query( array(
					'posts_per_page' => $options[ 'slider_qty' ],
					'post__in'		 => $options[ 'featured_slider' ],
					'orderby' 		 => 'post__in',
					'ignore_sticky_posts' => 1 // ignore sticky posts
				));
				$i=0; while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
					$title_attribute = apply_filters( 'the_title', get_the_title( $post->ID ) );
					$excerpt = get_the_excerpt();
					if ( $i == 1 ) { $classes = "post hentry slides displayblock"; } else { $classes = "post hentry slides displaynone"; }
					$catcheverest_post_sliders .= '
					<article class="'.$classes.'">
						<figure class="slider-image">
							<a title="Permalink to '.the_title('','',false).'" href="' . get_permalink() . '">
								'. get_the_post_thumbnail( $post->ID, 'slider', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class'	=> 'pngfix' ) ).'
							</a>	
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="Permalink to '.the_title('','',false).'" href="' . get_permalink() . '">'.the_title( '<span>','</span>', false ).'</a>
								</h1>
							</header>';
							if( $excerpt !='') {
								$catcheverest_post_sliders .= '<div class="entry-content">'. $excerpt.'</div>';
							}
							$catcheverest_post_sliders .= '
						</div>
					</article><!-- .slides -->';				
				endwhile; wp_reset_query();
				$catcheverest_post_sliders .= '
			</section>
        	<div id="slider-nav">
        		<a class="slide-previous">&lt;</a>
        		<a class="slide-next">&gt;</a>
        	</div>
        	<div id="controllers"></div>
  		</div><!-- #main-slider -->';
			
	set_transient( 'catcheverest_post_sliders', $catcheverest_post_sliders, 86940 );
	}
	echo $catcheverest_post_sliders;	
} 
endif; // catcheverest_post_sliders	


/**
 * Shows Default Slider Demo if there is not iteam in Featured Post Slider
 */
function catcheverest_default_sliders() { 
	//delete_transient( 'catcheverest_default_sliders' );
	
	if ( !$catcheverest_default_sliders = get_transient( 'catcheverest_default_sliders' ) ) {
		echo '<!-- refreshing cache -->';	
		$catcheverest_default_sliders = '
		<div id="main-slider" class="container">
			<section class="featured-slider">
				<article class="post hentry slides displayblock">
					<figure class="slider-image">
						<a title="Mount Everest" href="#">
							<img src="'. get_template_directory_uri() . '/images/slider1.jpg" class="wp-post-image" alt="Mount Everest" title="Mount Everest">
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Mount Everest" href="#"><span>Mount Everest</span></a>
							</h1>
						</header>
						<div class="entry-content">
							<p>Mount Everest is the Earth\'s highest mountain, with a peak at 8,848 metres above sea level and the 5th tallest mountain measured from the centre of the Earth. It is located in the Nepal.</p>
						</div>
					</div>
				</article><!-- .slides -->
				
				<article class="post hentry slides displaynone">
					<figure class="slider-image">
						<a title="Nepal Prayer Wheels" href="#">
							<img src="'. get_template_directory_uri() . '/images/slider2.jpg" class="wp-post-image" alt="Nepal Prayer Wheels" title="Nepal Prayer Wheels">
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Nepal Prayer Wheels" href="#"><span>Nepal Prayer Wheels</span></a>
							</h1>
						</header>
						<div class="entry-content">
							<p>A prayer wheel is a cylindrical wheel on a spindle made from metal, wood, stone, leather or coarse cotton. Traditionally, the mantra Om Mani Padme Hum is written in Sanskrit on the outside of the wheel.</p>
						</div>   
					</div>             
				</article><!-- .slides -->                   
			</section>
			<div id="slider-nav">
				<a class="slide-previous">&lt;</a>
				<a class="slide-next">&gt;</a>
			</div>
			<div id="controllers"></div>
		</div><!-- #main-slider -->';
			
	set_transient( 'catcheverest_default_sliders', $catcheverest_default_sliders, 86940 );
	}
	echo $catcheverest_default_sliders;	
} // catcheverest_default_sliders	


if ( ! function_exists( 'catcheverest_slider_display' ) ) :
/**
 * Shows Slider
 */
function catcheverest_slider_display() {
	global $post, $wp_query, $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;

	// get data value from theme options
	$enableslider = $options[ 'enable_slider' ];
	$featuredslider = $options[ 'featured_slider' ];
	
	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts'); 

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( ( $enableslider == 'enable-slider-allpage' ) || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && $enableslider == 'enable-slider-homepage' ) ) :
	 
	
		// This function passes the value of slider effect to js file 
		if ( function_exists( 'catcheverest_pass_slider_value' ) ) : catcheverest_pass_slider_value(); endif;
		
		// Select Slider
		if ( !empty( $featuredslider ) ) {
			catcheverest_post_sliders();
		}
		else {
			catcheverest_default_sliders();
		}

			
	endif;	
}
endif; // catcheverest_slider_display

add_action( 'catcheverest_before_main', 'catcheverest_slider_display', 10 );


if ( ! function_exists( 'catcheverest_homepage_headline' ) ) :
/**
 * Shows Homepage Headline Message
 *
 * @uses catcheverest_before_main action to add it in the header
 */
function catcheverest_homepage_headline() { 
	//delete_transient( 'catcheverest_homepage_headline' );
	
	global $post, $wp_query, $catcheverest_options_settings;;
   	$options = $catcheverest_options_settings;

	// Getting data from Theme Options
	$disable_headline = $options[ 'disable_homepage_headline' ];
	$disable_subheadline = $options[ 'disable_homepage_subheadline' ];
	$homepage_headline = $options[ 'homepage_headline' ];
	$homepage_subheadline = $options[ 'homepage_subheadline' ];
	
	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts'); 

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();
	

	if ( ( empty( $disable_headline ) || empty( $disable_subheadline ) ) && ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) ) {
		
		if ( !$catcheverest_homepage_headline = get_transient( 'catcheverest_homepage_headline' ) ) {
			
			echo '<!-- refreshing cache -->';	
			
			$catcheverest_homepage_headline = '<div id="homepage-message" class="container"><p>';
			
			if ( $disable_headline == "0" ) {
				$catcheverest_homepage_headline .= sprintf( __( '%s', 'catcheverest' ) , $homepage_headline );
			}
			if ( $disable_subheadline == "0" ) {
				$catcheverest_homepage_headline .= '<span>' . sprintf( __( '%s', 'catcheverest' ) , $homepage_subheadline ) . '</span>';
			}			
			
			$catcheverest_homepage_headline .= '</p></div>';  
			
			set_transient( 'catcheverest_homepage_headline', $catcheverest_homepage_headline, 86940 );
		}
		echo $catcheverest_homepage_headline;	
	 }
} 
endif; // catcheverest_homepage_headline	

add_action( 'catcheverest_before_main', 'catcheverest_homepage_headline', 10 );


/**
 * Shows Default Featued Content
 *
 * @uses catcheverest_before_main action to add it in the header
 */
function catcheverest_default_featured_content() { 
	//delete_transient( 'catcheverest_default_featured_content' );
	
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	$disable_homepage_featured = $options[ 'disable_homepage_featured' ];
	
	if ( $disable_homepage_featured == "0" ) { 
		if ( !$catcheverest_default_featured_content = get_transient( 'catcheverest_default_featured_content' ) ) {
			$catcheverest_default_featured_content = '
			<section id="featured-post">
				<article class="post hentry first">
					<figure class="featured-homepage-image">
						<a href="#" title="Nepal Prayer Wheels">
							<img title="Nepal Prayer Wheels" alt="Nepal Prayer Wheels" class="wp-post-image" src="'.get_template_directory_uri() . '/images/thumb-390-1.jpg" />
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Nepal Prayer Wheels" href="#">Nepal Prayer Wheels</a>
							</h1>
						</header>
						<div class="entry-content">
							A prayer wheel is a cylindrical wheel on a spindle made from metal, wood, stone, leather or coarse cotton. Traditionally, the mantra Om Mani Padme Hum is written in Sanskrit on the outside of the wheel.
						</div>
					</div><!-- .entry-container -->			
				</article>
				
				<article class="post hentry">
					<figure class="featured-homepage-image">
						<a href="#" title="Mount Everest">
							<img title="Mount Everest" alt="Mount Everest" class="wp-post-image" src="'.get_template_directory_uri() . '/images/thumb-390-2.jpg" />
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Mount Everest" href="#">Mount Everest</a>
							</h1>
						</header>
						<div class="entry-content">
							Mount Everest is the Earth\'s highest mountain, with a peak at 8,848 metres above sea level and the 5th tallest mountain measured from the centre of the Earth. It is located in the Nepal.
						</div>
					</div><!-- .entry-container -->			
				</article>

				<article class="post hentry">
					<figure class="featured-homepage-image">
						<a href="#" title="Mount Kanchengjunga">
							<img title="Mount Kanchengjunga" alt="Mount Kanchengjunga" class="wp-post-image" src="'.get_template_directory_uri() . '/images/thumb-390-3.jpg" />
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Mount Kanchengjunga" href="#">Mount Kanchengjunga</a>
							</h1>
						</header>
						<div class="entry-content">
							Kangchenjunga is the third highest mountain in the world, with apeat at 8,586 metres above sea level. It is located on the boundary between Nepal and the Indian state of Sikkim.
						</div>
					</div><!-- .entry-container -->			
				</article>
			</section><!-- #featured-post -->';
		}
		echo $catcheverest_default_featured_content;
	}
}


if ( ! function_exists( 'catcheverest_homepage_featured_content' ) ) :
/**
 * Homepage Featured Content
 *
 * @uses catcheverest_before_main action to add it in the header
 */
function catcheverest_homepage_featured_content() { 
	//delete_transient( 'catcheverest_homepage_featured_content' );
	
	// Getting data from Theme Options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
	$disable_homepage_featured = $options[ 'disable_homepage_featured' ];
	$quantity = $options [ 'homepage_featured_qty' ];
	$headline = $options [ 'homepage_featured_headline' ];
	
	if ( $disable_homepage_featured == "0" ) { 
		
		if ( !$catcheverest_homepage_featured_content = get_transient( 'catcheverest_homepage_featured_content' )  && ( !empty( $options[ 'homepage_featured_image' ] ) || !empty( $options[ 'homepage_featured_title' ] ) || !empty( $options[ 'homepage_featured_content' ] ) ) ) {
			
			echo '<!-- refreshing cache -->';	
			
			$catcheverest_homepage_featured_content = '<section id="featured-post">';
			
			if ( !empty( $headline ) ) {
				$catcheverest_homepage_featured_content .= '<h1 id="feature-heading" class="entry-title">' . sprintf( __( '%s', 'catcheverest' ) , $headline ) . '</h1>';
			}
			
			$catcheverest_homepage_featured_content .= '<div class="featued-content-wrap">';
			
				for ( $i = 1; $i <= $quantity; $i++ ) {
					
	
					if ( !empty ( $options[ 'homepage_featured_base' ][ $i ] ) ) {
						$target = '_blank';
					} else {
						$target = '_self';
					}
							
					//Adding in Classes for Display blok and none
					if ( $i % 3 == 1  || $i == 1 ) {
						$classes = "post hentry first"; 
					} 
					else { 
						$classes = "post hentry"; 
					}
							
					//Checking Link
					if ( !empty ( $options[ 'homepage_featured_url' ][ $i ] ) ) {
						$link = $options[ 'homepage_featured_url' ][ $i ];
					} else {
						$link = '#';
					}
					
					//Checking Title
					if ( !empty ( $options[ 'homepage_featured_title' ][ $i ] ) ) {
						$title = sprintf( __( '%s', 'catcheverest' ) , $options[ 'homepage_featured_title' ][ $i ] );
					} else {
						$title = '';
					}			
					
	
					if ( !empty ( $options[ 'homepage_featured_title' ][ $i ] ) || !empty ( $options[ 'homepage_featured_content' ][ $i ] ) || !empty ( $options[ 'homepage_featured_image' ][ $i ] ) ) {
						$catcheverest_homepage_featured_content .= '
						<article class="'.$classes.'">';
							if ( !empty ( $options[ 'homepage_featured_image' ][ $i ] ) ) {
								$catcheverest_homepage_featured_content .= '
								<figure class="featured-homepage-image">
									<a title="'.$title.'" href="'.$link.'" target="'.$target.'">
										<img src="'.$options[ 'homepage_featured_image' ][ $i ].'" class="wp-post-image" alt="'.$title.'" title="'.$title.'">
									</a>
								</figure>';  
							}
							if ( !empty ( $options[ 'homepage_featured_title' ][ $i ] ) || !empty ( $options[ 'homepage_featured_content' ][ $i ] ) ) {
								$catcheverest_homepage_featured_content .= '
								<div class="entry-container">';
								
									if ( !empty ( $options[ 'homepage_featured_title' ][ $i ] ) ) { 
										$catcheverest_homepage_featured_content .= '
										<header class="entry-header">
											<h1 class="entry-title">
												<a href="'.$link.'" title="'.$title.'" target="'.$target.'">'.$title.'</a>
											</h1>
										</header>';
									}
									if ( !empty ( $options[ 'homepage_featured_content' ][ $i ] ) ) { 
										$catcheverest_homepage_featured_content .= '
										<div class="entry-content">
											' . sprintf( __( '%s', 'catcheverest' ) , $options[ 'homepage_featured_content' ][ $i ] ) . '
										</div>';
									}
								$catcheverest_homepage_featured_content .= '
								</div><!-- .entry-container -->';	
							}
						$catcheverest_homepage_featured_content .= '			
						</article><!-- .slides -->';	
					}
			
				}
			
			$catcheverest_homepage_featured_content .= '</div><!-- .featued-content-wrap -->';
			
			$catcheverest_homepage_featured_content .= '</section><!-- #featured-post -->';	
			
		}
		
		echo $catcheverest_homepage_featured_content;
		
	}
 
} 
endif; // catcheverest_homepage_featured_content	


/**
 * Homepage Featured Content
 *
 * @uses catcheverest_before_main action to add it in the header
 */
function catcheverest_homepage_featured_display() { 	

	global $post, $wp_query, $catcheverest_options_settings;;
   	$options = $catcheverest_options_settings;

	// Getting data from Theme Options
	$options = $catcheverest_options_settings;
	$disable_homepage_featured = $options[ 'disable_homepage_featured' ];
	
	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts'); 

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();
	

	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		if  ( !empty( $options[ 'homepage_featured_image' ] ) || !empty( $options[ 'homepage_featured_title' ] ) || !empty( $options[ 'homepage_featured_content' ] ) ) {
			catcheverest_homepage_featured_content();
		} else {
			catcheverest_default_featured_content();
		}
	}
	
} // catcheverest_homepage_featured_content	

add_action( 'catcheverest_main', 'catcheverest_homepage_featured_display', 10 );


/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function catcheverest_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}


/**
 * shows footer content
 */
function catcheverest_footer_content() { 
	//delete_transient( 'catcheverest_footer_content' );	
	
	if ( ( !$catcheverest_footer_content = get_transient( 'catcheverest_footer_content' ) ) ) {
		echo '<!-- refreshing cache -->';
		
		// get the data value from theme options
		global $catcheverest_options_settings;
   	 	$options = $catcheverest_options_settings;
		
       // $catcheverest_footer_content = $options[ 'footer_code' ];
		$catcheverest_footer_content = '<div class="copyright">'. esc_attr__( 'Copyright', 'catcheverest' ) . ' &copy; [the-year] <span>[site-link]</span>. '. esc_attr__( 'All Rights Reserved', 'catcheverest' ) . '.</div><div class="powered">'. esc_attr__( 'Powered by', 'catcheverest' ) . ': [wp-link] | '. esc_attr__( 'Theme', 'catcheverest' ) . ': [theme-link]</div>';
		
    	set_transient( 'catcheverest_footer_content', $catcheverest_footer_content, 86940 );
    }
	echo do_shortcode( $catcheverest_footer_content );
}
add_action( 'catcheverest_site_generator', 'catcheverest_footer_content', 10 );


/**
 * Alter the query for the main loop in homepage
 * @uses pre_get_posts hook
 */
function catcheverest_alter_home( $query ){
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;
		
    $cats = $options[ 'front_page_category' ];

    if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options[ 'featured_slider' ] ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options[ 'featured_slider' ];
		}
	}
	if ( !in_array( '0', $cats ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['category__in'] = $options[ 'front_page_category' ];
		}
	}
}
add_action( 'pre_get_posts','catcheverest_alter_home' );


if ( ! function_exists( 'catcheverest_social_networks' ) ) :
/**
 * This function for Social Icons
 *
 * @fetch links through Theme Options
 * @use in widget
 * @social links, Facebook, Twitter and RSS
  */
function catcheverest_social_networks() {
	//delete_transient( 'catcheverest_social_networks' );
	
	// get the data value from theme options
	global $catcheverest_options_settings;
   	$options = $catcheverest_options_settings;

    $elements = array();

	$elements = array( 	$options[ 'social_facebook' ], 
						$options[ 'social_twitter' ],
						$options[ 'social_googleplus' ],
						$options[ 'social_linkedin' ],
						$options[ 'social_pinterest' ],
						$options[ 'social_youtube' ],
						$options[ 'social_vimeo' ],
						$options[ 'social_slideshare' ],
						$options[ 'social_foursquare' ],
						$options[ 'social_flickr' ],
						$options[ 'social_tumblr' ],
						$options[ 'social_deviantart' ],
						$options[ 'social_dribbble' ],
						$options[ 'social_myspace' ],
						$options[ 'social_wordpress' ],
						$options[ 'social_rss' ],
						$options[ 'social_delicious' ],
						$options[ 'social_lastfm' ],
						$options[ 'social_instagram' ],
						$options[ 'social_github' ],
						$options[ 'social_vkontakte' ],
						$options[ 'social_myworld' ],
						$options[ 'social_odnoklassniki' ],
						$options[ 'social_goodreads' ],
						$options[ 'social_skype' ],
						$options[ 'social_soundcloud' ]
					);
	$flag = 0;
	if( !empty( $elements ) ) {
		foreach( $elements as $option) {
			if( !empty( $option ) ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if( $flag == 1 ) {
				break;
			}
		}
	}	
	
	if ( ( !$catcheverest_social_networks = get_transient( 'catcheverest_social_networks' ) ) && ( $flag == 1 ) )  {
		echo '<!-- refreshing cache -->';
		
		$catcheverest_social_networks .='
		<ul class="social-profile">';
	
			//facebook
			if ( !empty( $options[ 'social_facebook' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="facebook"><a href="'.esc_url( $options[ 'social_facebook' ] ).'" title="'.sprintf( esc_attr__( '%s on Facebook', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Facebook </a></li>';
			}
			//Twitter
			if ( !empty( $options[ 'social_twitter' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="twitter"><a href="'.esc_url( $options[ 'social_twitter' ] ).'" title="'.sprintf( esc_attr__( '%s on Twitter', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
			}
			//Google+
			if ( !empty( $options[ 'social_googleplus' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="google-plus"><a href="'.esc_url( $options[ 'social_googleplus' ] ).'" title="'.sprintf( esc_attr__( '%s on Google+', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Google+ </a></li>';
			}
			//Linkedin
			if ( !empty( $options[ 'social_linkedin' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="linkedin"><a href="'.esc_url( $options[ 'social_linkedin' ] ).'" title="'.sprintf( esc_attr__( '%s on Linkedin', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Linkedin </a></li>';
			}
			//Pinterest
			if ( !empty( $options[ 'social_pinterest' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="pinterest"><a href="'.esc_url( $options[ 'social_pinterest' ] ).'" title="'.sprintf( esc_attr__( '%s on Pinterest', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
			}				
			//Youtube
			if ( !empty( $options[ 'social_youtube' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="you-tube"><a href="'.esc_url( $options[ 'social_youtube' ] ).'" title="'.sprintf( esc_attr__( '%s on YouTube', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' YouTube </a></li>';
			}
			//Vimeo
			if ( !empty( $options[ 'social_vimeo' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="viemo"><a href="'.esc_url( $options[ 'social_vimeo' ] ).'" title="'.sprintf( esc_attr__( '%s on Vimeo', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vimeo </a></li>';
			}				
			//Slideshare
			if ( !empty( $options[ 'social_slideshare' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="slideshare"><a href="'.esc_url( $options[ 'social_slideshare' ] ).'" title="'.sprintf( esc_attr__( '%s on Slideshare', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Slideshare </a></li>';
			}				
			//Foursquare
			if ( !empty( $options[ 'social_foursquare' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="foursquare"><a href="'.esc_url( $options[ 'social_foursquare' ] ).'" title="'.sprintf( esc_attr__( '%s on Foursquare', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' foursquare </a></li>';
			}
			//Flickr
			if ( !empty( $options[ 'social_flickr' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="flickr"><a href="'.esc_url( $options[ 'social_flickr' ] ).'" title="'.sprintf( esc_attr__( '%s on Flickr', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Flickr </a></li>';
			}
			//Tumblr
			if ( !empty( $options[ 'social_tumblr' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="tumblr"><a href="'.esc_url( $options[ 'social_tumblr' ] ).'" title="'.sprintf( esc_attr__( '%s on Tumblr', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Tumblr </a></li>';
			}
			//deviantART
			if ( !empty( $options[ 'social_deviantart' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="deviantart"><a href="'.esc_url( $options[ 'social_deviantart' ] ).'" title="'.sprintf( esc_attr__( '%s on deviantART', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' deviantART </a></li>';
			}
			//Dribbble
			if ( !empty( $options[ 'social_dribbble' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="dribbble"><a href="'.esc_url( $options[ 'social_dribbble' ] ).'" title="'.sprintf( esc_attr__( '%s on Dribbble', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Dribbble </a></li>';
			}
			//MySpace
			if ( !empty( $options[ 'social_myspace' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="myspace"><a href="'.esc_url( $options[ 'social_myspace' ] ).'" title="'.sprintf( esc_attr__( '%s on MySpace', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' MySpace </a></li>';
			}
			//WordPress
			if ( !empty( $options[ 'social_wordpress' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="wordpress"><a href="'.esc_url( $options[ 'social_wordpress' ] ).'" title="'.sprintf( esc_attr__( '%s on WordPress', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' WordPress </a></li>';
			}				
			//RSS
			if ( !empty( $options[ 'social_rss' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="rss"><a href="'.esc_url( $options[ 'social_rss' ] ).'" title="'.sprintf( esc_attr__( '%s on RSS', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' RSS </a></li>';
			}
			//Delicious
			if ( !empty( $options[ 'social_delicious' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="delicious"><a href="'.esc_url( $options[ 'social_delicious' ] ).'" title="'.sprintf( esc_attr__( '%s on Delicious', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Delicious </a></li>';
			}				
			//Last.fm
			if ( !empty( $options[ 'social_lastfm' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="lastfm"><a href="'.esc_url( $options[ 'social_lastfm' ] ).'" title="'.sprintf( esc_attr__( '%s on Last.fm', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Last.fm </a></li>';
			}	
			//Instagram
			if ( !empty( $options[ 'social_instagram' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="instagram"><a href="'.esc_url( $options[ 'social_instagram' ] ).'" title="'.sprintf( esc_attr__( '%s on Instagram', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Instagram </a></li>';
			}	
			//GitHub
			if ( !empty( $options[ 'social_github' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="github"><a href="'.esc_url( $options[ 'social_github' ] ).'" title="'.sprintf( esc_attr__( '%s on GitHub', 'catcheverest' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' GitHub </a></li>';
			}				
			//Vkontakte
			if ( !empty( $options[ 'social_vkontakte' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="vkontakte"><a href="'.esc_url( $options[ 'social_vkontakte' ] ).'" title="'.sprintf( esc_attr__( '%s on Vkontakte', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vkontakte </a></li>';
			}				
			//My World
			if ( !empty( $options[ 'social_myworld' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="myworld"><a href="'.esc_url( $options[ 'social_myworld' ] ).'" title="'.sprintf( esc_attr__( '%s on My World', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' My World </a></li>';
			}				
			//Odnoklassniki
			if ( !empty( $options[ 'social_odnoklassniki' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="odnoklassniki"><a href="'.esc_url( $options[ 'social_odnoklassniki' ] ).'" title="'.sprintf( esc_attr__( '%s on Odnoklassniki', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Odnoklassniki </a></li>';
			}
			//Goodreads
			if ( !empty( $options[ 'social_goodreads' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="goodreads"><a href="'.esc_url( $options[ 'social_goodreads' ] ).'" title="'.sprintf( esc_attr__( '%s on Goodreads', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Goodreads </a></li>';
			}
			//Skype
			if ( !empty( $options[ 'social_skype' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="skype"><a href="'.esc_attr( $options[ 'social_skype' ] ).'" title="'.sprintf( esc_attr__( '%s on Skype', 'catcheverest' ),get_bloginfo( 'name' ) ).'">'.get_bloginfo( 'name' ).' Skype </a></li>';
			}
			//Soundcloud
			if ( !empty( $options[ 'social_soundcloud' ] ) ) {
				$catcheverest_social_networks .=
					'<li class="soundcloud"><a href="'.esc_url( $options[ 'social_soundcloud' ] ).'" title="'.sprintf( esc_attr__( '%s on Soundcloud', 'catcheverest' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Soundcloud </a></li>';
			}			
			
			$catcheverest_social_networks .='
		</ul>';
		
		set_transient( 'catcheverest_social_networks', $catcheverest_social_networks, 86940 );	 
	}
	echo $catcheverest_social_networks;
}
endif; // catcheverest_social_networks


/**
 * Site Verification and Header Code from the Theme Option
 *
 * If user sets the code we're going to display meta verification
 * @get the data value from theme options
 * @uses wp_head action to add the code in the header
 * @uses set_transient and delete_transient API for cache
 */
function catcheverest_webmaster() {
	//delete_transient( 'catcheverest_webmaster' );	
	
	if ( ( !$catcheverest_webmaster = get_transient( 'catcheverest_webmaster' ) ) ) {

		// get the data value from theme options
		global $catcheverest_options_settings;
   		$options = $catcheverest_options_settings;
		echo '<!-- refreshing cache -->';	
		
		$catcheverest_webmaster = '';
		//google
		if ( !empty( $options['google_verification'] ) ) {
			$catcheverest_webmaster .= '<meta name="google-site-verification" content="' .  $options['google_verification'] . '" />' . "\n";
		}
		//bing
		if ( !empty( $options['bing_verification'] ) ) {
			$catcheverest_webmaster .= '<meta name="msvalidate.01" content="' .  $options['bing_verification']  . '" />' . "\n";
		}
		//yahoo
		 if ( !empty( $options['yahoo_verification'] ) ) {
			$catcheverest_webmaster .= '<meta name="y_key" content="' .  $options['yahoo_verification']  . '" />' . "\n";
		}
		//site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
			$catcheverest_webmaster =  $options[ 'analytic_header' ] ;
		}
			
		set_transient( 'catcheverest_webmaster', $catcheverest_webmaster, 86940 );
	}
	echo $catcheverest_webmaster;
}
add_action('wp_head', 'catcheverest_webmaster');


/**
 * This function loads the Footer Code such as Add this code from the Theme Option
 *
 * @get the data value from theme options
 * @load on the footer ONLY
 * @uses wp_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function catcheverest_footercode() {
	//delete_transient( 'catcheverest_footercode' );	
	
	if ( ( !$catcheverest_footercode = get_transient( 'catcheverest_footercode' ) ) ) {

		// get the data value from theme options
		global $catcheverest_options_settings;
   		$options = $catcheverest_options_settings;
		echo '<!-- refreshing cache -->';	
		
		//site stats, analytics header code
		if ( !empty( $options['analytic_footer'] ) ) {
			$catcheverest_footercode =  $options[ 'analytic_footer' ] ;
		}
			
		set_transient( 'catcheverest_footercode', $catcheverest_footercode, 86940 );
	}
	echo $catcheverest_footercode;
}
add_action('wp_footer', 'catcheverest_footercode');


/**
 * Adds in post ID when viewing lists of posts 
 * This will help the admin to add the post ID in featured slider
 * 
 * @param mixed $post_columns
 * @return post columns
 */
function catcheverest_post_id_column( $post_columns ) {
	$beginning = array_slice( $post_columns, 0 ,1 );
	$beginning[ 'postid' ] = __( 'ID', 'catcheverest'  );
	$ending = array_slice( $post_columns, 1 );
	$post_columns = array_merge( $beginning, $ending );
	return $post_columns;
}

add_filter( 'manage_posts_columns', 'catcheverest_post_id_column' );

function catcheverest_posts_id_column( $col, $val ) {
	if( $col == 'postid' ) echo $val;
}

add_action( 'manage_posts_custom_column', 'catcheverest_posts_id_column', 10, 2 );

function catcheverest_posts_id_column_css() {
	echo '<style type="text/css">#postid { width: 40px; }</style>';
}

add_action( 'admin_head-edit.php', 'catcheverest_posts_id_column_css' );


if ( ! function_exists( 'catcheverest_menu_alter' ) ) :
/**
* Add default navigation menu to nav menu
* Used while viewing on smaller screen
*/
function catcheverest_menu_alter( $items, $args ) {
	$items .= '<li class="default-menu"><a href="' . esc_url( home_url( '/' ) ) . '" title="Menu">'.__( 'Menu', 'catcheverest' ).'</a></li>';
	return $items;
}
endif; // catcheverest_menu_alter

add_filter( 'wp_nav_menu_items', 'catcheverest_menu_alter', 10, 2 );


if ( ! function_exists( 'catcheverest_pagemenu_alter' ) ) :
/**
 * Add default navigation menu to page menu
 * Used while viewing on smaller screen
 */
function catcheverest_pagemenu_alter( $output ) {
	$output .= '<li class="default-menu"><a href="' . esc_url( home_url( '/' ) ) . '" title="Menu">'.__( 'Menu', 'catcheverest' ).'</a></li>';
	return $output;
}
endif; // catcheverest_pagemenu_alter

add_filter( 'wp_list_pages', 'catcheverest_pagemenu_alter' );


if ( ! function_exists( 'catcheverest_pagemenu_filter' ) ) :
/**
 * @uses wp_page_menu filter hook
 */
function catcheverest_pagemenu_filter( $text ) {
	$replace = array(
		'current_page_item'     => 'current-menu-item'
	);

	$text = str_replace(array_keys($replace), $replace, $text);
  	return $text;
	
}
endif; // catcheverest_pagemenu_filter

add_filter('wp_page_menu', 'catcheverest_pagemenu_filter');


/**
 * Get the Web Clip Icon Image from theme options
 *
 * @uses web_clip and remove_web_clip 
 * @get the data value of image from theme options
 * @display webclip icon
 *
 * @uses default Web Click Icon if web_clip field on theme options is empty
 *
 * @uses set_transient and delete_transient 
 */
function catcheverest_web_clip() {
	//delete_transient( 'catcheverest_web_clip' );	
	
	if( ( !$catcheverest_web_clip = get_transient( 'catcheverest_web_clip' ) ) ) {
		
		// get the data value from theme options
		global $catcheverest_options_settings;
   		$options = $catcheverest_options_settings;
		
		echo '<!-- refreshing cache -->';
		if ( empty( $options[ 'remove_web_clip' ] ) ) :
			// if not empty web_clip on theme options
			if ( !empty( $options[ 'web_clip' ] ) ) :
				$catcheverest_web_clip = '<link rel="apple-touch-icon-precomposed" href="'.esc_url( $options[ 'web_clip' ] ).'" />'; 	
			else:
				// if empty web_clip on theme options, display default webclip icon
				$catcheverest_web_clip = '<link rel="apple-touch-icon-precomposed" href="'. get_template_directory_uri() .'/images/apple-touch-icon.png" />';
			endif;
		endif;
		
		set_transient( 'catcheverest_web_clip', $catcheverest_web_clip, 86940 );	
	}	
	echo $catcheverest_web_clip ;	
} // catcheverest_web_clip

//Load webclip icon in Header Section
add_action( 'wp_head', 'catcheverest_web_clip' );