<?php

/* Allow shortcodes in widgets */
add_filter( 'widget_text', 'do_shortcode' );


/* Add classes to body tag */

add_filter( 'body_class', 'vlog_body_class' );

if ( !function_exists( 'vlog_body_class' ) ):
	function vlog_body_class( $classes ) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		//Add some broswer classes which can be usefull for some css hacks later
		if ( $is_lynx ) $classes[] = 'lynx';
		elseif ( $is_gecko ) $classes[] = 'gecko';
		elseif ( $is_opera ) $classes[] = 'opera';
		elseif ( $is_NS4 ) $classes[] = 'ns4';
		elseif ( $is_safari ) $classes[] = 'safari';
		elseif ( $is_chrome ) $classes[] = 'chrome';
		elseif ( $is_IE ) $classes[] = 'ie';
		else $classes[] = 'unknown';

		if ( $is_iphone ) $classes[] = 'iphone';

		if ( vlog_get_option( 'content_layout' ) == 'boxed' ) {
			$classes[] = 'vlog-boxed';
		}

		return $classes;
	}
endif;


/* Print some stuff from options to head tag */

add_action( 'wp_head', 'vlog_wp_head', 99 );

if ( !function_exists( 'vlog_wp_head' ) ):
	function vlog_wp_head() {

		//Additional CSS (if user adds his custom css inside theme options)
		$additional_css = trim( preg_replace( '/\s+/', ' ', vlog_get_option( 'additional_css' ) ) );
		if ( !empty( $additional_css ) ) {
			echo '<style type="text/css">'.$additional_css.'</style>';
		}

	}
endif;

/* For advanced use - custom JS code into footer if specified in theme options */

add_action( 'wp_footer', 'vlog_wp_footer', 99 );

if ( !function_exists( 'vlog_wp_footer' ) ):
	function vlog_wp_footer() {

		//Additional JS
		$additional_js = trim( preg_replace( '/\s+/', ' ', vlog_get_option( 'additional_js' ) ) );
		if ( !empty( $additional_js ) ) {
			echo '<script type="text/javascript">
				/* <![CDATA[ */
					'.$additional_js.'
				/* ]]> */
				</script>';
		}

	}
endif;




add_filter( 'dynamic_sidebar_params', 'vlog_modify_widget_display' );

/**
 * Widget display callback
 *
 * Check if highlight option is selected and add vlog highlight class to widget
 *
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_modify_widget_display' ) ) :

	function vlog_modify_widget_display( $params ) {

		if ( strpos( $params[0]['id'], 'vlog_footer_sidebar' ) !== false ) {
			return $params; //do not apply highlight styling for footer widgets
		}

		global $wp_registered_widgets;

		$widget_id              = $params[0]['widget_id'];
		$widget_obj             = $wp_registered_widgets[$widget_id];
		$widget_num             = $widget_obj['params'][0]['number'];
		$widget_opt = get_option( $widget_obj['callback'][0]->option_name );

		if ( isset( $widget_opt[$widget_num]['vlog-highlight'] ) && $widget_opt[$widget_num]['vlog-highlight'] == 1 ) {
			$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"vlog-highlight ", $params[0]['before_widget'], 1 );
		}

		return $params;

	}

endif;


/* Add media grabber features */

add_action( 'init', 'vlog_add_media_grabber' );

if ( !function_exists( 'vlog_add_media_grabber' ) ):
	function vlog_add_media_grabber() {
		if ( !class_exists( 'Hybrid_Media_Grabber' ) ) {
			include_once get_template_directory() .'/inc/media-grabber/class-hybrid-media-grabber.php';
		}
	}
endif;

/* Add class to gallery images to run our pop-up and change sizes */

add_filter( 'shortcode_atts_gallery', 'vlog_gallery_atts', 10, 3 );

if ( !function_exists( 'vlog_gallery_atts' ) ):
	function vlog_gallery_atts( $output, $pairs, $atts ) {


		$atts['link'] = 'file';
		$output['link'] = 'file';


		if ( !isset( $output['columns'] ) ) {
			$output['columns'] = 1;
		}

		if ( vlog_get_option( 'auto_gallery_img_sizes' ) ) {
			switch ( $output['columns'] ) {
			case '1' : $output['size'] = 'vlog-lay-a-full'; break;
			case '2' : $output['size'] = 'vlog-lay-b-full'; break;
			case '3' : $output['size'] = 'vlog-lay-e-full'; break;
			case '4' :
			case '5' :
			case '6' :
			case '7' :
			case '8' :
			case '9' : $output['size'] = 'vlog-lay-g-full'; break;
			default: $output['size'] = 'vlog-lay-a-full'; break;
			}

			//Check if has a matched image size
			global $vlog_image_matches;

			if ( !empty( $vlog_image_matches ) && array_key_exists( $output['size'], $vlog_image_matches ) ) {
				$output['size'] = $vlog_image_matches[$output['size']];
			}
		}

		return $output;
	}
endif;

if ( !function_exists( 'vlog_add_class_attachment_link' ) ):
	function vlog_add_class_attachment_link( $link ) {
		$link = str_replace( '<a', '<a class="vlog-popup"', $link );
		return $link;
	}
endif;



/* Unregister Widgets */
add_action( 'widgets_init', 'vlog_unregister_widgets', 99 );

if ( !function_exists( 'vlog_unregister_widgets' ) ):
	function vlog_unregister_widgets() {

		$widgets = array( 'EV_Widget_Entry_Views', 'Series_Widget_List_Posts', 'Series_Widget_List_Related' );

		//Allow child themes or plugins to add/remove widgets they want to unregister
		$widgets = apply_filters( 'vlog_modify_unregister_widgets', $widgets );

		if ( !empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				unregister_widget( $widget );
			}
		}

	}
endif;


/* Remove entry views support for other post types, we need post support only */

add_action( 'init', 'vlog_remove_entry_views_support', 99 );

if ( !function_exists( 'vlog_remove_entry_views_support' ) ):
	function vlog_remove_entry_views_support() {

		$types = array( 'page', 'attachment', 'literature', 'portfolio_item', 'recipe', 'restaurant_item' );

		//Allow child themes or plugins to modify entry views support
		$widgets = apply_filters( 'vlog_modify_entry_views_support', $types );

		if ( !empty( $types ) ) {
			foreach ( $types as $type ) {
				remove_post_type_support( $type, 'entry-views' );
			}
		}

	}
endif;


/* Prevent redirect issue that may brake home page pagination caused by some plugins */
add_filter( 'redirect_canonical', 'vlog_disable_redirect_canonical' );

function vlog_disable_redirect_canonical( $redirect_url ) {
	if ( is_page_template( 'template-modules.php' ) && is_paged() ) {
		$redirect_url = false;
	}
	return $redirect_url;
}



/* Add span elements to post count number in category widget */

add_filter( 'wp_list_categories', 'vlog_add_span_cat_count', 10, 2 );

if ( !function_exists( 'vlog_add_span_cat_count' ) ):
	function vlog_add_span_cat_count( $links, $args ) {

		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] != 'category' ) {
			return $links;
		}

		$links = preg_replace( '/(<a[^>]*>)/', '$1<span class="category-text">', $links );
		$links = str_replace( '</a>', '</span></a>', $links );
		$links = str_replace( '</a> (', '<span class="vlog-count">', $links );
		$links = str_replace( ')', '</span></a>', $links );

		return $links;
	}
endif;

/* Pre get posts */
add_action( 'pre_get_posts', 'vlog_pre_get_posts' );

if ( !function_exists( 'vlog_pre_get_posts' ) ):
	function vlog_pre_get_posts( $query ) {

		if ( !is_admin() && $query->is_main_query() && $query->is_archive() ) {

			$template = vlog_detect_template();

			/* Check whether to change number of posts per page for specific archive template */
			$obj = get_queried_object();

			$ppp = vlog_get_option( $template.'_ppp' );

			if ( $ppp == 'custom' ) {
				$ppp_num = absint( vlog_get_option( $template.'_ppp_num' ) );
				$query->set( 'posts_per_page', $ppp_num );
			}

			/* Serie  */
			if ( $template == 'serie' ) {

				$meta = vlog_get_series_meta( $obj->term_id );

				if ( $meta['layout']['type'] == 'custom' ) {

					$query->set( 'posts_per_page', absint( $meta['layout']['ppp'] ) );
				}

				$ascending = ( $meta['layout']['type'] == 'custom' ) ? $meta['layout']['serie_order_asc'] : vlog_get_option( $template.'_order_asc' );

				if ( $ascending ) {
					$query->set( 'order', 'ASC' );
				}

				$fa = vlog_get_featured_area();

				if ( isset( $fa['query'] ) && !empty( $fa['query'] ) ) {

					$exclude_ids = array();
					
					foreach ( $fa['query']->posts as $p ) {
						$exclude_ids[] = $p->ID;
					}

					$query->set( 'post__not_in', $exclude_ids );

				}

				wp_reset_postdata();

			}

			/* Category */
			if ( $template == 'category' ) {

				$meta = vlog_get_category_meta( $obj->term_id );

				if ( $meta['layout']['type'] == 'custom' ) {

					$query->set( 'posts_per_page', absint( $meta['layout']['ppp'] ) );
				}

				$is_unique_cat = ( $meta['layout']['type'] == 'inherit' ) ? vlog_get_option( $template.'_fa_unique' ) : $meta['layout']['cover_unique'];

				if ( $is_unique_cat ) {

					$fa = vlog_get_featured_area();

					if ( isset( $fa['query'] ) && !empty( $fa['query'] ) ) {

						$exclude_ids = array();
						
						foreach ( $fa['query']->posts as $p ) {
							$exclude_ids[] = $p->ID;
						}

						$query->set( 'post__not_in', $exclude_ids );

					}

					wp_reset_postdata();
				}
			}
		}

	}
endif;


/**
 * Filter function to add class to linked media image for popup
 *
 * @return   $content
 */

add_filter( 'the_content', 'vlog_popup_media_in_content', 100, 1 );

if ( !function_exists( 'vlog_popup_media_in_content' ) ):
	function vlog_popup_media_in_content( $content ) {

		if ( vlog_get_option( 'on_single_img_popup' ) ) {

			$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")>/i";
			$replacement = '<a$1class="vlog-popup-img" href=$2$3.$4$5>';
			$content = preg_replace( $pattern, $replacement, $content );
			return $content;
		}

		return  $content;
	}
endif;

/**
 * Modify WooCommerce wrappers
 *
 * Provide support for WooCommerce pages to match theme HTML markup
 *
 * @return HTML output
 * @since  1.5
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'vlog_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'vlog_woocommerce_wrapper_end', 10 );

if ( !function_exists( 'vlog_woocommerce_wrapper_start' ) ):
	function vlog_woocommerce_wrapper_start() {
		global $vlog_sidebar_opts; 
		$sidebar_class = $vlog_sidebar_opts['use_sidebar'] == 'none' ? 'vlog-single-no-sid' : '';
		echo '<div class="vlog-section '.esc_attr( $sidebar_class ).'"><div class="container"><div class="vlog-content vlog-single-content">';
	}
endif;

if ( !function_exists( 'vlog_woocommerce_wrapper_end' ) ):
	function vlog_woocommerce_wrapper_end() {
		echo '</div>';
	}
endif;

add_action( 'vlog_before_end_content', 'vlog_woocommerce_close_wrap' );

if ( !function_exists( 'vlog_woocommerce_close_wrap' ) ):
	function vlog_woocommerce_close_wrap() {
		if ( vlog_is_woocommerce_active() && vlog_is_woocommerce_page() ) {
			echo '</div></div>';
		}
	}
endif;

?>