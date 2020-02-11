<?php

/**
 * Get module defaults
 *
 * @param  string $type Module type
 * @return array Default arguments of a module
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_module_defaults' ) ):
	function vlog_get_module_defaults( $type = false ) {

		$defaults = array(
			'posts' => array(
				'type' => 'posts',
				'type_name' => esc_html__( 'Posts', 'vlog'),
				'title' => '',
				'hide_title' => 0,
				'columns' => 12,
				'layout' => 'b',
				'limit' => 10,
				'starter_layout' => 'none',
				'starter_limit' => 1,
				'css_class' => '',
				'cat' => array(),
				'tag' => array(),
				'manual' => array(),
				'time' => 0,
				'order' => 'date',
				'format' => 0,
				'unique' => 0,
				'slider' => 0,
				'slider_autoplay' => 0,
				'slider_autoplay_time' => 5,
				'more_text' => '',
				'more_url' => '',
				'sort'	=> 'DESC' 
			),

			'cats' => array(
				'type' => 'cats',
				'type_name' => esc_html__( 'Categories', 'vlog'),
				'title' => '',
				'hide_title' => 0,
				'layout' => 'e',
				'cat' => array(),
				'display_count' => 1,
				'display_icon'	=> 0,
				'count_label'	=> esc_html__( 'videos', 'vlog'),
				'css_class' => '',
				'slider' => 0,
				'slider_autoplay' => 0,
				'slider_autoplay_time' => 5,
				'more_text' => '',
				'more_url' => '',
			),

			'text' => array(
				'type' => 'text',
				'type_name' => esc_html__( 'Text', 'vlog'),
				'title' => '',
				'hide_title' => 0,
				'columns' => 12,
				'content' => '',
				'autop' => 0,
				'css_class' => ''
			)
		);

		if( vlog_is_series_active() ){
				
				$defaults['series'] = array(
					'type' => 'series',
					'type_name' => esc_html__( 'Series', 'vlog'),
					'title' => '',
					'hide_title' => 0,
					'layout' => 'e',
					'series' => array(),
					'display_count' => 1,
					'display_icon'	=> 1,
					'count_label'	=> esc_html__( 'videos', 'vlog'),
					'css_class' => '',
					'slider' => 0,
					'slider_autoplay' => 0,
					'slider_autoplay_time' => 5,
					'more_text' => '',
					'more_url' => '', 
				);
		}


		if( !empty( $type ) && array_key_exists( $type, $defaults ) ){
			return $defaults[$type];
		}

		return $defaults;
		
	}
endif;

/**
 * Get module options
 *
 * @param  string $type Module type
 * @return array Options for sepcific module
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_module_options' ) ):
	function vlog_get_module_options( $type = false ) {

		$options = array(
			'posts' => array(
				'layouts' => vlog_get_main_layouts(),
				'starter_layouts' => vlog_get_main_layouts( false, true ),
				'columns' => vlog_get_module_columns(),
				'cats' => get_categories( array( 'hide_empty' => false, 'number' => 0 ) ),
				'time' => vlog_get_time_diff_opts(),
				'order' => vlog_get_post_order_opts(),
				'formats' => vlog_get_post_format_opts(),
			),

			'cats' => array(
				'layouts' => vlog_get_cat_layouts(),
				'cats' => get_categories( array( 'hide_empty' => false, 'number' => 0 ) )
			),

			

			'text' => array(
				'columns' => vlog_get_module_columns(),
			)
		);

		if( vlog_is_series_active() ){
			$options['series'] = array(
				'layouts' => vlog_get_cat_layouts(),
				'series' => get_terms( array('taxonomy' => 'series', 'hide_empty' => false, 'number' => 0 ) )
			);
		}


		if( !empty( $type ) && array_key_exists( $type, $options ) ){
			return $options[$type];
		}

		return $options;
		
	}
endif;

				

/**
 * Get module layout
 *
 * Functions gets current post layout for specific module
 *
 * @param array   $module Module data
 * @param int     $i      index of current post
 * @return string id of current layout
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_module_layout' ) ):
	function vlog_get_module_layout( $module, $i ) {

		if( vlog_module_is_slider( $module ) ){
			
			return $module['layout'];

		} else if ( isset($module['starter_layout']) && $module['starter_layout'] != 'none' &&  $i < absint( $module['starter_limit'] ) ) {
			
			return $module['starter_layout'];
		}

		return $module['layout'];
	}
endif;

/**
 * Is module slider
 *
 * Check if slider is applied to module
 *
 * @param array   $module Module data
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_is_slider' ) ):
	function vlog_module_is_slider( $module ) {

		if ( isset($module['slider']) && !empty( $module['slider'] ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Is module combined
 *
 * Check if module has starter posts
 *
 * @param array   $module Module data
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_is_combined' ) ):
	function vlog_module_is_combined( $module ) {

		if ( isset($module['starter_layout']) && $module['starter_layout'] != 'none' && !empty( $module['starter_limit']) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Is module paginated
 *
 * Check if current module has a pagination
 * 
 * @param   $i current section index
 * @param   $j current module index
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_is_paginated' ) ):
	function vlog_module_is_paginated( $i, $j ) {
		global $vlog_module_pag_index;
		
		if(!empty($vlog_module_pag_index) && $vlog_module_pag_index['s_ind'] == $i && $vlog_module_pag_index['m_ind'] == $j ){
			return true;
		}

		return false;
	}
endif;

/**
 * Set paginated module index
 *
 * Get last posts module index so we know to which module we should apply pagination
 * and set indexes to $vlog_module_pag_index global var
 *
 * @param array   $sections Sections data array
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_set_paginated_module_index' ) ):
	function vlog_set_paginated_module_index( $sections, $paged = false ) {
		
		global $vlog_module_pag_index;

		//If we are on paginated modules page it shows only one section and module so index is set to "0"
		if( $paged ){

			$vlog_module_pag_index = array( 's_ind' => 0, 'm_ind' => 0 );
		
		} else {

			$last_section_index = false;
			$last_module_index = false;
			foreach( $sections as $m => $section ){
				if(!empty($section['modules'])){
					foreach( $section['modules'] as $n => $module ){
						if($module['type'] == 'posts'){
							$last_section_index = $m;
							$last_module_index = $n;
						}
					}
				}
			}

			if( $last_section_index !== false && $last_module_index !== false ){
				$vlog_module_pag_index = array( 's_ind' => $last_section_index, 'm_ind' => $last_module_index );
			}
		}
	}
endif;

/**
 * Module template is paged
 *
 * Check if we are on paginated modules page
 *
 * @return int|false
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_template_is_paged' ) ):
	function vlog_module_template_is_paged() {
		$curr_page = is_front_page() ? absint( get_query_var('page') ) : absint( get_query_var('paged') );
		return $curr_page > 1 ? $curr_page : false;
	}
endif;


/**
 * Parse paged module template
 *
 * When we are on paginated module page
 * pull only the last posts module and its section 
 * but check queries for other modules in other sections
 *
 * @param  array $sections existing sections data
 * @return array parsed new section data
 * @since  1.0
 */

if ( !function_exists( 'vlog_parse_paged_module_template' ) ):
	function vlog_parse_paged_module_template( $sections ) {

		foreach( $sections as $s_ind => $section ){
			if(!empty($section['modules'])){
				foreach( $section['modules'] as $m_ind => $module ){
					
					$module = vlog_parse_args( $module, vlog_get_module_defaults( $module['type'] ) );

					if($module['type'] == 'posts'){
						
						if( vlog_module_is_paginated( $s_ind, $m_ind ) ) {
						
							$new_sections = array( 0 => $section );
							$module['starter_layout'] = 'none';
							$new_sections[0]['modules'] = array( 0 => $module );
							return $new_sections;
						
						} else {
						
							if( $module['unique'] ){
								vlog_get_module_query( $module );
							}
						}

					}
				}
			}
		}

	}
endif;




/**
 * Get module heading
 *
 * Function gets heading/title html for current module
 *
 * @param array   $module Module data
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_module_heading' ) ):
	function vlog_get_module_heading( $module ) {

		$args = array();

		if ( !empty( $module['title'] ) && empty( $module['hide_title'] ) ) {

			$args['title'] = '<h4>'.$module['title'].'</h4>';
		}

		$args['actions'] = '';

		if ( isset( $module['more_text'] ) && !empty( $module['more_text'] ) && !empty( $module['more_url'] ) ) {
			$args['actions'].= '<a class="vlog-all-link" href="'.esc_url( $module['more_url'] ).'">'.$module['more_text'].'</a>';
		}

		if ( vlog_module_is_slider( $module ) ) {
			$args['actions'].= '<div class="vlog-slider-controls" data-col="'.esc_attr( vlog_layout_columns( $module['layout']) ).'" data-autoplay="'.esc_attr($module['slider_autoplay']).'" data-autoplay-time="'.esc_attr(absint($module['slider_autoplay_time'])).'"></div>';
		}

		return !empty( $args ) ? vlog_module_heading( $args ) : '';

	}
endif;

/**
 * Get module query
 *
 * @param array   $module Module data
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_module_query' ) ):
	function vlog_get_module_query( $module, $paged = false ) {
		
		global $vlog_unique_module_posts;

		$module = wp_parse_args( $module, vlog_get_module_defaults($module['type']) );

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $module['manual'] ) ) {

			$args['posts_per_page'] = absint( count( $module['manual'] ) );
			$args['orderby'] =  'post__in';
			$args['post__in'] =  $module['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {

			$args['post_type'] = 'post';
			$args['posts_per_page'] = absint( $module['limit'] );

			if ( !empty( $module['cat'] ) ) {
				$args['category__in'] = $module['cat'];
			}

			if ( !empty( $module['tag'] ) ) {
				$args['tag_slug__in'] = $module['tag'];
			}

			if ( !empty( $module['format'] ) ) {
				
				if( $module['format'] == 'standard'){
					
					$terms = array();
					$formats = get_theme_support('post-formats');
					if(!empty($formats) && is_array($formats[0])){
						foreach($formats[0] as $format){
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array('post-format-'.$module['format']);
					$operator = 'IN';
				}
				
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => $operator
					)
				);
			}
			

			$args['orderby'] = $module['order'];
			$args['order'] = $module['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {

				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();

			}

			if ( $time_diff = $module['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', vlog_calculate_time_diff( $time_diff ) ) );
			}

			if( !empty( $vlog_unique_module_posts ) ){
				$args['post__not_in'] = $vlog_unique_module_posts;
			}
		}

		if( $paged ){
			$args['paged'] = $paged;
		}

		$query = new WP_Query( $args );

		if ( $module['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$vlog_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;

/**
 * Get featured area query
 *
 * @param array  $fa Featured area settings to parse the query
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_featured_area_query' ) ):
	function vlog_get_featured_area_query( $fa ) {
		
		global $vlog_unique_module_posts;

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $fa['manual'] ) ) {

			$args['orderby'] =  'post__in';
			$args['post__in'] =  $fa['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {

			$args['post_type'] = 'post';
			$args['posts_per_page'] = absint( $fa['limit'] ) ;

			if ( !empty( $fa['cat'] ) ) {
				$args['category__in'] = $fa['cat'];
			}

			if ( !empty( $fa['tag'] ) ) {
				$args['tag_slug__in'] = $fa['tag'];
			}

			if ( !empty( $fa['format'] ) ) {
				
				if( $fa['format'] == 'standard'){
					
					$terms = array();
					$formats = get_theme_support('post-formats');
					if(!empty($formats) && is_array($formats[0])){
						foreach($formats[0] as $format){
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array('post-format-'.$fa['format']);
					$operator = 'IN';
				}
				
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => $operator
					)
				);
			}

			$args['orderby'] = $fa['order'];
			$args['order'] = $fa['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();
			}

			if($args['orderby'] == 'title'){
				$args['order'] = 'ASC';
			}

			if ( $time_diff = $fa['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', vlog_calculate_time_diff( $time_diff ) ) );
			}

			if( !empty( $vlog_unique_module_posts ) ){
				$args['post__not_in'] = $vlog_unique_module_posts;
			}
		}


		$query = new WP_Query( $args );
		
		if ( $fa['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$vlog_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;



/**
 * Get layout columns
 *
 * @param  string $layout Layout ID
 * @return int Bootsrap col-lg ID
 * @since  1.0
 */

if ( !function_exists( 'vlog_layout_columns' ) ):
	function vlog_layout_columns( $layout ) {

		$layouts = array( 
			'a' => 12,
			'b' => 12,
			'c' => 6,
			'd' =>  6,
			'e' =>  4,
			'f' =>  4,
			'g' =>  3,
			'h' =>  3
		);

		return $layouts[$layout];
		
	}
endif;

/**
 * Check if we need to apply eq height class to specific posts module
 *
 * @param  array $module
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_is_eq_height' ) ):
	function vlog_module_is_eq_height( $module ) {

		if( !vlog_module_is_combined($module) ) {
			return true;
		}

		if( ( vlog_layout_columns($module['starter_layout']) * $module['starter_limit'] ) % $module['columns'] ){
			return false;
		}

		return true;
		
	}
endif;
?>