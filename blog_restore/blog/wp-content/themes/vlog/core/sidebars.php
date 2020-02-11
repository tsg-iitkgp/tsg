<?php

add_action( 'widgets_init', 'vlog_register_sidebars' );

/**
 * Register sidebars
 *
 * Callback function for theme sidebars registration and init
 * 
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_register_sidebars' ) ) :
	function vlog_register_sidebars() {
		
		/* Default Sidebar */
		register_sidebar(
			array(
				'id' => 'vlog_default_sidebar',
				'name' => esc_html__( 'Default Sidebar', 'vlog' ),
				'description' => esc_html__( 'This is default sidebar.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);

		/* Default Sticky Sidebar */
		register_sidebar(
			array(
				'id' => 'vlog_default_sticky_sidebar',
				'name' => esc_html__( 'Default Sticky Sidebar', 'vlog' ),
				'description' => esc_html__( 'This is default sticky sidebar. Sticky means that it will be always pinned to top while you are scrolling through your website content.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);


		/* Add sidebars from theme options */
		$custom_sidebars = vlog_get_option( 'sidebars' );

		if (!empty( $custom_sidebars ) ){
			foreach ( $custom_sidebars as $key => $title) {
				
				if ( is_numeric($key) ) {
					register_sidebar(
						array(
							'id' => 'vlog_sidebar_'.$key,
							'name' => $title,
							'description' => '',
							'before_widget' => '<div id="%1$s" class="widget %2$s">',
							'after_widget' => '</div>',
							'before_title' => '<h4 class="widget-title h5"><span>',
							'after_title' => '</span></h4>'
						)
					);
				}
			}
		}


		/* Footer Sidebar Area 1*/
		register_sidebar(
			array(
				'id' => 'vlog_footer_sidebar_1',
				'name' => esc_html__( 'Footer Column 1', 'vlog' ),
				'description' => esc_html__( 'This is footer area column 1.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);

		/* Footer Sidebar Area 2*/
		register_sidebar(
			array(
				'id' => 'vlog_footer_sidebar_2',
				'name' => esc_html__( 'Footer Column 2', 'vlog' ),
				'description' => esc_html__( 'This footer area column 2.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);


		/* Footer Sidebar Area 3*/
		register_sidebar(
			array(
				'id' => 'vlog_footer_sidebar_3',
				'name' => esc_html__( 'Footer Column 3', 'vlog' ),
				'description' => esc_html__( 'This footer area column 3.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);

		/* Footer Sidebar Area 4 */
		register_sidebar(
			array(
				'id' => 'vlog_footer_sidebar_4',
				'name' => esc_html__( 'Footer Column 4', 'vlog' ),
				'description' => esc_html__( 'This is footer area column 4.', 'vlog' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title h5"><span>',
				'after_title' => '</span></h4>'
			)
		);

	}

endif;



add_action( 'wp', 'vlog_set_current_sidebar' );

/**
 * Set current sidebar
 *
 * It checks which sidebar to display based on current template options 
 * and creates a global variable $vlog_sidebar_opts
 *
 * @return array Sidebar layout and values
 * @since  1.0
 */

if ( !function_exists( 'vlog_set_current_sidebar' ) ):
	function vlog_set_current_sidebar() {
		
		global $vlog_sidebar_opts;
		
		/* Default */
		$use_sidebar = 'none';
		$sidebar = 'vlog_default_sidebar';
		$sticky_sidebar = 'vlog_default_sticky_sidebar';

		$vlog_template = vlog_detect_template();

		if ( in_array( $vlog_template, array( 'search', 'tag', 'author', 'archive', 'product', 'product_archive', 'forum', 'topic' ) ) ) {

			$use_sidebar = vlog_get_option( $vlog_template.'_use_sidebar' );


			if ( $use_sidebar != 'none' ) {
				$sidebar = vlog_get_option( $vlog_template.'_sidebar' );
				$sticky_sidebar = vlog_get_option( $vlog_template.'_sticky_sidebar' );
			}

		} else if ( $vlog_template == 'category' ) {
				
				$obj = get_queried_object();
				$meta = vlog_get_category_meta($obj->term_id);
				
				if ( $meta['sidebar']['type'] == 'inherit' ) {
					$use_sidebar = vlog_get_option( $vlog_template.'_use_sidebar' );
					if ( $use_sidebar != 'none' ) {
						$sidebar = vlog_get_option( $vlog_template.'_sidebar' );
						$sticky_sidebar = vlog_get_option( $vlog_template.'_sticky_sidebar' );
					}				
				} else {
					$use_sidebar = $meta['sidebar']['use_sidebar'];
					if ( $use_sidebar != 'none' ) {
						$sidebar = $meta['sidebar']['standard_sidebar'];
						$sticky_sidebar = $meta['sidebar']['sticky_sidebar'];
					}
				}

			} else if ( $vlog_template == 'serie' ) {
				
				$obj = get_queried_object();
				$meta = vlog_get_series_meta($obj->term_id);
				
				if ( $meta['sidebar']['type'] == 'inherit' ) {
					$use_sidebar = vlog_get_option( $vlog_template.'_use_sidebar' );
					if ( $use_sidebar != 'none' ) {
						$sidebar = vlog_get_option( $vlog_template.'_sidebar' );
						$sticky_sidebar = vlog_get_option( $vlog_template.'_sticky_sidebar' );
					}				
				} else {
					$use_sidebar = $meta['sidebar']['use_sidebar'];
					if ( $use_sidebar != 'none' ) {
						$sidebar = $meta['sidebar']['standard_sidebar'];
						$sticky_sidebar = $meta['sidebar']['sticky_sidebar'];
					}
				}

			} else if ( $vlog_template == 'single' ) {

				$meta = vlog_get_post_meta();
				$use_sidebar = ( $meta['use_sidebar'] == 'inherit' ) ? vlog_get_option( $vlog_template.'_use_sidebar' ) : $meta['use_sidebar'];
				if ( $use_sidebar != 'none' ) {
					$sidebar = ( $meta['sidebar'] == 'inherit' ) ?  vlog_get_option( $vlog_template.'_sidebar' ) : $meta['sidebar'];
					$sticky_sidebar = ( $meta['sticky_sidebar'] == 'inherit' ) ?  vlog_get_option( $vlog_template.'_sticky_sidebar' ) : $meta['sticky_sidebar'];
				}

			} else if ($vlog_template == 'page' ) {
				
				$meta = vlog_get_page_meta();
				$use_sidebar = ( $meta['use_sidebar'] == 'inherit' ) ? vlog_get_option( 'page_use_sidebar' ) : $meta['use_sidebar'];
				if ( $use_sidebar != 'none' ) {
					$sidebar = ( $meta['sidebar'] == 'inherit' ) ?  vlog_get_option( 'page_sidebar' ) : $meta['sidebar'];
					$sticky_sidebar = ( $meta['sticky_sidebar'] == 'inherit' ) ?  vlog_get_option( 'page_sticky_sidebar' ) : $meta['sticky_sidebar'];
				}

			}

		$vlog_sidebar_opts = array(
			'use_sidebar' => $use_sidebar,
			'sidebar' => $sidebar,
			'sticky_sidebar' => $sticky_sidebar
		);

	}
endif;

?>