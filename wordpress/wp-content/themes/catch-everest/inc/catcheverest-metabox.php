<?php
/**
 * Catch Everest Custom meta box
 *
 * @package Catch Themes
 * @subpackage Catch_Everest
 * @since Catch Everest 1.0
 */
 
 // Add the Meta Box  
function catcheverest_add_custom_box() {
	add_meta_box(
		'siderbar-layout',							  	//Unique ID
       __( 'Select Sidebar layout', 'catcheverest' ),   //Title
        'catcheverest_sidebar_layout',                   //Callback function
        'page'                                          //show metabox in pages
    ); 
	add_meta_box(
		'siderbar-layout',							  	//Unique ID
       __( 'Select Sidebar layout', 'catcheverest' ),   //Title
        'catcheverest_sidebar_layout',                   //Callback function
        'post'                                          //show metabox in pages
    ); 
}

add_action( 'add_meta_boxes', 'catcheverest_add_custom_box' );

global $sidebar_layout;
$sidebar_layout = array(
		 'default-sidebar' => array(
            			'id'		=> 'catcheverest-sidebarlayout',
						'value' 	=> 'default',
						'label' 	=> __( 'Default Layout Set in', 'catcheverest' ).' <a href="'.get_bloginfo('url').'/wp-admin/themes.php?page=theme_options" target="_blank">'. __( 'Theme Settings', 'catcheverest' ).'</a>',
						'thumbnail' => ' '
        			),
       'right-sidebar' => array(
						'id' => 'catcheverest-sidebarlayout',
						'value' => 'right-sidebar',
						'label' => __( 'Right sidebar', 'catcheverest' ),
						'thumbnail' => get_template_directory_uri() . '/inc/panel/images/right-sidebar.png'
       				),
        'left-sidebar' => array(
            			'id'		=> 'catcheverest-sidebarlayout',
						'value' 	=> 'left-sidebar',
						'label' 	=> __( 'Left sidebar', 'catcheverest' ),
						'thumbnail' => get_template_directory_uri() . '/inc/panel/images/left-sidebar.png'
       				),	 
        'no-sidebar' => array(
            			'id'		=> 'catcheverest-sidebarlayout',
						'value' 	=> 'no-sidebar',
						'label' 	=> __( 'No sidebar', 'catcheverest' ),
						'thumbnail' => get_template_directory_uri() . '/inc/panel/images/no-sidebar.png'
        			),
		'no-sidebar-full-width' => array(
            			'id'		=> 'catcheverest-sidebarlayout',
						'value' 	=> 'no-sidebar-full-width',
						'label' 	=> __( 'No sidebar, Full Width', 'catcheverest' ),
						'thumbnail' => get_template_directory_uri() . '/inc/panel/images/no-sidebar-fullwidth.png'
        			)
    );
	
/**
 * @renders metabox to for sidebar layout
 */
function catcheverest_sidebar_layout() {  
    global $sidebar_layout, $post;  
    // Use nonce for verification  
    wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );

    // Begin the field table and loop  ?>
    <table id="sidebar-metabox" class="form-table" width="100%">
        <tbody> 
            <tr>
                <?php  
                foreach ($sidebar_layout as $field) {  
                    $meta = get_post_meta( $post->ID, $field['id'], true );
					if(empty( $meta ) ){
						$meta='default';
					}
					if( $field['thumbnail']==' ' ): ?>
							<label class="description">
								<input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
							</label>
                    <?php else: ?>
                        <td>
                            <label class="description">
                                <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" width="136" height="122" alt="" /></span></br>
                                <input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
                            </label>
                        </td>
					<?php endif;
                } // end foreach 
                ?>
            </tr>
		</tbody>
	</table>
<?php 
}
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function catcheverest_save_custom_meta( $post_id ) { 
	global $sidebar_layout, $post; 
	
	// Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
        return;
		
	// Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
		
	if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }  
	
	foreach ($sidebar_layout as $field) {  
		//Execute this saving function
		$old = get_post_meta( $post_id, $field['id'], true); 
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {  
			update_post_meta($post_id, $field['id'], $new);  
		} elseif ('' == $new && $old) {  
			delete_post_meta($post_id, $field['id'], $old);  
		} 
	 } // end foreach   
}
add_action('save_post', 'catcheverest_save_custom_meta'); 