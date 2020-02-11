<?php 

/**
 * Save series meta 
 * 
 * Callback function to save series meta data
 * 
 * @since  1.0
 */

add_action( 'edited_series', 'vlog_save_series_meta_fields', 10, 2 );
add_action( 'create_series', 'vlog_save_series_meta_fields', 10, 2 );

if ( !function_exists( 'vlog_save_series_meta_fields' ) ) :
	function vlog_save_series_meta_fields( $term_id ) {

		if ( isset( $_POST['vlog'] ) ) {

			$meta = array();

			if( isset( $_POST['vlog']['layout'] ) ) { 
				if( $_POST['vlog']['layout']['type'] != 'inherit' ){
					$meta['layout'] = $_POST['vlog']['layout'];
				}
			}

			if( isset( $_POST['vlog']['sidebar'] ) ) { 
				if( $_POST['vlog']['sidebar']['type'] != 'inherit' ){
					$meta['sidebar'] = $_POST['vlog']['sidebar'];
				}
			}

			if( !empty( $meta) ){
				update_term_meta( $term_id, '_vlog_meta', $meta);
			} else {
				delete_term_meta( $term_id, '_vlog_meta');
			}
			
		}

	}
endif;
	

/**
 * Add series meta 
 * 
 * Callback function to load series meta fields on "new series" screen
 * 
 * @since  1.0
 */

add_action( 'series_add_form_fields', 'vlog_series_add_meta_fields', 10, 2 );

if( !function_exists('vlog_series_add_meta_fields') )	: 
	function vlog_series_add_meta_fields() {
		$meta = vlog_get_series_meta();
		$cover = vlog_get_featured_layouts( false, true );
		$main = vlog_get_main_layouts();
		$starter = vlog_get_main_layouts( false, true );
		$sidebar = vlog_get_sidebar_layouts();
		$pagination = vlog_get_pagination_layouts( );
		$all_sidebars = vlog_get_sidebars_list();
		?>
		
		<div class="form-field">
			<label><?php esc_html_e( 'Layout options', 'vlog' ); ?></label>
			<label><input type="radio" name="vlog[layout][type]" value="inherit" class="layout-type" <?php checked( $meta['layout']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global series options', 'vlog' ); ?></label>
			<label><input type="radio" name="vlog[layout][type]" value="custom" class="layout-type" <?php checked( $meta['layout']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom layout', 'vlog' ); ?></label>	
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Display posts in ascending order', 'vlog' ); ?></label>
			<label for="vlog[layout][serie_order_asc]"><input type="radio" name="vlog[layout][serie_order_asc]" value="1" <?php checked( 1 , $meta['layout']['serie_order_asc']); ?>><?php esc_html_e('On', 'vlog'); ?></label>
			<label for="vlog[layout][serie_order_asc]"><input type="radio" name="vlog[layout][serie_order_asc]" value="0" <?php checked( 0 , $meta['layout']['serie_order_asc']); ?>><?php esc_html_e('Off', 'vlog'); ?></label>
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Cover area layout', 'vlog' ); ?></label>
			<ul class="vlog-img-select-wrap">
		  	<?php foreach ( $cover as $id => $layout ): ?>
		  		<li>
		  			<?php $selected_class = vlog_compare( $id, $meta['layout']['cover'] ) ? ' selected': ''; ?>
		  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
		  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
		  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][cover]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['cover'] );?>/>
		  		</li>
		  	<?php endforeach; ?>
		    </ul>
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Number of cover area posts', 'vlog' ); ?></label>
			<input name="vlog[layout][cover_ppp]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['cover_ppp']); ?>" />
		</div>
			
		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Main layout', 'vlog' ); ?></label>
			<ul class="vlog-img-select-wrap">
		  	<?php foreach ( $main as $id => $layout ): ?>
		  		<li>
		  			<?php $selected_class = vlog_compare( $id, $meta['layout']['main'] ) ? ' selected': ''; ?>
		  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
		  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
		  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][main]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['main'] );?>/>
		  		</li>
		  	<?php endforeach; ?>
		    </ul>
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Posts per page', 'vlog' ); ?></label>
			<input name="vlog[layout][ppp]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['ppp']); ?>" />
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Starter layout', 'vlog' ); ?></label>
			<ul class="vlog-img-select-wrap">
		  	<?php foreach ( $starter as $id => $layout ): ?>
		  		<li>
		  			<?php $selected_class = vlog_compare( $id, $meta['layout']['starter'] ) ? ' selected': ''; ?>
		  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
		  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
		  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][starter]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['starter'] );?>/>
		  		</li>
		  	<?php endforeach; ?>
		    </ul>
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Number of starter posts', 'vlog' ); ?></label>
			<input name="vlog[layout][starter_limit]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['starter_limit']); ?>" />
		</div>

		<div class="form-field vlog-layout-opt">
			<label><?php esc_html_e( 'Pagination', 'vlog' ); ?></label>
			<ul class="vlog-img-select-wrap">
		  	<?php foreach ( $pagination as $id => $layout ): ?>
		  		<li>
		  			<?php $selected_class = vlog_compare( $id, $meta['layout']['pagination'] ) ? ' selected': ''; ?>
		  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
		  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
		  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['pagination'] );?>/>
		  		</li>
		  	<?php endforeach; ?>
		    </ul>
		</div>

		<div class="form-field"> 
			<label><?php esc_html_e( 'Sidebar options', 'vlog' ); ?></label>
							
					<label>
						<input type="radio" name="vlog[sidebar][type]" value="inherit" class="layout-sidebar" <?php checked( $meta['sidebar']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global series sibebar options', 'vlog' ); ?>
					</label>
					<label>
						<input type="radio" name="vlog[sidebar][type]" value="custom" class="layout-sidebar" <?php checked( $meta['sidebar']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom series sidebar options', 'vlog' ); ?>
					</label>	
			
		</div>
		
		<div class="form-field vlog-sidebar-opt"> 
			<label><?php esc_html_e( 'Display sidebar', 'vlog' ); ?></label>

					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $sidebar as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['sidebar']['use_sidebar'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[sidebar][use_sidebar]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['sidebar']['use_sidebar'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>

		</div>

		<div class="form-field vlog-sidebar-opt"> 
			<label><?php esc_html_e( 'Series standard sidebar', 'vlog' ); ?></label>
				
				<select name="vlog[sidebar][standard_sidebar]" >
					<?php foreach ($all_sidebars as $key => $name) : ?>
						<option value="<?php echo esc_attr($key) ?>" <?php selected( $key, $meta['sidebar']['standard_sidebar'] ); ?>><?php esc_html_e($name, 'vlog'); ?></option>
					<?php endforeach ?>
				</select>	

		</div>

		<div class="form-field vlog-sidebar-opt"> 
			<label><?php esc_html_e( 'Series sticky sidebar', 'vlog' ); ?></label>
				<select name="vlog[sidebar][sticky_sidebar]" >
					<?php foreach ($all_sidebars as $key => $name) : ?>
						<option value="<?php echo esc_attr($key) ?>" <?php selected( $key, $meta['sidebar']['sticky_sidebar'] ); ?>><?php esc_html_e($name, 'vlog'); ?></option>
					<?php endforeach ?>
				</select>
		</div>

	<?php }
 endif; 


/**
 * Edit series meta 
 * 
 * Callback function to load series meta fields on edit screen
 * 
 * @since  1.0
 */

add_action( 'series_edit_form_fields', 'vlog_series_edit_meta_fields', 10, 2 );

if ( !function_exists( 'vlog_series_edit_meta_fields' ) ) :
	function vlog_series_edit_meta_fields( $term ) {	
		$meta = vlog_get_series_meta( $term->term_id );
		$cover = vlog_get_featured_layouts( false, true );
		$main = vlog_get_main_layouts();
		$starter = vlog_get_main_layouts( false, true );
		$sidebar = vlog_get_sidebar_layouts();
		$pagination = vlog_get_pagination_layouts( );						
		?>		

		<tr class="form-field"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Layout options', 'vlog' ); ?></label></th>
				<td>			
					<label><input type="radio" name="vlog[layout][type]" value="inherit" class="layout-type" <?php checked( $meta['layout']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global series options', 'vlog' ); ?></label><br>
					<label><input type="radio" name="vlog[layout][type]" value="custom" class="layout-type" <?php checked( $meta['layout']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom layout', 'vlog' ); ?></label>	
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Display posts in ascending order', 'vlog' ); ?></label></th>
			<td>				
				<label for="vlog[layout][serie_order_asc]"><input type="radio" name="vlog[layout][serie_order_asc]" value="1" <?php checked( 1 , $meta['layout']['serie_order_asc']); ?>><?php esc_html_e('On', 'vlog'); ?></label><br>
				<label for="vlog[layout][serie_order_asc]"><input type="radio" name="vlog[layout][serie_order_asc]" value="0" <?php checked( 0 , $meta['layout']['serie_order_asc']); ?>><?php esc_html_e('Off', 'vlog'); ?></label>
			</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Cover area layout', 'vlog' ); ?></label></th>
				<td>
					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $cover as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['layout']['cover'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][cover]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['cover'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Number of cover area posts', 'vlog' ); ?></label></th>
				<td>
					<input name="vlog[layout][cover_ppp]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['cover_ppp']); ?>" />
				</td>	
		</tr>
			
		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Main layout', 'vlog' ); ?></label></th>
				<td>
					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $main as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['layout']['main'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][main]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['main'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Posts per page', 'vlog' ); ?></label></th>
				<td>
					<input name="vlog[layout][ppp]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['ppp']); ?>" />
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Starter layout', 'vlog' ); ?></label></th>
				<td>
					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $starter as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['layout']['starter'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][starter]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['starter'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Number of starter posts', 'vlog' ); ?></label></th>
				<td>
					<input name="vlog[layout][starter_limit]" type="text" class="vlog-small-text" value="<?php echo esc_attr($meta['layout']['starter_limit']); ?>" />
				</td>
		</tr>

		<tr class="form-field vlog-layout-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Pagination layout', 'vlog' ); ?></label></th>
				<td>
					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $pagination as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['layout']['pagination'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[layout][pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['pagination'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>
				</td>
		</tr>
		
		<tr class="form-field"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Sidebar options', 'vlog' ); ?></label></th>
				<td>			
					<label>
						<input type="radio" name="vlog[sidebar][type]" value="inherit" class="layout-sidebar" <?php checked( $meta['sidebar']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global series sibebar options', 'vlog' ); ?>
					</label><br>
					<label>
						<input type="radio" name="vlog[sidebar][type]" value="custom" class="layout-sidebar" <?php checked( $meta['sidebar']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom series sidebar options', 'vlog' ); ?>
					</label>	
				</td>
		</tr>
		
		<tr class="form-field vlog-sidebar-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Display sidebar', 'vlog' ); ?></label></th>
				<td>
					<ul class="vlog-img-select-wrap">
				  	<?php foreach ( $sidebar as $id => $layout ): ?>
				  		<li>
				  			<?php $selected_class = vlog_compare( $id, $meta['sidebar']['use_sidebar'] ) ? ' selected': ''; ?>
				  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr( $selected_class ); ?>">
				  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
				  			<input type="radio" class="vlog-hidden vlog-count-me" name="vlog[sidebar][use_sidebar]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['sidebar']['use_sidebar'] );?>/>
				  		</li>
				  	<?php endforeach; ?>
				    </ul>
				</td>
		</tr>

		<tr class="form-field vlog-sidebar-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Series standard sidebar', 'vlog' ); ?></label></th>
				<td>
					<?php $all_sidebars = vlog_get_sidebars_list(); ?>
					<select name="vlog[sidebar][standard_sidebar]" >
						<?php foreach ($all_sidebars as $key => $name) : ?>
							<option value="<?php echo esc_attr($key) ?>" <?php selected( $key, $meta['sidebar']['standard_sidebar'] ); ?>><?php esc_html_e($name, 'vlog'); ?></option>
						<?php endforeach ?>
					</select>	
				</td>
		</tr>

		<tr class="form-field vlog-sidebar-opt"> 
			<th scope="row" valign="top"><label><?php esc_html_e( 'Series sticky sidebar', 'vlog' ); ?></label></th>
				<td>
					<?php $all_sidebars = vlog_get_sidebars_list(); ?>
					<select name="vlog[sidebar][sticky_sidebar]" >
						<?php foreach ($all_sidebars as $key => $name) : ?>
							<option value="<?php echo esc_attr($key) ?>" <?php selected( $key, $meta['sidebar']['sticky_sidebar'] ); ?>><?php esc_html_e($name, 'vlog'); ?></option>
						<?php endforeach ?>
					</select>	
				</td>
		</tr>

	<?php }
endif;

?>