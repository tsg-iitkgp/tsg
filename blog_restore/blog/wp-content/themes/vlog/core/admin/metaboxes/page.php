<?php 

/**
 * Load page metaboxes
 * 
 * Callback function for page metaboxes load
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_load_page_metaboxes' ) ) :
	function vlog_load_page_metaboxes() {
		

		/* Sidebar metabox */
		add_meta_box(
			'vlog_sidebar',
			esc_html__( 'Sidebar', 'vlog' ),
			'vlog_sidebar_metabox',
			'page',
			'side',
			'default'
		);

		/* Featured area metabox */
		add_meta_box(
			'vlog_fa',
			esc_html__( 'Cover Area', 'vlog' ),
			'vlog_fa_metabox',
			'page',
			'normal',
			'high'
		);

		/* Modules metabox */
		add_meta_box(
			'vlog_modules',
			esc_html__( 'Modules', 'vlog' ),
			'vlog_modules_metabox',
			'page',
			'normal',
			'high'
		);

		/* Pagination metabox */
		add_meta_box(
			'vlog_pagination',
			esc_html__( 'Pagination', 'vlog' ),
			'vlog_pagination_metabox',
			'page',
			'normal',
			'high'
		);

	}
endif;


/**
 * Save page meta
 * 
 * Callback function to save page meta data
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_save_page_metaboxes' ) ) :
	function vlog_save_page_metaboxes( $post_id, $post ) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}
			
		if ( ! isset( $_POST['vlog_page_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['vlog_page_metabox_nonce'], 'vlog_page_metabox_save' ) ) {
   			return;
		}

		if ( $post->post_type == 'page' && isset( $_POST['vlog'] ) ) {
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

			if( isset( $_POST['vlog']['pag'] ) &&  $_POST['vlog']['pag'] != 'none' ){
				$vlog_meta['pag'] = $_POST['vlog']['pag'];
			}


			if( isset( $_POST['vlog']['fa'] ) &&  !empty($_POST['vlog']['fa']) ){
				
				foreach( $_POST['vlog']['fa'] as $key => $value ){
					
					$vlog_meta['fa'][$key] = $value; 					
				}

				if ( isset( $_POST['vlog']['fa']['manual'] ) && !empty( $_POST['vlog']['fa']['manual'] ) ) {
							$vlog_meta['fa']['manual'] = array_map( 'absint', explode( ",", $_POST['vlog']['fa']['manual'] ) );
				}

					if ( isset(  $_POST['vlog']['fa']['tag'] ) && !empty(  $_POST['vlog']['fa']['tag'] ) ) {
							$vlog_meta['fa']['tag'] = vlog_get_tax_term_slug_by_name( $_POST['vlog']['fa'], 'post_tag');
				}
			}

			if ( isset( $_POST['vlog']['sections'] ) ) {
				$vlog_meta['sections'] = array_values( $_POST['vlog']['sections'] );
				foreach($vlog_meta['sections'] as $i => $section ){
					if(!empty($section['modules'])){
						
						foreach( $section['modules'] as $j => $module ){
							if ( isset( $module['manual'] ) && !empty( $module['manual'] ) ) {
								$section['modules'][$j]['manual'] = array_map( 'absint', explode( ",", $module['manual'] ) );
							}

							if ( isset( $module['tag'] ) && !empty( $module['tag'] ) ) {
								$section['modules'][$j]['tag'] = vlog_get_tax_term_slug_by_name( $module['tag'], 'post_tag');
							}

						}

						$vlog_meta['sections'][$i]['modules'] = array_values($section['modules']);
					}
				}
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
 * Module generator metabox
 * 
 * Callback function to create modules metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_modules_metabox' ) ) :
	function vlog_modules_metabox( $object, $box ) {

		wp_nonce_field( 'vlog_page_metabox_save', 'vlog_page_metabox_nonce' );

		$meta = vlog_get_page_meta( $object->ID );

		// print_r($meta);
	
		$default = array(
			'modules' => array(),
			'use_sidebar' => 'right',
			'sidebar' => 'vlog_default_sidebar',
			'sticky_sidebar' => 'vlog_default_sticky_sidebar',
			'bg' => '',
			'css_class' => ''
		);

		$module_defaults = vlog_get_module_defaults();

		$options = array(
			'use_sidebar' => vlog_get_sidebar_layouts(),
			'sidebars' => vlog_get_sidebars_list(),
			'module_options' => vlog_get_module_options()
		);

?>
		
		<div id="vlog-sections">
			<?php if(!empty($meta['sections'])) : ?>
				<?php foreach($meta['sections'] as $i => $section) : $section = vlog_parse_args( $section, $default ); ?>
					<?php vlog_generate_section( $section, $options, $i ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		
		<p><a href="javascript:void(0);" class="vlog-add-section button-primary"><?php esc_html_e( 'Create new section', 'vlog' ); ?></a></p>
		
		<div id="vlog-section-clone">
			<?php vlog_generate_section( $default, $options ); ?>
		</div>

		<div id="vlog-module-clone">
			<?php foreach( $module_defaults as $type => $module ): ?>
				<div class="<?php echo esc_attr($type); ?>">
					<?php vlog_generate_module( $module, $options['module_options'][$type]); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div id="vlog-sections-count" data-count="<?php echo count($meta['sections']); ?>"></div>
				  	
	<?php
	}
endif;


/**
 * Generate section
 * 
 * Generate section field inside modules generator
 * 
 * @param   $section Data array for current section
 * @param   $options An array of section options
 * @param   $i id of a current section, if false then create an empty section
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_section' ) ) :
	function vlog_generate_section( $section, $options, $i = false ) {
		extract( $options );
		$name_prefix = ( $i === false ) ? '' :  'vlog[sections]['.$i.']';
		$edit = ( $i === false ) ? '' :  'edit';
		$section_class = ( $i === false ) ? '' :  'vlog-section-'.$i;
		$section_num = ( $i === false ) ? '' : $i ;
		//print_r($section);
		?>
		<div class="vlog-section <?php echo esc_attr($section_class); ?>" data-section="<?php echo esc_attr($section_num); ?>">
			
			<div class="vlog-modules">
				<?php if(!empty( $section['modules'] ) ): ?>
					<?php foreach($section['modules'] as $j => $module ) : $module = vlog_parse_args( $module, vlog_get_module_defaults( $module['type'] ) ); ?>
						<?php vlog_generate_module( $module, $module_options[$module['type']], $i, $j ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			
			<div class="vlog-modules-count" data-count="<?php echo esc_attr(count($section['modules'])); ?>"></div>


			<div class="section-bottom">
				<div class="left">
					<?php $module_data = vlog_get_module_defaults(); ?>
					<?php foreach( $module_data as $mod ) : ?>
						<a href="javascript:void(0);" class="vlog-add-module button-secondary" data-type="<?php echo esc_attr($mod['type']); ?>"><?php echo '+ '.$mod['type_name']. ' ' .esc_html__( 'Module', 'vlog'); ?></a>
					<?php endforeach; ?>
				</div>
				<div class="right">
					<span><?php esc_html_e( 'Sidebar', 'vlog' ); ?> (<span class="vlog-sidebar"><?php echo $section['use_sidebar']; ?></span>)</span>
					<a href="javascript:void(0);" class="vlog-edit-section button-secondary"><?php esc_html_e( 'Edit', 'vlog' ); ?></a>
					<a href="javascript:void(0);" class="vlog-remove-section button-secondary"><?php esc_html_e( 'Remove', 'vlog' ); ?></a>
				</div>
			</div>

			
			<div class="vlog-section-form <?php echo esc_attr($edit); ?>">

				<div class="vlog-opt">
					<div class="vlog-opt-title">
						<?php esc_html_e( 'Display sidebar', 'vlog' ); ?>:
					</div>
				    <div class="vlog-opt-content">
					    <ul class="vlog-img-select-wrap">
					  	<?php foreach ( $use_sidebar as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = vlog_compare( $id, $section['use_sidebar'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
					  			<br/><span><?php echo $layout['title']; ?></span>
					  			<input type="radio" class="vlog-hidden vlog-count-me sec-sidebar" name="<?php echo esc_attr($name_prefix); ?>[use_sidebar]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $section['use_sidebar'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					    <small class="howto"><?php esc_html_e( 'Choose a sidebar layout', 'vlog' ); ?></small>
					</div>
				</div>

			    <div class="vlog-opt">
			    	<div class="vlog-opt-title">
			    		<?php esc_html_e( 'Standard sidebar', 'vlog' ); ?>:
			    	</div>
				    <div class="vlog-opt-content">
					    <select name="<?php echo esc_attr($name_prefix); ?>[sidebar]" class="vlog-count-me vlog-opt-select">
					  	<?php foreach ( $sidebars as $id => $name ): ?>
					  		<option class="vlog-count-me" value="<?php echo esc_attr($id); ?>" <?php selected( $id, $section['sidebar'] );?>><?php echo $name; ?></option>
					  	<?php endforeach; ?>
					  	</select>
				 		<small class="howto"><?php esc_html_e( 'Choose a standard sidebar', 'vlog' ); ?></small>
				 	</div>
				</div>

				<div class="vlog-opt">
				 	<div class="vlog-opt-title">
				 		<?php esc_html_e( 'Sticky sidebar', 'vlog' ); ?>:
				 	</div>
				  	<div class="vlog-opt-content">
					  	<select name="<?php echo esc_attr($name_prefix); ?>[sticky_sidebar]" class="vlog-count-me vlog-opt-select">
					  	<?php foreach ( $sidebars as $id => $name ): ?>
					  		<option class="vlog-count-me" value="<?php echo esc_attr($id); ?>" <?php selected( $id, $section['sticky_sidebar'] );?>><?php echo $name; ?></option>
					  	<?php endforeach; ?>
					  	</select>
					 	<small class="howto"><?php esc_html_e( 'Choose a sticky sidebar', 'vlog' ); ?></small>
					 </div>
				</div>

				<div class="vlog-opt">
				 	<div class="vlog-opt-title">
				 		<?php esc_html_e( 'Background', 'vlog' ); ?>:
				 	</div>
				  	<div class="vlog-opt-content">
					  	<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[bg]" class="vlog-count-me" value=""  <?php checked( '', $section['bg'] );?> > <?php esc_html_e( 'Transparent', 'vlog' ); ?> </label> <br/>
					  	<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[bg]" class="vlog-count-me" value="vlog-bg" <?php checked( 'vlog-bg', $section['bg'] );?> > <?php esc_html_e( 'Shaded color', 'vlog' ); ?> </label><br/>
					 	<small class="howto"><?php esc_html_e( 'Choose section background type', 'vlog' ); ?></small>
					</div>
				</div>

				<div class="vlog-opt">
				 	<div class="vlog-opt-title">
				 		<?php esc_html_e( 'Custom CSS class', 'vlog' ); ?>:
				 	</div>
				  	<div class="vlog-opt-content">
					  	<input type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" class="vlog-count-me" value="<?php echo esc_attr(esc_html($section['css_class'])); ?>"> 
						<small class="howto"><?php esc_html_e( 'Optionally, specify a class name for a possibility to apply custom styling to this section using CSS (i.e. my-custom-section)', 'vlog' ); ?></small>

					</div>
				</div>

			</div>

		</div>
		<?php
	}
endif;


/**
 * Generate module field
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $i id of a current section
 * @param   $j id of a current module
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_module' ) ) :
	function vlog_generate_module( $module, $options, $i = false, $j = false ) {
		
		$name_prefix = ( $i === false ) ? '' :  'vlog[sections]['.$i.'][modules]['.$j.']';
		$edit = ( $j === false ) ? '' :  'edit';
		$module_class = ( $j === false ) ? '' :  'vlog-module-'.$j;
		$module_num = ( $j === false ) ? '' : $j;
?>
		<div class="vlog-module <?php echo esc_attr($module_class); ?>" data-module="<?php echo esc_attr($module_num); ?>">
			
			<div class="left">
				<span class="vlog-module-type">
					<?php echo ($module['type_name']); ?>
					<?php if(isset($module['columns']) && $module['type'] != 'woocommerce'){
							$columns = vlog_get_module_columns();
							echo '(<span class="vlog-module-columns">'.$columns[$module['columns']]['title'].'</span>)';
						}
					?>
				</span>
				<span class="vlog-module-title"><?php echo $module['title']; ?></span>
			</div>

			<div class="right">
				<a href="javascript:void(0);" class="vlog-edit-module"><?php esc_html_e( 'Edit', 'vlog' ); ?></a> | 
				<a href="javascript:void(0);" class="vlog-remove-module"><?php esc_html_e( 'Remove', 'vlog' ); ?></a>
			</div>

			<div class="vlog-module-form <?php echo esc_attr($edit); ?>">
				
				<input class="vlog-count-me" type="hidden" name="<?php echo esc_attr($name_prefix); ?>[type]" value="<?php echo esc_attr($module['type']); ?>"/>
				<?php call_user_func( 'vlog_generate_module_'.$module['type'], $module, $options, $name_prefix ); ?>

		   	</div>

		</div>
		
	<?php
	}
endif;


/**
 * Generate posts module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_module_posts' ) ) :
function vlog_generate_module_posts( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="vlog-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Actions', 'vlog' ); ?></a>
	</div>

	<div class="vlog-tab first">

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Title', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="vlog-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'vlog' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'vlog' ); ?></small>

			</div>
		</div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Width', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap vlog-col-dep-control">
			  	<?php foreach ( $columns as $id => $column ): ?>
			  		<li>
			  			<?php $selected_class = vlog_compare( $id, $module['columns'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($column['img']); ?>" title="<?php echo esc_attr($column['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($column['title']); ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me mod-columns" name="<?php echo esc_attr($name_prefix); ?>[columns]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['columns'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose module width', 'vlog' ); ?></small>
		    </div>
	    </div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Layout', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap vlog-col-dep">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<?php $disabled_class = ( $module['columns'] % $layout['col'] ) ? 'vlog-disabled' : ''; ?>
			  		<li class="<?php echo esc_attr($disabled_class); ?>">
			  			<?php $selected_class = vlog_compare( $id, $module['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>" data-col="<?php echo esc_attr($layout['col']); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose your main posts layout', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Number of posts', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($module['limit']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Max number of posts to display', 'vlog' ); ?></small>
			</div>
		</div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Starter Layout', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap vlog-col-dep">
			  	<?php foreach ( $starter_layouts as $id => $layout ): ?>
			  		<?php $disabled_class = $layout['col'] && $module['columns'] % $layout['col']  ? 'vlog-disabled' : ''; ?>
			  		<li class="<?php echo esc_attr($disabled_class); ?>">
			  			<?php $selected_class = vlog_compare( $id, $module['starter_layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>" data-col="<?php echo esc_attr($layout['col']); ?>">
			  			<br/><span><?php echo $layout['title']; ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[starter_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['starter_layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose your starter posts layout', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Number of starter posts', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[starter_limit]" value="<?php echo esc_attr($module['starter_limit']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Number of posts to display in starter layout', 'vlog' ); ?></small>
			</div>
		</div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr(esc_html($module['css_class']));?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'vlog' ); ?></small>
			</div>
		</div>

	</div>

	<div class="vlog-tab">
		
		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Order by', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['order'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
					<br/><?php esc_html_e( 'Or choose manually', 'vlog' ); ?>:<br/>
		   		<?php $manual = !empty( $module['manual'] ) ? implode( ",", $module['manual'] ) : ''; ?>
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" class="vlog-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify post ids separated by comma if you want to select only those posts. i.e. 213,32,12,45', 'vlog' ); ?></small>
		   	</div>
	    </div>

	     <div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Sort', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $module['sort'], 'DESC' ); ?> class="vlog-count-me" /><?php esc_html_e('Descending', 'vlog') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $module['sort'], 'ASC' ); ?> class="vlog-count-me" /><?php esc_html_e('Ascending', 'vlog') ?></label><br/>
		   	</div>
	    </div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'In category', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<div class="vlog-fit-height">
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $module['cat'] ) ? 'checked="checked"' : ''; ?>
		   			<label><input class="vlog-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr($checked); ?> /><?php echo $cat->name;?></label><br/>
		   		<?php endforeach; ?>
		   		</div>
		   		<small class="howto"><?php esc_html_e( 'Check whether you want to display posts from specific categories only', 'vlog' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Tagged with', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tag]" value="<?php echo esc_attr(vlog_get_tax_term_name_by_slug($module['tag'])); ?>" class="vlog-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'vlog' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Format', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['format'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'vlog' ); ?></small>
	   		</div>
	   	</div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Not older than', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['time'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'vlog' ); ?></small>
	   		</div>
	   	</div>

	   	<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $module['unique'], 1 ); ?> class="vlog-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, posts in this module will be excluded from other modules below.', 'vlog' ); ?></small>
		   	</div>
	    </div>

	</div>

	<div class="vlog-tab">

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Slider options', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider]" value="1" <?php checked( $module['slider'], 1 ); ?> class="vlog-count-me" /> <?php esc_html_e( 'Display module as slider', 'vlog' ); ?></label> <br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay]" value="1" <?php checked( $module['slider_autoplay'], 1 ); ?> class="vlog-count-me" /></label> 
		   		<?php esc_html_e( 'Autoplay (rotate) slider every', 'vlog' ); ?> <input type="number" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay_time]" value="<?php echo esc_attr(absint( $module['slider_autoplay_time'] )); ?>"  class="small-text vlog-count-me" /> <?php esc_html_e( 'seconds', 'vlog' ); ?>
		   		<small class="howto"><?php esc_html_e( 'Note: if slider is apllied to a module, "starter" layout will be ignored', 'vlog' ); ?></small>
		   	</div>
	    </div>


	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display "view all" link', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><?php esc_html_e( 'Text', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="vlog-count-me" />
		   		<br/>
		   		<label><?php esc_html_e( 'URL', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="vlog-count-me" /><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify text and URL if you want to display "view all" button in this module', 'vlog' ); ?></small>
		   	</div>
	    </div>

	</div>
<?php }
endif;


/**
 * Generate category module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_module_cats' ) ) :
function vlog_generate_module_cats( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="vlog-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Actions', 'vlog' ); ?></a>
	</div>

	<div class="vlog-tab first">

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Title', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="vlog-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'vlog' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'vlog' ); ?></small>

			</div>
		</div>


		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Layout', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap vlog-col-dep">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = vlog_compare( $id, $module['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose a layout', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display play icon', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[display_icon]" value="0" class="vlog-count-me" />
		   		<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[display_icon]" value="1" <?php checked( $module['display_icon'], 1 ); ?> class="vlog-count-me" />
		   	</div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display posts count', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="0" class="vlog-count-me" />
		   		<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="1" <?php checked( $module['display_count'], 1 ); ?> class="vlog-count-me vlog-next-hide" />
		   	</div>
	    </div>


	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Count label', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[count_label]" value="<?php echo esc_attr($module['count_label']);?>" class="vlog-count-me" />
		   	</div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr(esc_html($module['css_class']));?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'vlog' ); ?></small>
			</div>
		</div>		

	</div>

	<div class="vlog-tab">
		
		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Categories', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content sortable">
				<?php $cats = vlog_sort_option_items( $cats,  $module['cat']); ?>
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $module['cat'] ) ? 'checked="checked"' : ''; ?>
		   			<label><input class="vlog-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr($checked); ?> /><?php echo $cat->name;?></label>
		   		<?php endforeach; ?>
		   	</div>
	   	</div>

	</div>

	<div class="vlog-tab">

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Slider options', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider]" value="1" <?php checked( $module['slider'], 1 ); ?> class="vlog-count-me" /> <?php esc_html_e( 'Display module as slider', 'vlog' ); ?></label> <br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay]" value="1" <?php checked( $module['slider_autoplay'], 1 ); ?> class="vlog-count-me" /></label> 
		   		<?php esc_html_e( 'Autoplay (rotate) slider every', 'vlog' ); ?> <input type="number" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay_time]" value="<?php echo esc_attr(absint( $module['slider_autoplay_time'] )); ?>"  class="small-text vlog-count-me" /> <?php esc_html_e( 'seconds', 'vlog' ); ?>
		   		<small class="howto"><?php esc_html_e( 'Note: if slider is apllied to a module, "starter" layout will be ignored', 'vlog' ); ?></small>
		   	</div>
	    </div>


	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display "view all" link', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><?php esc_html_e( 'Text', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="vlog-count-me" />
		   		<br/>
		   		<label><?php esc_html_e( 'URL', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="vlog-count-me" /><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify text and URL if you want to display "view all" button in this module', 'vlog' ); ?></small>
		   	</div>
	    </div>

	</div>
<?php }
endif;

/**
 * Generate series module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_module_series' ) ) :
function vlog_generate_module_series( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="vlog-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'vlog' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Actions', 'vlog' ); ?></a>
	</div>

	<div class="vlog-tab first">

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Title', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="vlog-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'vlog' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'vlog' ); ?></small>

			</div>
		</div>


		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Layout', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap vlog-col-dep">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = vlog_compare( $id, $module['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose a layout', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display play icon', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[display_icon]" value="0" class="vlog-count-me" />
		   		<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[display_icon]" value="1" <?php checked( $module['display_icon'], 1 ); ?> class="vlog-count-me" />
		   	</div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display posts count', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="0" class="vlog-count-me" />
		   		<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="1" <?php checked( $module['display_count'], 1 ); ?> class="vlog-count-me vlog-next-hide" />
		   	</div>
	    </div>


	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Count label', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[count_label]" value="<?php echo esc_attr($module['count_label']);?>" class="vlog-count-me" />
		   	</div>
	    </div>

	     <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr(esc_html($module['css_class']));?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'vlog' ); ?></small>
			</div>
		</div>	

	</div>

	<div class="vlog-tab">
		
		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Series (palylists)', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content sortable">
				<?php $series = vlog_sort_option_items( $series,  $module['series']); ?>
		   		<?php foreach ( $series as $serie ) : ?>
		   			<?php $checked = in_array( $serie->term_id, $module['series'] ) ? 'checked="checked"' : ''; ?>
		   			<label><input class="vlog-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[series][]" value="<?php echo esc_attr($serie->term_id); ?>" <?php echo esc_attr($checked); ?> /><?php echo $serie->name;?></label>
		   		<?php endforeach; ?>
		   	</div>
	   	</div>

	</div>

	<div class="vlog-tab">

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Slider options', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider]" value="1" <?php checked( $module['slider'], 1 ); ?> class="vlog-count-me" /> <?php esc_html_e( 'Display module as slider', 'vlog' ); ?></label> <br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay]" value="1" <?php checked( $module['slider_autoplay'], 1 ); ?> class="vlog-count-me" /></label> 
		   		<?php esc_html_e( 'Autoplay (rotate) slider every', 'vlog' ); ?> <input type="number" name="<?php echo esc_attr($name_prefix); ?>[slider_autoplay_time]" value="<?php echo esc_attr(absint( $module['slider_autoplay_time'] )); ?>"  class="small-text vlog-count-me" /> <?php esc_html_e( 'seconds', 'vlog' ); ?>
		   		<small class="howto"><?php esc_html_e( 'Note: if slider is apllied to a module, "starter" layout will be ignored', 'vlog' ); ?></small>
		   	</div>
	    </div>


	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Display "view all" link', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><?php esc_html_e( 'Text', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="vlog-count-me" />
		   		<br/>
		   		<label><?php esc_html_e( 'URL', 'vlog' ); ?></label>: <input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="vlog-count-me" /><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify text and URL if you want to display "view all" button in this module', 'vlog' ); ?></small>
		   	</div>
	    </div>

	</div>
<?php }
endif;


/**
 * Generate text module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_module_text' ) ) :
	function vlog_generate_module_text( $module, $options, $name_prefix ){
		
		extract( $options ); ?>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Title', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="vlog-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'vlog' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'vlog' ); ?></small>				
			</div>
		</div>

		<div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Width', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap">
			  	<?php foreach ( $columns as $id => $column ): ?>
			  		<li>
			  			<?php $selected_class = vlog_compare( $id, $module['columns'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($column['img']); ?>" title="<?php echo esc_attr($column['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo $column['title']; ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me mod-columns" name="<?php echo esc_attr($name_prefix); ?>[columns]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['columns'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose module width', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Content', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<textarea class="vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[content]"><?php echo $module['content']; ?></textarea>
				<small class="howto"><?php esc_html_e( 'Paste any text, HTML, script or shortcodes here', 'vlog' ); ?></small>

				<label>
					<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autop]" value="1" <?php checked( $module['autop'], 1 ); ?> class="vlog-count-me" />
					<?php esc_html_e( 'Automatically add paragraphs', 'vlog' ); ?>
				</label>
			</div>
		</div>

		 <div class="vlog-opt">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr(esc_html($module['css_class']));?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'vlog' ); ?></small>
			</div>
		</div>	

	<?php }
endif;

/**
 * Featured area metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_fa_metabox' ) ) :
function vlog_fa_metabox( $object, $box ){
	
	$meta = vlog_get_page_meta( $object->ID, 'fa' );

	$layouts = vlog_get_featured_layouts( false, true );
	$order = vlog_get_post_order_opts();
	$cats = get_categories( array( 'hide_empty' => false, 'number' => 0 ) );
	$time = vlog_get_time_diff_opts();
	$formats = vlog_get_post_format_opts();

	$name_prefix = 'vlog[fa]';

	?>

	<div class="vlog-opt-box">

		<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Layout', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
			    <ul class="vlog-img-select-wrap">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = vlog_compare( $id, $meta['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="vlog-hidden vlog-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose your cover area layout', 'vlog' ); ?></small>
		    </div>
	    </div>

	    <div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Number of posts', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<input class="vlog-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($meta['limit']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Max number of posts to display', 'vlog' ); ?></small>
			</div>
		</div>

		<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Order by', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['order'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
					<br/><?php esc_html_e( 'Or choose manually', 'vlog' ); ?>:<br/>
		   		<?php $manual = !empty( $meta['manual'] ) ? implode( ",", $meta['manual'] ) : ''; ?>
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" class="vlog-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify post ids separated by comma if you want to select only those posts. i.e. 213,32,12,45', 'vlog' ); ?></small>
		   	</div>
	    </div>

	    <div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Sort', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $meta['sort'], 'DESC' ); ?> class="vlog-count-me" /><?php esc_html_e('Descending', 'vlog') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $meta['sort'], 'ASC' ); ?> class="vlog-count-me" /><?php esc_html_e('Ascending', 'vlog') ?></label><br/>
		   	</div>
	    </div>

	    <div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $meta['unique'], 1 ); ?> class="vlog-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, selected posts will be excluded from modules.', 'vlog' ); ?></small>
		   	</div>
	    </div>

	</div>

	<div class="vlog-opt-box">

		

		<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'In category', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
				<div class="vlog-fit-height">
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $meta['cat'] ) ? 'checked="checked"' : ''; ?>
		   			<label><input class="vlog-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo $checked; ?> /><?php echo $cat->name;?></label><br/>
		   		<?php endforeach; ?>
		   		</div>
		   		<small class="howto"><?php esc_html_e( 'Check whether you want to display posts from specific categories only', 'vlog' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Tagged with', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tag]" value="<?php echo esc_attr(vlog_get_tax_term_name_by_slug($meta['tag'])); ?>" class="vlog-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'vlog' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Format', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['format'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'vlog' ); ?></small>
	   		</div>
	   	</div>

		<div class="vlog-opt-inline">
			<div class="vlog-opt-title">
				<?php esc_html_e( 'Not older than', 'vlog' ); ?>:
			</div>
			<div class="vlog-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['time'], $id ); ?> class="vlog-count-me" /><?php echo $title;?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'vlog' ); ?></small>
	   		</div>
	   	</div>

	   	

	</div>



<?php }
endif;


/**
 * Pagination metabox
 * 
 * Callback function to create pagination metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_pagination_metabox' ) ) :
	function vlog_pagination_metabox( $object, $box ) {
		
		$meta = vlog_get_page_meta( $object->ID );
		$layouts = vlog_get_pagination_layouts( false, true );
?>
	  	<ul class="vlog-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $meta['pag'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="vlog-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo $layout['title']; ?></span>
	  			<input type="radio" class="vlog-hidden" name="vlog[pag]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['pag'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Note: Pagination will be applied to the last post module on the page', 'vlog' ); ?></p>

	  <?php
	}
endif;

?>