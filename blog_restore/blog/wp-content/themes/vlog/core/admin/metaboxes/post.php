<?php

/**
 * Load post metaboxes
 * 
 * Callback function for post metaboxes load
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_load_post_metaboxes' ) ) :
	function vlog_load_post_metaboxes() {

		/* Layout metabox */
		add_meta_box(
			'vlog_layout',
			esc_html__( 'Cover Layout', 'vlog' ),
			'vlog_layout_metabox',
			'post',
			'side',
			'default'
		);

		/* Sidebar metabox */
		add_meta_box(
			'vlog_sidebar',
			esc_html__( 'Sidebar', 'vlog' ),
			'vlog_sidebar_metabox',
			'post',
			'side',
			'default'
		);

	}
endif;


/**
 * Save post meta
 * 
 * Callback function to save post meta data
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_save_post_metaboxes' ) ) :
	function vlog_save_post_metaboxes( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['vlog_post_metabox_nonce'] ) || !wp_verify_nonce( $_POST['vlog_post_metabox_nonce'], 'vlog_post_metabox_save' ) ) {
   			return;
		}


		if ( $post->post_type == 'post' && isset( $_POST['vlog'] ) ) {
			$post_type = get_post_type_object( $post->post_type );
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

			$vlog_meta = array();

			if( isset( $_POST['vlog']['use_sidebar'] ) &&  $_POST['vlog']['use_sidebar'] != 'inherit' ){
				$vlog_meta['use_sidebar'] = $_POST['vlog']['use_sidebar'];
			}
			
			if( isset( $_POST['vlog']['sidebar'] ) &&  $_POST['vlog']['sidebar'] != 'inherit' ){
				$vlog_meta['sidebar'] = $_POST['vlog']['sidebar'];
			}

			if( isset( $_POST['vlog']['sticky_sidebar'] ) &&  $_POST['vlog']['sticky_sidebar'] != 'inherit' ){
				$vlog_meta['sticky_sidebar'] = $_POST['vlog']['sticky_sidebar'];
			}

			if( isset( $_POST['vlog']['layout'] ) &&  $_POST['vlog']['layout'] != 'inherit' ){
				$vlog_meta['layout'] = $_POST['vlog']['layout'];
			}
			
			if(!empty($vlog_meta)){
				update_post_meta( $post_id, '_vlog_meta', $vlog_meta );
			} else {
				delete_post_meta( $post_id, '_vlog_meta');
			}

		}
	}
endif;


/**
 * Layout metabox
 * 
 * Callback function to create layout metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_layout_metabox' ) ) :
	function vlog_layout_metabox( $object, $box ) {
		
		wp_nonce_field( 'vlog_post_metabox_save', 'vlog_post_metabox_nonce' );

		$vlog_meta = vlog_get_post_meta( $object->ID );
		$layouts = vlog_get_featured_layouts( true, true, array(3,4,5) );
?>
	  	<ul class="vlog-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $vlog_meta['layout'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo $layout['title']; ?></span>
	  			<input type="radio" class="vlog-hidden" name="vlog[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $vlog_meta['layout'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Choose a layout', 'vlog' ); ?></p>

	  <?php
	}
endif;



/**
 * Sidebar metabox
 * 
 * Callback function to create sidebar metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_sidebar_metabox' ) ) :
	function vlog_sidebar_metabox( $object, $box ) {
		
		if($object->post_type == 'post'){
			$vlog_meta = vlog_get_post_meta( $object->ID );
		} else {
			$vlog_meta = vlog_get_page_meta( $object->ID );
		}
		
		$sidebars_lay = vlog_get_sidebar_layouts( true );
		$sidebars = vlog_get_sidebars_list( true );
?>
	  	<ul class="vlog-img-select-wrap">
	  	<?php foreach ( $sidebars_lay as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $vlog_meta['use_sidebar'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo $layout['title']; ?></span>
	  			<input type="radio" class="vlog-hidden" name="vlog[use_sidebar]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $vlog_meta['use_sidebar'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Display sidebar', 'vlog' ); ?></p>

	  <?php if ( !empty( $sidebars ) ): ?>

	  	<p><select name="vlog[sidebar]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $vlog_meta['sidebar'] );?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose standard sidebar to display', 'vlog' ); ?></p>

	  	<p><select name="vlog[sticky_sidebar]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $vlog_meta['sticky_sidebar'] );?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose sticky sidebar to display', 'vlog' ); ?></p>

	  <?php endif; ?>
	  <?php
	}
endif;

?>