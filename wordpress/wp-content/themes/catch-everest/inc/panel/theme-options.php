<?php
/**
 * Catch Everest Theme Options
 *
 * @package Catch Themes
 * @subpackage Catch_Everest
 * @since Catch Everest 1.0
 */
add_action( 'admin_init', 'catcheverest_register_settings' );
add_action( 'admin_menu', 'catcheverest_options_menu' );


/**
 * Enqueue admin script and styles
 *
 * @uses wp_register_script, wp_enqueue_script and wp_enqueue_style
 * @Calling jquery, jquery-ui-tabs,jquery-cookie, jquery-ui-sortable, jquery-ui-draggable, media-upload, thickbox, farbtastic, colorpicker
 */
function catcheverest_admin_scripts() {
	//jQuery Cookie
	wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/inc/panel/js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );
	
	wp_enqueue_script( 'catcheverest_admin', get_template_directory_uri().'/inc/panel/js/admin.min.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-cookie', 'jquery-ui-sortable', 'jquery-ui-draggable' ) );
	wp_enqueue_script( 'catcheverest_upload', get_template_directory_uri().'/inc/panel/js/add_image_scripts.js', array( 'jquery','media-upload','thickbox' ) );
	
	wp_enqueue_style( 'catcheverest_admin_style',get_template_directory_uri().'/inc/panel/admin.css', array( 'thickbox'), '1.0', 'screen' );
}
add_action('admin_print_styles-appearance_page_theme_options', 'catcheverest_admin_scripts');


/*
 * Create a function for Theme Options Page
 *
 * @uses add_menu_page
 * @add action admin_menu 
 */
function catcheverest_options_menu() {

	add_theme_page( 
        __( 'Theme Options', 'catcheverest' ),           // Name of page
        __( 'Theme Options', 'catcheverest' ),           // Label in menu
        'edit_theme_options',                           // Capability required
        'theme_options',                                // Menu slug, used to uniquely identify the page
        'catcheverest_theme_options_do_page'             // Function that renders the options page
    );	

}


/* 
 * Admin Social Links
 * use facebook and twitter scripts
 */
function catcheverest_admin_social() { ?>
<!-- Start Social scripts -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=276203972392824";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<!-- End Social scripts -->
<?php
}
add_action('admin_print_styles-appearance_page_theme_options','catcheverest_admin_social');


/*
 * Register options and validation callbacks
 *
 * @uses register_setting
 * @action admin_init
 */
function catcheverest_register_settings(){
	register_setting( 'catcheverest_options', 'catcheverest_options', 'catcheverest_theme_options_validate' );
}


/*
 * Render Catch Everest Theme Options page
 *
 * @uses settings_fields, get_option, bloginfo
 * @Settings Updated
 */
function catcheverest_theme_options_do_page() {
	if (!isset($_REQUEST['settings-updated']))
		$_REQUEST['settings-updated'] = false;
	?>
    
	<div id="catchthemes" class="wrap">
    	
    	<form method="post" action="options.php">
			<?php
                settings_fields( 'catcheverest_options' );
                global $catcheverest_options_settings;
                $options = $catcheverest_options_settings;				
            ?>   
            <?php if (false !== $_REQUEST['settings-updated']) : ?>
            	<div class="updated fade"><p><strong><?php _e('Options Saved', 'catcheverest'); ?></strong></p></div>
            <?php endif; ?>            
            
			<div id="theme-option-header">
            	<div id="theme-social">
                	<ul>
            			<li class="widget-fb">
                            <div data-show-faces="false" data-width="80" data-layout="button_count" data-send="false" data-href="<?php echo esc_url(__('http://facebook.com/catchthemes','catcheverest')); ?>" class="fb-like"></div></li>
                     	<li class="widget-tw">
                            <a data-dnt="true" data-show-screen-name="true" data-show-count="true" class="twitter-follow-button" href="<?php echo esc_url(__('https://twitter.com/catchthemes','catcheverest')); ?>">Follow @catchthemes</a>
            			</li>
                   	</ul>
               	</div><!-- #theme-social -->
            
                <div id="theme-option-title">
                    <h2 class="title"><?php _e( 'Theme Options By', 'catcheverest' ); ?></h2>
                    <h2 class="logo">
                        <a href="<?php echo esc_url( __( 'http://catchthemes.com/', 'catcheverest' ) ); ?>" title="<?php esc_attr_e( 'Catch Themes', 'catcheverest' ); ?>" target="_blank">
                            <img src="<?php echo get_template_directory_uri().'/inc/panel/images/catch-themes.png'; ?>" alt="<?php _e( 'Catch Themes', 'catcheverest' ); ?>" />
                        </a>
                    </h2>
                </div><!-- #theme-option-title -->
                
                <div id="upgradepro">
                	<a class="button" href="<?php echo esc_url(__('http://catchthemes.com/themes/catch-everest-pro/','catcheverest')); ?>" title="<?php esc_attr_e('Upgrade to Catch Everest Pro', 'catcheverest'); ?>" target="_blank"><?php printf(__('Upgrade to Catch Everest Pro','catcheverest')); ?></a>
               	</div><!-- #upgradepro -->
            
                <div id="theme-support">
                    <ul>
                    	<li><a class="button donate" href="<?php echo esc_url(__('http://catchthemes.com/donate/','catcheverest')); ?>" title="<?php esc_attr_e('Donate to Catch Everest', 'catcheverest'); ?>" target="_blank"><?php printf(__('Donate Now','catcheverest')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('http://catchthemes.com/support/','catcheverest')); ?>" title="<?php esc_attr_e('Support', 'catcheverest'); ?>" target="_blank"><?php printf(__('Support','catcheverest')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('http://catchthemes.com/theme-instructions/catch-everest/','catcheverest')); ?>" title="<?php esc_attr_e('Theme Instruction', 'catcheverest'); ?>" target="_blank"><?php printf(__('Theme Instruction','catcheverest')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://www.facebook.com/catchthemes/','catcheverest')); ?>" title="<?php esc_attr_e('Like Catch Themes on Facebook', 'catcheverest'); ?>" target="_blank"><?php printf(__('Facebook','catcheverest')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('https://twitter.com/catchthemes/','catcheverest')); ?>" title="<?php esc_attr_e('Follow Catch Themes on Twitter', 'catcheverest'); ?>" target="_blank"><?php printf(__('Twitter','catcheverest')); ?></a></li>
                        <li><a class="button" href="<?php echo esc_url(__('http://wordpress.org/support/view/theme-reviews/catch-everest','catcheverest')); ?>" title="<?php esc_attr_e('Rate us 5 Star on WordPress', 'catcheverest'); ?>" target="_blank"><?php printf(__('5 Star Rating','catcheverest')); ?></a></li>
                   	</ul>
                </div><!-- #theme-support --> 
                 
          	</div><!-- #theme-option-header -->              
 
            
            <div id="catcheverest_ad_tabs">
                <ul class="tabNavigation" id="mainNav">
                    <li><a href="#themeoptions"><?php _e( 'Theme Options', 'catcheverest' );?></a></li>
                    <li><a href="#homepagesettings"><?php _e( 'Homepage Settings', 'catcheverest' );?></a></li>
                    <li><a href="#slidersettings"><?php _e( 'Featured Post Slider', 'catcheverest' );?></a></li>
                    <li><a href="#sociallinks"><?php _e( 'Social Links', 'catcheverest' );?></a></li>
                    <li><a href="#webmaster"><?php _e( 'Webmaster Tools', 'catcheverest' );?></a></li>
                </ul><!-- .tabsNavigation #mainNav -->
                   
                   
                <!-- Option for Design Settings -->
                <div id="themeoptions">     
                
                	<div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Responsive Design', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                        	<table class="form-table">
                                <tbody>
                                    <tr>                            
                                        <th scope="row"><?php _e( 'Disable Responsive Design?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[disable_responsive]'>
                                        <td><input type="checkbox" id="headerlogo" name="catcheverest_options[disable_responsive]" value="1" <?php checked( '1', $options['disable_responsive'] ); ?> /> <?php _e('Check to disable', 'catcheverest'); ?></td>
                                    </tr>
                               	</tbody>
                          	</table>
                      		<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content --> 
                    </div><!-- .option-container --> 
                                        
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Upload Logo', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                        	<p><?php printf(__('Custom Header. Need to Add or Remove Logo?','catcheverest')); ?> <?php printf(__('<a class="button" href="%s">Click here</a>', 'catcheverest'), admin_url('themes.php?page=custom-header')); ?></p>	
                    	</div><!-- .option-content -->
                 	</div><!-- .option-container --> 

                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Favicon', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php _e( 'Disable Favicon?', 'catcheverest' ); ?></th>
                                         <input type='hidden' value='0' name='catcheverest_options[remove_favicon]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[remove_favicon]" value="1" <?php checked( '1', $options['remove_favicon'] ); ?> /> <?php _e('Check to disable', 'catcheverest'); ?></td>
                                    </tr>
                                    <tr>                            
                                        <th scope="row"><?php _e( 'Fav Icon URL:', 'catcheverest' ); ?></th>
                                        <td><?php if ( !empty ( $options[ 'fav_icon' ] ) ) { ?>
                                                <input class="upload-url" size="65" type="text" name="catcheverest_options[fav_icon]" value="<?php echo esc_url( $options [ 'fav_icon' ] ); ?>" />
                                            <?php } else { ?>
                                                <input class="upload-url" size="65" type="text" name="catcheverest_options[fav_icon]" value="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" alt="fav" />
                                            <?php }  ?> 
                                            <input id="st_upload_button" class="st_upload_button button" name="wsl-image-add" type="button" value="<?php esc_attr_e( 'Change Fav Icon','catcheverest' );?>" />
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th scope="row"><?php _e( 'Preview: ', 'catcheverest' ); ?></th>
                                        <td> 
                                            <?php 
                                                if ( !empty( $options[ 'fav_icon' ] ) ) { 
                                                    echo '<img src="'.esc_url( $options[ 'fav_icon' ] ).'" alt="fav" />';
                                                } else {
                                                    echo '<img src="'. get_template_directory_uri().'/images/favicon.ico" alt="fav" />';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Web Clip Icon Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php _e( 'Disable Web Clip Icon?', 'catcheverest' ); ?></th>
                                         <input type='hidden' value='0' name='catcheverest_options[remove_web_clip]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[remove_web_clip]" value="1" <?php checked( '1', $options['remove_web_clip'] ); ?> /> <?php _e('Check to disable', 'catcheverest'); ?></td>
                                    </tr>
                                    <tr>                            
                                        <th scope="row"><?php _e( 'Web Clip Icon URL:', 'catcheverest' ); ?></th>
                                        <td><?php if ( !empty ( $options[ 'web_clip' ] ) ) { ?>
                                                <input class="upload-url" size="65" type="text" name="catcheverest_options[web_clip]" value="<?php echo esc_url( $options [ 'web_clip' ] ); ?>" class="upload" />
                                            <?php } else { ?>
                                                <input size="65" type="text" name="catcheverest_options[web_clip]" value="<?php echo get_template_directory_uri(); ?>/images/apple-touch-icon.png" alt="fav" />
                                            <?php }  ?> 
                                            <input id="st_upload_button" class="st_upload_button button" name="wsl-image-add" type="button" value="<?php esc_attr_e( 'Change Web Clip Icon','catcheverest' );?>" />
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th scope="row"><?php _e( 'Preview: ', 'catcheverest' ); ?></th>
                                        <td> 
                                            <?php 
                                                if ( !empty( $options[ 'web_clip' ] ) ) { 
                                                    echo '<img src="'.esc_url( $options[ 'web_clip' ] ).'" alt="fav" />';
                                                } else {
                                                    echo '<img src="'. get_template_directory_uri().'/images/favicon.ico" alt="fav" />';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p><?php esc_attr_e( 'Note: Web Clip Icon for Apple devices. Recommended Size - Width 144px and Height 144px height, which will support High Resolution Devices like iPad Retina.', 'catcheverest' ); ?></p>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->                    
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Header Right Section', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php _e( 'Disable Header Right Section?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[disable_header_right_sidebar]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[disable_header_right_sidebar]" value="1" <?php checked( '1', $options['disable_header_right_sidebar'] ); ?> /> <?php _e( 'Check to disable', 'catcheverest'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->                        
                                        
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Background', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                        	<p><?php printf(__('Custom Background. Need to replace or remove background?','catcheverest')); ?> <?php printf(__('<a class="button" href="%s">Click here</a>', 'catcheverest'), admin_url('themes.php?page=custom-background')); ?></p>	                                 
                    	</div><!-- .option-content -->
                 	</div><!-- .option-container -->                     
 
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Search Text Settings', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">  
                                <tbody>
                                    <tr> 
                                        <th scope="row"><label><?php _e( 'Default Display Text in Search', 'catcheverest' ); ?></label></th>
                                        <td><input type="text" size="45" name="catcheverest_options[search_display_text]" value="<?php echo esc_attr( $options[ 'search_display_text'] ); ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container --> 
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Excerpt / More Tag Settings', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">  
                                <tbody>
									<tr>
                                        <th scope="row"><label><?php _e( 'More Tag Text', 'catcheverest' ); ?></label></th>
                                        <td><input type="text" size="45" name="catcheverest_options[more_tag_text]" value="<?php echo esc_attr( $options[ 'more_tag_text' ] ); ?>" />
                                        </td>
                                    </tr>  
                                     <tr>
                                        <th scope="row"><?php _e( 'Excerpt length(words)', 'catcheverest' ); ?></th>
                                        <td><input type="text" size="3" name="catcheverest_options[excerpt_length]" value="<?php echo intval( $options[ 'excerpt_length' ] ); ?>" /></td>
                                    </tr>  
                              	</tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container --> 
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Feed Redirect', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">  
                                <tbody>
									<tr>
                                        <th scope="row"><label><?php _e( 'Feed Redirect URL', 'catcheverest' ); ?></label></th>
                                        <td><input type="text" size="70" name="catcheverest_options[feed_url]" value="<?php echo esc_attr( $options[ 'feed_url' ] ); ?>" /> <?php _e( 'Add in the Feedburner URL', 'catcheverest' ); ?>
                                        </td>
                                    </tr>  
                               	</tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->    
                    
					<div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Layout Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table content-layout">  
                                <tbody>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Sidebar Layout Options', 'catcheverest' ); ?></label></th>
                                        <td>
                                        	<label title="right-sidebar" class="box"><img src="<?php echo get_template_directory_uri(); ?>/inc/panel/images/right-sidebar.png" alt="Content-Sidebar" /><br />
                                            <input type="radio" name="catcheverest_options[sidebar_layout]" id="right-sidebar" <?php checked($options['sidebar_layout'], 'right-sidebar') ?> value="right-sidebar"  />
                                            <?php _e( 'Right Sidebar', 'catcheverest' ); ?>
                                            </label>  
                                            
                                            <label title="left-Sidebar" class="box"><img src="<?php echo get_template_directory_uri(); ?>/inc/panel/images/left-sidebar.png" alt="Content-Sidebar" /><br />
                                            <input type="radio" name="catcheverest_options[sidebar_layout]" id="left-sidebar" <?php checked($options['sidebar_layout'], 'left-sidebar') ?> value="left-sidebar"  />
                                            <?php _e( 'Left Sidebar', 'catcheverest' ); ?>
                                            </label>   
                                            
                                            <label title="no-sidebar" class="box"><img src="<?php echo get_template_directory_uri(); ?>/inc/panel/images/no-sidebar.png" alt="Content-Sidebar" /><br />
                                            <input type="radio" name="catcheverest_options[sidebar_layout]" id="no-sidebar" <?php checked($options['sidebar_layout'], 'no-sidebar') ?> value="no-sidebar"  />
                                            <?php _e( 'No Sidebar', 'catcheverest' ); ?>
                                            </label>
                                                      
                                            <label title="no-sidebar-full-width" class="box"><img src="<?php echo get_template_directory_uri(); ?>/inc/panel/images/no-sidebar-fullwidth.png" alt="No Sidebar, Full Width" /><br />
                                            <input type="radio" name="catcheverest_options[sidebar_layout]" id="no-sidebar-full-width" <?php checked($options['sidebar_layout'], 'no-sidebar-full-width'); ?> value="no-sidebar-full-width"  />
                                            <?php _e( 'No Sidebar, Full Width', 'catcheverest' ); ?>
                                            </label>                                                                                  
                                        </td>
                                    </tr>  
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Content Layout', 'catcheverest' ); ?></label></th>
                                        <td>
                                        	<label title="content-full" class="box"><input type="radio" name="catcheverest_options[content_layout]" id="content-full" <?php checked($options['content_layout'], 'full') ?> value="full"  />
                                            <?php _e( 'Full Content Display', 'catcheverest' ); ?>
                                            </label>   
                                            
                                        	<label title="content-excerpt" class="box"><input type="radio" name="catcheverest_options[content_layout]" id="content-excerpt" <?php checked($options['content_layout'], 'excerpt') ?> value="excerpt"  />
                                            <?php _e( 'Excerpt/Blog Display', 'catcheverest' ); ?>
                                            </label>                                    
                                        </td>
                                    </tr>                                      
                                    <?php if( $options[ 'reset_layout' ] == "1" ) { $options[ 'reset_layout' ] = "0"; } ?>
                                    <tr>                            
                                        <th scope="row"><?php _e( 'Reset Layout?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[reset_layout]'>
                                        <td>
                                            <input type="checkbox" id="headerlogo" name="catcheverest_options[reset_layout]" value="1" <?php checked( '1', $options['reset_layout'] ); ?> /> <?php _e('Check to reset', 'catcheverest'); ?>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Custom CSS', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside"> 
                            <table class="form-table">  
                                <tbody>       
                                    <tr>
                                        <th scope="row"><?php _e( 'Enter your custom CSS styles.', 'catcheverest' ); ?></th>
                                        <td>
                                            <textarea name="catcheverest_options[custom_css]" id="custom-css" cols="90" rows="12"><?php echo esc_attr( $options[ 'custom_css' ] ); ?></textarea>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <th scope="row"><?php _e( 'CSS Tutorial from W3Schools.', 'catcheverest' ); ?></th>
                                        <td>
                                            <a class="button" href="<?php echo esc_url( __( 'http://www.w3schools.com/css/default.asp','catcheverest' ) ); ?>" title="<?php esc_attr_e( 'CSS Tutorial', 'catcheverest' ); ?>" target="_blank"><?php _e( 'Click Here to Read', 'catcheverest' );?></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container --> 
                                       
                </div><!-- #themeoptions -->  

				<!-- Options for Homepage Settings -->
                <div id="homepagesettings">                    
                
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Homepage Headline Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php _e( 'Homepage Headline', 'catcheverest' ); ?>
                                            <p><small><?php _e( 'The appropriate length for Headine is around 10 words.', 'catcheverest' ); ?></small></p>
                                        </th>
                                        <td>
	                                        <textarea class="textarea input-bg" name="catcheverest_options[homepage_headline]" cols="65" rows="3"><?php echo esc_textarea( $options[ 'homepage_headline' ] ); ?></textarea>
	                                    </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Homepage Subheadline Headline', 'catcheverest' ); ?>
                                            <p><small><?php _e( 'The appropriate length for Headine is around 10 words.', 'catcheverest' ); ?></small></p>
                                        </th>
                                        <td>
	                                        <textarea class="textarea input-bg" name="catcheverest_options[homepage_subheadline]" cols="65" rows="3"><?php echo esc_textarea( $options[ 'homepage_subheadline' ] ); ?></textarea>
	                                    </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Disable Homepage Headline?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[disable_homepage_headline]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[disable_homepage_headline]" value="1" <?php checked( '1', $options['disable_homepage_headline'] ); ?> /> <?php _e( 'Check to disable', 'catcheverest'); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Disable Homepage Subheadline?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[disable_homepage_subheadline]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[disable_homepage_subheadline]" value="1" <?php checked( '1', $options['disable_homepage_subheadline'] ); ?> /> <?php _e( 'Check to disable', 'catcheverest'); ?></td>
                                    </tr>  
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p>
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->   
                    
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Homepage Featured Content Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                	<tr>
                                        <th scope="row"><?php _e( 'Disable Homepage Featured Content?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[disable_homepage_featured]'>
                                        <td><input type="checkbox" id="favicon" name="catcheverest_options[disable_homepage_featured]" value="1" <?php checked( '1', $options['disable_homepage_featured'] ); ?> /> <?php _e( 'Check to disable', 'catcheverest'); ?></td>
                                    </tr>  
                                    <tr>
                                        <th scope="row">
											<?php _e( 'Headline', 'catcheverest' ); ?>
                                        </th>
                                        <td>
                                        	<input type="text" size="65" name="catcheverest_options[homepage_featured_headline]" value="<?php echo esc_attr( $options[ 'homepage_featured_headline' ] ); ?>" /> <?php _e( 'Leave empty if you want to remove headline', 'catcheverest' ); ?>
	                                    </td>
                                    </tr>
                                    <tr>
                                    	<th scope="row"><?php _e( 'Number of Featured Content', 'catcheverest' ); ?></th>
                                    	<td>
                                        	<input type="text" size="2" name="catcheverest_options[homepage_featured_qty]" value="<?php echo intval( $options[ 'homepage_featured_qty' ] ); ?>" size="2" />
                                        </td>
                                	</tr>
                                    <?php for ( $i = 1; $i <= $options[ 'homepage_featured_qty' ]; $i++ ): ?> 
                                    <tr>
                                    	<th scope="row">
											<strong><?php printf( esc_attr__( 'Featured Content #%s', 'catcheverest' ), $i ); ?></strong>
                                        </th>
                                   	</tr>
                                    <tr>
                                        <th scope="row">
											<?php _e( 'Image', 'catcheverest' ); ?>
                                        </th>
                                        <td>
                                        	<input class="upload-url" size="65" type="text" name="catcheverest_options[homepage_featured_image][<?php echo $i; ?>]" value="<?php if( array_key_exists( 'homepage_featured_image', $options ) && array_key_exists( $i, $options[ 'homepage_featured_image' ] ) ) echo esc_url( $options[ 'homepage_featured_image' ][ $i ] ); ?>" />
                                            <input id="st_upload_button" class="st_upload_button button" name="wsl-image-add" type="button" value="<?php if( array_key_exists( 'homepage_featured_image', $options ) && array_key_exists( $i, $options[ 'homepage_featured_image' ] ) ) { esc_attr_e( 'Change Image','catcheverest' ); } else { esc_attr_e( 'Add Image','catcheverest' ); } ?>" />  
                                      	</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Link URL', 'catcheverest' ); ?></label></th>
                                        <td><input type="text" size="65" name="catcheverest_options[homepage_featured_url][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'homepage_featured_url', $options ) && array_key_exists( $i, $options[ 'homepage_featured_url' ] ) ) echo esc_url( $options[ 'homepage_featured_url' ][ $i ] ); ?>" /> <?php _e( 'Add in the Target URL for the content', 'catcheverest' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e( 'Target. Open Link in New Window?', 'catcheverest' ); ?></label></th>
                                        <input type='hidden' value='0' name='catcheverest_options[homepage_featured_base][<?php echo absint( $i ); ?>]'> 
                                        <td><input type="checkbox" name="catcheverest_options[homepage_featured_base][<?php echo absint( $i ); ?>]" value="1" <?php if( array_key_exists( 'homepage_featured_base', $options ) && array_key_exists( $i, $options[ 'homepage_featured_base' ] ) ) checked( '1', $options[ 'homepage_featured_base' ][ $i ] ); ?> /> <?php _e( 'Check to open in new window', 'catcheverest' ); ?>
                                        </td>
                                    </tr>     
                                    <tr>
                                        <th scope="row">
											<?php _e( 'Title', 'catcheverest' ); ?>
                                        </th>
                                        <td>
                                        	<input type="text" size="65" name="catcheverest_options[homepage_featured_title][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'homepage_featured_title', $options ) && array_key_exists( $i, $options[ 'homepage_featured_title' ] ) ) echo esc_attr( $options[ 'homepage_featured_title' ][ $i ] ); ?>" /> <?php _e( 'Leave empty if you want to remove title', 'catcheverest' ); ?>
	                                    </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Content', 'catcheverest' ); ?>
                                            <p><small><?php _e( 'The appropriate length for Content is around 10 words.', 'catcheverest' ); ?></small></p>
                                        </th>
                                        <td>
	                                        <textarea class="textarea input-bg" name="catcheverest_options[homepage_featured_content][<?php echo absint( $i ); ?>]" cols="80" rows="3"><?php if( array_key_exists( 'homepage_featured_content', $options ) && array_key_exists( $i, $options[ 'homepage_featured_content' ] ) ) echo esc_html( $options[ 'homepage_featured_content' ][ $i ] ); ?></textarea>
	                                    </td>
                                    </tr>
                                    <?php endfor; ?>    
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p>
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->                                     
                
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Homepage / Frontpage Settings', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>                            
                                        <th scope="row"><?php _e( 'Enable Latest Posts?', 'catcheverest' ); ?></th>
                                        <input type='hidden' value='0' name='catcheverest_options[enable_posts_home]'>
                                        <td><input type="checkbox" id="headerlogo" name="catcheverest_options[enable_posts_home]" value="1" <?php checked( '1', $options['enable_posts_home'] ); ?> /> <?php _e( 'Check to Enable', 'catcheverest'); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e( 'Add Page instead of Latest Posts', 'catcheverest' ); ?></th>
                                        <td><a class="button" href="<?php echo esc_url( admin_url( 'options-reading.php' ) ) ; ?>" title="<?php esc_attr_e( 'Widgets', 'catcheverest' ); ?>" target="_blank"><?php _e( 'Click Here to Set Static Front Page Instead of Latest Posts', 'catcheverest' );?></a></td>
                                    </tr>                                
                                    <tr>
                                        <th scope="row">
                                            <?php _e( 'Homepage posts categories:', 'catcheverest' ); ?>
                                            <p>
                                                <small><?php _e( 'Only posts that belong to the categories selected here will be displayed on the front page.', 'catcheverest' ); ?></small>
                                            </p>
                                        </th>
                                        <td>
                                            <select name="catcheverest_options[front_page_category][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple">
                                                <option value="0" <?php if ( empty( $options['front_page_category'] ) ) { echo 'selected="selected"'; } ?>><?php _e( '--Disabled--', 'catcheverest' ); ?></option>
                                                <?php /* Get the list of categories */  
                                                    $categories = get_categories();
                                                    if( empty( $options[ 'front_page_category' ] ) ) {
                                                        $options[ 'front_page_category' ] = array();
                                                    }
                                                    foreach ( $categories as $category) :
                                                ?>
                                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, $options['front_page_category'] ) ) {echo 'selected="selected"';}?>><?php echo $category->cat_name; ?></option>
                                                <?php endforeach; ?>
                                            </select><br />
                                            <span class="description"><?php _e( 'You may select multiple categories by holding down the CTRL key.', 'catcheverest' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                  	</div><!-- .option-container --> 
            	</div><!-- #homepagesettings -->       
                
                <!-- Options for Slider Settings -->
                <div id="slidersettings">
           			<div class="option-container">
                		<h3 class="option-toggle"><a href="#"><?php _e( 'Slider Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">  
                                <tr>
                                    <th scope="row"><label><?php _e( 'Enable Slider', 'catcheverest' ); ?></label></th>
                                    <td>
                                        <label title="enable-slider-homepager" class="box">
                                        <input type="radio" name="catcheverest_options[enable_slider]" id="enable-slider-homepage" <?php checked($options['enable_slider'], 'enable-slider-homepage') ?> value="enable-slider-homepage"  />
                                        <?php _e( 'Homepage / Frontpage', 'catcheverest' ); ?>
                                        </label>
                                        <label title="enable-slider-allpage" class="box">
                                        <input type="radio" name="catcheverest_options[enable_slider]" id="enable-slider-allpage" <?php checked($options['enable_slider'], 'enable-slider-allpage') ?> value="enable-slider-allpage"  />
                                         <?php _e( 'Entire Site', 'catcheverest' ); ?>
                                        </label>
                                        <label title="disable-slider" class="box">
                                        <input type="radio" name="catcheverest_options[enable_slider]" id="disable-slider" <?php checked($options['enable_slider'], 'disable-slider') ?> value="disable-slider"  />
                                         <?php _e( 'Disable', 'catcheverest' ); ?>
                                        </label>                                
                                    </td>
                                </tr>                        
                                <tr>
                                    <th scope="row"><?php _e( 'Number of Slides', 'catcheverest' ); ?></th>
                                    <td><input type="text" name="catcheverest_options[slider_qty]" value="<?php echo intval( $options[ 'slider_qty' ] ); ?>" size="2" /></td>
                                </tr> 
                                <tr>
                                    <th>
                                        <label for="catcheverest_cycle_style"><?php _e( 'Transition Effect:', 'catcheverest' ); ?></label>
                                    </th>
                                    <td>
                                        <select id="catcheverest_cycle_style" name="catcheverest_options[transition_effect]">
                                            <option value="fade" <?php selected('fade', $options['transition_effect']); ?>><?php _e( 'fade', 'catcheverest' ); ?></option>
                                            <option value="wipe" <?php selected('wipe', $options['transition_effect']); ?>><?php _e( 'wipe', 'catcheverest' ); ?></option>
                                            <option value="scrollUp" <?php selected('scrollUp', $options['transition_effect']); ?>><?php _e( 'scrollUp', 'catcheverest' ); ?></option>
                                            <option value="scrollDown" <?php selected('scrollDown', $options['transition_effect']); ?>><?php _e( 'scrollDown', 'catcheverest' ); ?></option>
                                            <option value="scrollLeft" <?php selected('scrollLeft', $options['transition_effect']); ?>><?php _e( 'scrollLeft', 'catcheverest' ); ?></option>
                                            <option value="scrollRight" <?php selected('scrollRight', $options['transition_effect']); ?>><?php _e( 'scrollRight', 'catcheverest' ); ?></option>
                                            <option value="blindX" <?php selected('blindX', $options['transition_effect']); ?>><?php _e( 'blindX', 'catcheverest' ); ?></option>
                                            <option value="blindY" <?php selected('blindY', $options['transition_effect']); ?>><?php _e( 'blindY', 'catcheverest' ); ?></option>
                                            <option value="blindZ" <?php selected('blindZ', $options['transition_effect']); ?>><?php _e( 'blindZ', 'catcheverest' ); ?></option>
                                            <option value="cover" <?php selected('cover', $options['transition_effect']); ?>><?php _e( 'cover', 'catcheverest' ); ?></option>
                                            <option value="shuffle" <?php selected('shuffle', $options['transition_effect']); ?>><?php _e( 'shuffle', 'catcheverest' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e( 'Transition Delay', 'catcheverest' ); ?></th>
                                    <td>
                                        <input type="text" name="catcheverest_options[transition_delay]" value="<?php echo $options[ 'transition_delay' ]; ?>" size="2" />
                                       <span class="description"><?php _e( 'second(s)', 'catcheverest' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e( 'Transition Length', 'catcheverest' ); ?></th>
                                    <td>
                                        <input type="text" name="catcheverest_options[transition_duration]" value="<?php echo $options[ 'transition_duration' ]; ?>" size="2" />
                                        <span class="description"><?php _e( 'second(s)', 'catcheverest' ); ?></span>
                                    </td>
                                </tr>                      
        
                            </table>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
            		</div><!-- .option-container --> 
              
            
            		<div class="option-container post-slider">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Featured Post Slider Options', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">
                                <tr>                            
                                    <th scope="row"><?php _e( 'Exclude Slider post from Homepage posts?', 'catcheverest' ); ?></th>
                                     <input type='hidden' value='0' name='catcheverest_options[exclude_slider_post]'>
                                    <td><input type="checkbox" id="headerlogo" name="catcheverest_options[exclude_slider_post]" value="1" <?php checked( '1', $options['exclude_slider_post'] ); ?> /> <?php _e('Check to exclude', 'catcheverest'); ?></td>
                                </tr>
                                <tbody class="sortable">
                                    <?php for ( $i = 1; $i <= $options[ 'slider_qty' ]; $i++ ): ?>
                                    <tr>
                                        <th scope="row"><label class="handle"><?php _e( 'Featured Slider Post #', 'catcheverest' ); ?><span class="count"><?php echo absint( $i ); ?></span></label></th>
                                        <td><input type="text" name="catcheverest_options[featured_slider][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'featured_slider', $options ) && array_key_exists( $i, $options[ 'featured_slider' ] ) ) echo absint( $options[ 'featured_slider' ][ $i ] ); ?>" />
                                        <a href="<?php bloginfo ( 'wpurl' );?>/wp-admin/post.php?post=<?php if( array_key_exists ( 'featured_slider', $options ) && array_key_exists ( $i, $options[ 'featured_slider' ] ) ) echo absint( $options[ 'featured_slider' ][ $i ] ); ?>&action=edit" class="button" title="<?php esc_attr_e('Click Here To Edit'); ?>" target="_blank"><?php _e( 'Click Here To Edit', 'catcheverest' ); ?></a>
                                        </td>
                                    </tr>                           
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                            <p><?php _e( 'Note: Here You can put your Post IDs which displays on Homepage as slider.', 'catcheverest' ); ?> </p>
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->                
				</div><!-- #slidersettings -->
                
  
                <!-- Options for Social Links -->
                <div id="sociallinks">
                	<div class="option-container">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Facebook', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_facebook]" value="<?php echo esc_url( $options[ 'social_facebook' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Twitter', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_twitter]" value="<?php echo esc_url( $options[ 'social_twitter'] ); ?>" />
                                    </td>
                                </tr>
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Google+', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_googleplus]" value="<?php echo esc_url( $options[ 'social_googleplus'] ); ?>" />
                                    </td>
                                </tr>
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Pinterest', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_pinterest]" value="<?php echo esc_url( $options[ 'social_pinterest'] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Youtube', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_youtube]" value="<?php echo esc_url( $options[ 'social_youtube' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Vimeo', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_vimeo]" value="<?php echo esc_url( $options[ 'social_vimeo' ] ); ?>" />
                                    </td>
                                </tr>
                                
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Linkedin', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_linkedin]" value="<?php echo esc_url( $options[ 'social_linkedin'] ); ?>" />
                                    </td>
                                </tr>
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Slideshare', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_slideshare]" value="<?php echo esc_url( $options[ 'social_slideshare'] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Foursquare', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_foursquare]" value="<?php echo esc_url( $options[ 'social_foursquare' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Flickr', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_flickr]" value="<?php echo esc_url( $options[ 'social_flickr' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Tumblr', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_tumblr]" value="<?php echo esc_url( $options[ 'social_tumblr' ] ); ?>" />
                                    </td>
                                </tr> 
                                <tr>
                                    <th scope="row"><h4><?php _e( 'deviantART', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_deviantart]" value="<?php echo esc_url( $options[ 'social_deviantart' ] ); ?>" />
                                    </td>
                                </tr> 
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Dribbble', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_dribbble]" value="<?php echo esc_url( $options[ 'social_dribbble' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'MySpace', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_myspace]" value="<?php echo esc_url( $options[ 'social_myspace' ] ); ?>" />
                                    </td>
                                </tr> 
                                <tr>
                                    <th scope="row"><h4><?php _e( 'WordPress', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_wordpress]" value="<?php echo esc_url( $options[ 'social_wordpress' ] ); ?>" />
                                    </td>
                                </tr>                           
                                <tr>
                                    <th scope="row"><h4><?php _e( 'RSS', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_rss]" value="<?php echo esc_url( $options[ 'social_rss' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Delicious', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_delicious]" value="<?php echo esc_url( $options[ 'social_delicious' ] ); ?>" />
                                    </td>
                                </tr>                           
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Last.fm', 'catcheverest' ); ?> </h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_lastfm]" value="<?php echo esc_url( $options[ 'social_lastfm' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Instagram', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_instagram]" value="<?php echo esc_url( $options[ 'social_instagram' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'GitHub', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_github]" value="<?php echo esc_url( $options[ 'social_github' ] ); ?>" />
                                    </td>
                                </tr> 
                                <tr> 
                                    <th scope="row"><h4><?php _e( 'Vkontakte', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_vkontakte]" value="<?php echo esc_url( $options[ 'social_vkontakte'] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'My World', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_myworld]" value="<?php echo esc_url( $options[ 'social_myworld' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Odnoklassniki', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_odnoklassniki]" value="<?php echo esc_url( $options[ 'social_odnoklassniki' ] ); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Goodreads', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_goodreads]" value="<?php echo esc_url( $options[ 'social_goodreads' ] ); ?>" />
                                    </td>
                                </tr>                                
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Skype', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_skype]" value="<?php echo esc_attr( $options[ 'social_skype' ] ); ?>" />
                                    </td>
                                </tr>  
                                <tr>
                                    <th scope="row"><h4><?php _e( 'Soundcloud', 'catcheverest' ); ?></h4></th>
                                    <td><input type="text" size="45" name="catcheverest_options[social_soundcloud]" value="<?php echo esc_url( $options[ 'social_soundcloud' ] ); ?>" />
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>                           
            			<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p>                    
                    </div><!-- .option-container -->
                </div><!-- #sociallinks -->
                
                
                <!-- Options for Webmaster Tools -->
                <div id="webmaster">
                   
                    <div class="option-container">
                        <h3 class="option-toggle"><a href="#"><?php _e( 'Header and Footer Codes', 'catcheverest' ); ?></a></h3>
                        <div class="option-content inside">
                            <table class="form-table">  
                                <tbody>       
                                    <tr>
                                        <th scope="row"><?php _e( 'Code to display on Header', 'catcheverest' ); ?></th>
                                        <td>
                                        <textarea name="catcheverest_options[analytic_header]" id="analytics" rows="7" cols="80" ><?php echo esc_html( $options[ 'analytic_header' ] ); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td><td><?php _e('Note: Note: Here you can put scripts from Google, Facebook, Twitter, Add This etc. which will load on Header', 'catcheverest' ); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php _e('Code to display on Footer', 'catcheverest' ); ?></th>
                                        <td>
                                         <textarea name="catcheverest_options[analytic_footer]" id="analytics" rows="7" cols="80" ><?php echo esc_html( $options[ 'analytic_footer' ] ); ?></textarea>
                             
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td><td><?php _e( 'Note: Here you can put scripts from Google, Facebook, Twitter, Add This etc. which will load on footer', 'catcheverest' ); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'catcheverest' ); ?>" /></p> 
                        </div><!-- .option-content -->
                    </div><!-- .option-container -->  
                </div><!-- #webmaster -->

            </div><!-- #catcheverest_ad_tabs -->
		</form>
	</div><!-- .wrap -->
<?php
}


/**
 * Validate content options
 * @param array $options
 * @uses esc_url_raw, absint, esc_textarea, sanitize_text_field, catcheverest_invalidate_caches
 * @return array
 */
function catcheverest_theme_options_validate( $options ) {
	global $catcheverest_options_settings;
    $input_validated = $catcheverest_options_settings;
	
	global $catcheverest_options_defaults;
	$defaults = $catcheverest_options_defaults;
	
    $input = array();
    $input = $options;
	
	// Data Validation for Resonsive Design	
	if ( isset( $input['disable_responsive'] ) ) {
		// Our checkbox value is either 0 or 1 
		$input_validated[ 'disable_responsive' ] = $input[ 'disable_responsive' ];
	}
	
	// Data Validation for Favicon		
	if ( isset( $input[ 'fav_icon' ] ) ) {
		$input_validated[ 'fav_icon' ] = esc_url_raw( $input[ 'fav_icon' ] );
	}
	if ( isset( $input['remove_favicon'] ) ) {
		// Our checkbox value is either 0 or 1 
		$input_validated[ 'remove_favicon' ] = $input[ 'remove_favicon' ];
	}
	
	// Data Validation for web clip icon
	if ( isset( $input[ 'web_clip' ] ) ) {
		$input_validated[ 'web_clip' ] = esc_url_raw( $input[ 'web_clip' ] );
	}
	if ( isset( $input['remove_web_clip'] ) ) {
		// Our checkbox value is either 0 or 1 
		$input_validated[ 'remove_web_clip' ] = $input[ 'remove_web_clip' ];
	}	
	
	// Data Validation for Header Sidebar	
	if ( isset( $input[ 'disable_header_right_sidebar' ] ) ) {
		$input_validated[ 'disable_header_right_sidebar' ] = $input[ 'disable_header_right_sidebar' ];
	}	
	
	
	// Data Validation for Custom CSS Style
	if ( isset( $input['custom_css'] ) ) {
		$input_validated['custom_css'] = wp_kses_stripslashes($input['custom_css']);
	}
	
	// Data Validation for Homepage Headline Message
	if( isset( $input[ 'homepage_headline' ] ) ) {
		$input_validated['homepage_headline'] =  sanitize_text_field( $input[ 'homepage_headline' ] ) ? $input [ 'homepage_headline' ] : $defaults[ 'homepage_headline' ];
	}
	if( isset( $input[ 'homepage_subheadline' ] ) ) {
		$input_validated['homepage_subheadline'] =  sanitize_text_field( $input[ 'homepage_subheadline' ] ) ? $input [ 'homepage_subheadline' ] : $defaults[ 'homepage_subheadline' ];
	}	
	if ( isset( $input[ 'disable_homepage_headline' ] ) ) {
		$input_validated[ 'disable_homepage_headline' ] = $input[ 'disable_homepage_headline' ];
	}
	if ( isset( $input[ 'disable_homepage_subheadline' ] ) ) {
		$input_validated[ 'disable_homepage_subheadline' ] = $input[ 'disable_homepage_subheadline' ];
	}	
	

	// Data Validation for Homepage Featured Content 
	if ( isset( $input[ 'disable_homepage_featured' ] ) ) {
		$input_validated[ 'disable_homepage_featured' ] = $input[ 'disable_homepage_featured' ];
	}	
	if( isset( $input[ 'homepage_featured_headline' ] ) ) {
		$input_validated['homepage_featured_headline'] =  sanitize_text_field( $input[ 'homepage_featured_headline' ] ) ? $input [ 'homepage_featured_headline' ] : $defaults[ 'homepage_featured_headline' ];
	}	
	if ( isset( $input[ 'homepage_featured_image' ] ) ) {
		$input_validated[ 'homepage_featured_image' ] = array();
	}
	if ( isset( $input[ 'homepage_featured_url' ] ) ) {
		$input_validated[ 'homepage_featured_url' ] = array();
	}
	if ( isset( $input[ 'homepage_featured_base' ] ) ) {
		$input_validated[ 'homepage_featured_base' ] = array();
	}	
	if ( isset( $input[ 'homepage_featured_title' ] ) ) {
		$input_validated[ 'homepage_featured_title' ] = array();
	}
	if ( isset( $input[ 'homepage_featured_content' ] ) ) {
		$input_validated[ 'homepage_featured_content' ] = array();
	}
	if ( isset( $input[ 'homepage_featured_qty' ] ) ) {
		$input_validated[ 'homepage_featured_qty' ] = absint( $input[ 'homepage_featured_qty' ] ) ? $input [ 'homepage_featured_qty' ] : $defaults[ 'homepage_featured_qty' ];
		for ( $i = 1; $i <= $input [ 'homepage_featured_qty' ]; $i++ ) {
			if ( !empty( $input[ 'homepage_featured_image' ][ $i ] ) ) {
				$input_validated[ 'homepage_featured_image' ][ $i ] = esc_url_raw($input[ 'homepage_featured_image' ][ $i ] );
			}
			if ( !empty( $input[ 'homepage_featured_url' ][ $i ] ) ) {
				$input_validated[ 'homepage_featured_url'][ $i ] = esc_url_raw($input[ 'homepage_featured_url'][ $i ]);
			}
			if ( !empty( $input[ 'homepage_featured_base' ][ $i ] ) ) {
				$input_validated[ 'homepage_featured_base'][ $i ] = $input[ 'homepage_featured_base'][ $i ];
			}
			if ( !empty( $input[ 'homepage_featured_title' ][ $i ] ) ) {
				$input_validated[ 'homepage_featured_title'][ $i ] = sanitize_text_field($input[ 'homepage_featured_title'][ $i ]);
			}
			if ( !empty( $input[ 'homepage_featured_content' ][ $i ] ) ) {
				$input_validated[ 'homepage_featured_content'][ $i ] = wp_kses_stripslashes($input[ 'homepage_featured_content'][ $i ]);
			}	
		}
	}	
	
	// Data Validation for Homepage
	if ( isset( $input[ 'enable_posts_home' ] ) ) {
		$input_validated[ 'enable_posts_home' ] = $input[ 'enable_posts_home' ];
	}	
	

    if ( isset( $input['exclude_slider_post'] ) ) {
        // Our checkbox value is either 0 or 1 
   		$input_validated[ 'exclude_slider_post' ] = $input[ 'exclude_slider_post' ];	
	
    }
	// Front page posts categories
    if( isset( $input['front_page_category' ] ) ) {
		$input_validated['front_page_category'] = $input['front_page_category'];
    }
	

	// data validation for Enable Slider
	if( isset( $input[ 'enable_slider' ] ) ) {
		$input_validated[ 'enable_slider' ] = $input[ 'enable_slider' ];
	}	
    // data validation for number of slides
	if ( isset( $input[ 'slider_qty' ] ) ) {
		$input_validated[ 'slider_qty' ] = absint( $input[ 'slider_qty' ] ) ? $input [ 'slider_qty' ] : 4;
	}	
    // data validation for transition effect
    if( isset( $input[ 'transition_effect' ] ) ) {
        $input_validated['transition_effect'] = wp_filter_nohtml_kses( $input['transition_effect'] );
    }
    // data validation for transition delay
    if ( isset( $input[ 'transition_delay' ] ) && is_numeric( $input[ 'transition_delay' ] ) ) {
        $input_validated[ 'transition_delay' ] = $input[ 'transition_delay' ];
    }
    // data validation for transition length
    if ( isset( $input[ 'transition_duration' ] ) && is_numeric( $input[ 'transition_duration' ] ) ) {
        $input_validated[ 'transition_duration' ] = $input[ 'transition_duration' ];
    }	
	
	// data validation for Featured Post Slider
	if ( isset( $input[ 'featured_slider' ] ) ) {
		$input_validated[ 'featured_slider' ] = array();
	}
 	if ( isset( $input[ 'slider_qty' ] ) )	{	
		for ( $i = 1; $i <= $input [ 'slider_qty' ]; $i++ ) {
			if ( !empty( $input[ 'featured_slider' ][ $i ] ) && intval( $input[ 'featured_slider' ][ $i ] ) ) {
				$input_validated[ 'featured_slider' ][ $i ] = absint($input[ 'featured_slider' ][ $i ] );
			}
		}
	}	
	
	
	// data validation for Social Icons
	if( isset( $input[ 'social_facebook' ] ) ) {
		$input_validated[ 'social_facebook' ] = esc_url_raw( $input[ 'social_facebook' ] );
	}
	if( isset( $input[ 'social_twitter' ] ) ) {
		$input_validated[ 'social_twitter' ] = esc_url_raw( $input[ 'social_twitter' ] );
	}
	if( isset( $input[ 'social_googleplus' ] ) ) {
		$input_validated[ 'social_googleplus' ] = esc_url_raw( $input[ 'social_googleplus' ] );
	}
	if( isset( $input[ 'social_pinterest' ] ) ) {
		$input_validated[ 'social_pinterest' ] = esc_url_raw( $input[ 'social_pinterest' ] );
	}	
	if( isset( $input[ 'social_youtube' ] ) ) {
		$input_validated[ 'social_youtube' ] = esc_url_raw( $input[ 'social_youtube' ] );
	}
	if( isset( $input[ 'social_vimeo' ] ) ) {
		$input_validated[ 'social_vimeo' ] = esc_url_raw( $input[ 'social_vimeo' ] );
	}	
	if( isset( $input[ 'social_linkedin' ] ) ) {
		$input_validated[ 'social_linkedin' ] = esc_url_raw( $input[ 'social_linkedin' ] );
	}
	if( isset( $input[ 'social_slideshare' ] ) ) {
		$input_validated[ 'social_slideshare' ] = esc_url_raw( $input[ 'social_slideshare' ] );
	}	
	if( isset( $input[ 'social_foursquare' ] ) ) {
		$input_validated[ 'social_foursquare' ] = esc_url_raw( $input[ 'social_foursquare' ] );
	}
	if( isset( $input[ 'social_flickr' ] ) ) {
		$input_validated[ 'social_flickr' ] = esc_url_raw( $input[ 'social_flickr' ] );
	}
	if( isset( $input[ 'social_tumblr' ] ) ) {
		$input_validated[ 'social_tumblr' ] = esc_url_raw( $input[ 'social_tumblr' ] );
	}	
	if( isset( $input[ 'social_deviantart' ] ) ) {
		$input_validated[ 'social_deviantart' ] = esc_url_raw( $input[ 'social_deviantart' ] );
	}
	if( isset( $input[ 'social_dribbble' ] ) ) {
		$input_validated[ 'social_dribbble' ] = esc_url_raw( $input[ 'social_dribbble' ] );
	}	
	if( isset( $input[ 'social_myspace' ] ) ) {
		$input_validated[ 'social_myspace' ] = esc_url_raw( $input[ 'social_myspace' ] );
	}
	if( isset( $input[ 'social_wordpress' ] ) ) {
		$input_validated[ 'social_wordpress' ] = esc_url_raw( $input[ 'social_wordpress' ] );
	}	
	if( isset( $input[ 'social_rss' ] ) ) {
		$input_validated[ 'social_rss' ] = esc_url_raw( $input[ 'social_rss' ] );
	}
	if( isset( $input[ 'social_delicious' ] ) ) {
		$input_validated[ 'social_delicious' ] = esc_url_raw( $input[ 'social_delicious' ] );
	}	
	if( isset( $input[ 'social_lastfm' ] ) ) {
		$input_validated[ 'social_lastfm' ] = esc_url_raw( $input[ 'social_lastfm' ] );
	}	
	if( isset( $input[ 'social_instagram' ] ) ) {
		$input_validated[ 'social_instagram' ] = esc_url_raw( $input[ 'social_instagram' ] );
	}	
	if( isset( $input[ 'social_github' ] ) ) {
		$input_validated[ 'social_github' ] = esc_url_raw( $input[ 'social_github' ] );
	}	
	if( isset( $input[ 'social_vkontakte' ] ) ) {
		$input_validated[ 'social_vkontakte' ] = esc_url_raw( $input[ 'social_vkontakte' ] );
	}	
	if( isset( $input[ 'social_myworld' ] ) ) {
		$input_validated[ 'social_myworld' ] = esc_url_raw( $input[ 'social_myworld' ] );
	}
	if( isset( $input[ 'social_odnoklassniki' ] ) ) {
		$input_validated[ 'social_odnoklassniki' ] = esc_url_raw( $input[ 'social_odnoklassniki' ] );
	}	
	if( isset( $input[ 'social_goodreads' ] ) ) {
		$input_validated[ 'social_goodreads' ] = esc_url_raw( $input[ 'social_goodreads' ] );
	}	
	if( isset( $input[ 'social_skype' ] ) ) {
		$input_validated[ 'social_skype' ] = sanitize_text_field( $input[ 'social_skype' ] );
	}
	if( isset( $input[ 'social_soundcloud' ] ) ) {
		$input_validated[ 'social_soundcloud' ] = esc_url_raw( $input[ 'social_soundcloud' ] );
	}		
		
	//Webmaster Tool Verification
	if( isset( $input[ 'google_verification' ] ) ) {
		$input_validated[ 'google_verification' ] = wp_filter_post_kses( $input[ 'google_verification' ] );
	}
	if( isset( $input[ 'yahoo_verification' ] ) ) {
		$input_validated[ 'yahoo_verification' ] = wp_filter_post_kses( $input[ 'yahoo_verification' ] );
	}
	if( isset( $input[ 'bing_verification' ] ) ) {
		$input_validated[ 'bing_verification' ] = wp_filter_post_kses( $input[ 'bing_verification' ] );
	}	
	if( isset( $input[ 'analytic_header' ] ) ) {
		$input_validated[ 'analytic_header' ] = wp_kses_stripslashes( $input[ 'analytic_header' ] );
	}
	if( isset( $input[ 'analytic_footer' ] ) ) {
		$input_validated[ 'analytic_footer' ] = wp_kses_stripslashes( $input[ 'analytic_footer' ] );	
	}		
	
    // Layout settings verification
	if( isset( $input[ 'sidebar_layout' ] ) ) {
		$input_validated[ 'sidebar_layout' ] = $input[ 'sidebar_layout' ];
	}
	if( isset( $input[ 'content_layout' ] ) ) {
		$input_validated[ 'content_layout' ] = $input[ 'content_layout' ];
	}
	
    if( isset( $input[ 'more_tag_text' ] ) ) {
        $input_validated[ 'more_tag_text' ] = htmlentities( sanitize_text_field ( $input[ 'more_tag_text' ] ), ENT_QUOTES, 'UTF-8' );
    }   

    if( isset( $input[ 'search_display_text' ] ) ) {
        $input_validated[ 'search_display_text' ] = sanitize_text_field( $input[ 'search_display_text' ] ) ? $input [ 'search_display_text' ] : $defaults[ 'search_display_text' ];
    }
	
	if ( isset( $input['reset_layout'] ) ) {
		// Our checkbox value is either 0 or 1 
		$input_validated[ 'reset_layout' ] = $input[ 'reset_layout' ];
	}	
	
	//Reset Color Options
	if( $input[ 'reset_layout' ] == 1 ) {
		global $catcheverest_options_defaults;
		$defaults = $catcheverest_options_defaults;

		$input_validated[ 'sidebar_layout' ] = $defaults[ 'sidebar_layout' ];
		$input_validated[ 'content_layout' ] = $defaults[ 'content_layout' ];
	}		
	
    //data validation for excerpt length
    if ( isset( $input[ 'excerpt_length' ] ) ) {
        $input_validated[ 'excerpt_length' ] = absint( $input[ 'excerpt_length' ] ) ? $input [ 'excerpt_length' ] : $defaults[ 'excerpt_length' ];
    }

	//Feed Redirect
	if ( isset( $input[ 'feed_url' ] ) ) {
		$input_validated['feed_url'] = esc_url_raw($input['feed_url']);
	}
	
	//Clearing the theme option cache
	if( function_exists( 'catcheverest_themeoption_invalidate_caches' ) ) catcheverest_themeoption_invalidate_caches();
	
	return $input_validated;
}


/*
 * Clearing the cache if any changes in Admin Theme Option
 */
function catcheverest_themeoption_invalidate_caches(){
	delete_transient( 'catcheverest_favicon' );	  // favicon on cpanel/ backend and frontend
	delete_transient( 'catcheverest_web_clip' ); // web clip icons
	delete_transient( 'catcheverest_post_sliders' ); // featured post slider
	delete_transient( 'catcheverest_homepage_headline' ); // Homepage Headline Message
	delete_transient( 'catcheverest_homepage_featured_content' ); // Homepage Featured Content
	delete_transient( 'catcheverest_social_networks' ); // Social Networks
	delete_transient( 'catcheverest_webmaster' ); // scripts which loads on header	
	delete_transient( 'catcheverest_footercode' ); // scripts which loads on footer
	delete_transient( 'catcheverest_inline_css' ); // Custom Inline CSS
}


/*
 * Clearing the cache if any changes in post or page
 */
function catcheverest_post_invalidate_caches(){
	delete_transient( 'catcheverest_post_sliders' );
}
//Add action hook here save post
add_action( 'save_post', 'catcheverest_post_invalidate_caches' );


/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @uses add_shortcode() to create new shortcodes.
 */
function catcheverest_add_shortcodes() {
	/* Add theme-specific shortcodes. */
	add_shortcode( 'footer-image', 'catcheverest_footer_image_shortcode' );
	add_shortcode( 'the-year', 'catcheverest_the_year_shortcode' );
	add_shortcode( 'site-link', 'catcheverest_site_link_shortcode' );
	add_shortcode( 'wp-link', 'catcheverest_wp_link_shortcode' );
	add_shortcode( 'theme-link', 'catcheverest_theme_link_shortcode' );
	
}
/* Register shortcodes. */
add_action( 'init', 'catcheverest_add_shortcodes' );


/**
 * Shortcode to display Footer Image.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function catcheverest_footer_image_shortcode() {
	if( function_exists( 'catcheverest_footerlogo' ) ) :
    	return catcheverest_footerlogo(); 
    endif;
}


/**
 * Shortcode to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function catcheverest_the_year_shortcode() {
	return date( __( 'Y', 'catcheverest' ) );
}


/**
 * Shortcode to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function catcheverest_site_link_shortcode() {
	return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}


/**
 * Shortcode to display a link to WordPress.org.
 *
 * @return string
 */
function catcheverest_wp_link_shortcode() {
	return '<a href="http://wordpress.org" target="_blank" title="' . esc_attr__( 'WordPress', 'catcheverest' ) . '"><span>' . __( 'WordPress', 'catcheverest' ) . '</span></a>';
}


/**
 * Shortcode to display a link to Theme Link.
 *
 * @return string
 */
function catcheverest_theme_link_shortcode() {
	return '<a href="http://catchthemes.com/themes/catch-everest" target="_blank" title="' . esc_attr__( 'Catch Everest', 'catcheverest' ) . '"><span>' . __( 'Catch Everest', 'catcheverest' ) . '</span></a>';
}