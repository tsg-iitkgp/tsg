<?php


/**
 * Debug (log) function
 *
 * Outputs any content into log file in theme root directory
 *
 * @param mixed   $mixed Content to output
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'vlog_log' ) ):
	function vlog_log( $mixed ) {

		WP_Filesystem();
		global $wp_filesystem;

		if ( is_array( $mixed ) ) {
			$mixed = print_r( $mixed, 1 );
		} else if ( is_object( $mixed ) ) {
				ob_start();
				var_dump( $mixed );
				$mixed = ob_get_clean();
			}

		$old = $wp_filesystem->get_contents( get_template_directory() . '/log' );
		$wp_filesystem->put_contents( get_template_directory() . '/log', $old.$mixed . PHP_EOL, FS_CHMOD_FILE );
	}
endif;

/**
 * Get option value from theme options
 *
 * A wrapper function for WordPress native get_option()
 * which gets an option from specific option key (set in theme options panel)
 *
 * @param string  $option Name of the option
 * @return mixed Specific option value or "false" (if option is not found)
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_option' ) ):
	function vlog_get_option( $option ) {

		global $vlog_settings;

		if ( empty( $vlog_settings ) ) {
			$vlog_settings = get_option( 'vlog_settings' );
		}

		if ( isset( $vlog_settings[$option] ) ) {
			return is_array( $vlog_settings[$option] ) && isset( $vlog_settings[$option]['url'] ) ? $vlog_settings[$option]['url'] : $vlog_settings[$option];
		} else {
			return false;
		}

	}
endif;



/**
 * Get post meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_post_meta' ) ):
	function vlog_get_post_meta( $post_id = false, $field = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$defaults = array(
			'layout' => 'inherit',
			'use_sidebar' => 'inherit',
			'sidebar' => 'inherit',
			'sticky_sidebar' => 'inherit',
		);

		$meta = get_post_meta( $post_id, '_vlog_meta', true );
		$meta = vlog_parse_args( $meta, $defaults );


		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get page meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_page_meta' ) ):
	function vlog_get_page_meta( $post_id = false, $field = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$defaults = array(
			'use_sidebar' => 'inherit',
			'sidebar' => 'inherit',
			'sticky_sidebar' => 'inherit',
			'sections' => array(),
			'pag' => 'none',
			'fa' => array(
				'layout' => '1',
				'limit'  => 5,
				'cat' => array(),
				'tag' => array(),
				'manual' => array(),
				'time' => 0,
				'order' => 'date',
				'format' => 0,
				'unique' => 0,
				'sort' => 'DESC'
			)
		);

		$meta = get_post_meta( $post_id, '_vlog_meta', true );
		$meta = vlog_parse_args( $meta, $defaults );


		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get category meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.5
 */

if ( !function_exists( 'vlog_get_category_meta' ) ):
	function vlog_get_category_meta( $cat_id = false, $field = false ) {
		$defaults = array(
			'layout' => array(
				'type' => 'inherit',
				'cover' => vlog_get_option( 'category_fa_layout' ),
				'cover_ppp' => vlog_get_option( 'category_fa_limit' ),
				'cover_order' => vlog_get_option( 'category_fa_order' ),
				'cover_unique' => vlog_get_option( 'category_fa_unique' ),
				'main' => vlog_get_option( 'category_layout' ),
				'ppp' => vlog_get_option( 'category_ppp_num' ),
				'starter' => vlog_get_option( 'category_starter_layout' ),
				'starter_limit' => vlog_get_option( 'category_starter_limit' ),
				'pagination' => vlog_get_option( 'category_pag' ),

			),
			'sidebar' => array(
				'type' => 'inherit',
				'use_sidebar' => vlog_get_option( 'category_use_sidebar' ),
				'standard_sidebar' => vlog_get_option( 'category_sidebar' ),
				'sticky_sidebar' => vlog_get_option( 'category_sticky_sidebar' ),

			)
		);

		if ( $cat_id ) {
			$meta = get_term_meta( $cat_id, '_vlog_meta', true );
			$meta = wp_parse_args( (array) $meta, $defaults );
		} else {
			$meta = $defaults;
		}

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get serie meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.5
 */

if ( !function_exists( 'vlog_get_series_meta' ) ):
	function vlog_get_series_meta( $serie_id = false, $field = false ) {
		$defaults = array(
			'layout' => array(
				'type' => 'inherit',
				'serie_order_asc' => vlog_get_option( 'serie_order_asc' ),
				'cover' => vlog_get_option( 'serie_fa_layout' ),
				'cover_ppp' => vlog_get_option( 'serie_fa_limit' ),
				'main' => vlog_get_option( 'serie_layout' ),
				'ppp' => vlog_get_option( 'serie_ppp_num' ),
				'starter' => vlog_get_option( 'serie_starter_layout' ),
				'starter_limit' => vlog_get_option( 'serie_starter_limit' ),
				'pagination' => vlog_get_option( 'serie_pag' ),

			),
			'sidebar' => array(
				'type' => 'inherit',
				'use_sidebar' => vlog_get_option( 'serie_use_sidebar' ),
				'standard_sidebar' => vlog_get_option( 'serie_sidebar' ),
				'sticky_sidebar' => vlog_get_option( 'serie_sticky_sidebar' ),

			),
		);


		if ( $serie_id ) {
			$meta = get_term_meta( $serie_id, '_vlog_meta', true );
			$meta = wp_parse_args( (array) $meta, $defaults );
		} else {
			$meta = $defaults;
		}

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get current post layout
 *
 * It checks which posts layout to display based on current template options
 *
 * @param int     $i Index of the current post in loop
 * @return string Layout ID
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_current_post_layout' ) ):
	function vlog_get_current_post_layout( $i ) {

		$layout = 'a'; //layout a as default
		$starter_limit = 0; //do not display starter layout by default

		$vlog_template = vlog_detect_template();

		if ( in_array( $vlog_template, array( 'search', 'tag', 'author', 'archive' ) ) ) {

			$layout = vlog_get_option( $vlog_template.'_layout' );
			$starter_layout = vlog_get_option( $vlog_template.'_starter_layout' );
			$starter_limit = $starter_layout != 'none' ? vlog_get_option( $vlog_template.'_starter_limit' ) : 0;

		} else if ( $vlog_template == 'category' ) {

				$obj = get_queried_object();
				$meta = vlog_get_category_meta( $obj->term_id );

				if ( $meta['layout']['type'] == 'inherit' ) {
					$layout = vlog_get_option( $vlog_template.'_layout' );
					$starter_layout = vlog_get_option( $vlog_template.'_starter_layout' );
					$starter_limit = $starter_layout != 'none' ? vlog_get_option( $vlog_template.'_starter_limit' ) : 0;
				} else {
					$layout = $meta['layout']['main'];
					$starter_layout = $meta['layout']['starter'];
					$starter_limit = $starter_layout != 'none' ? $meta['layout']['starter_limit'] : 0;

				}
		} else if ( $vlog_template == 'serie' ) {

			$obj = get_queried_object();
			$meta = vlog_get_series_meta( $obj->term_id );

			if ( $meta['layout']['type'] == 'inherit' ) {
				$layout = vlog_get_option( $vlog_template.'_layout' );
				$starter_layout = vlog_get_option( $vlog_template.'_starter_layout' );
				$starter_limit = $starter_layout != 'none' ? vlog_get_option( $vlog_template.'_starter_limit' ) : 0;
			} else {
				$layout = $meta['layout']['main'];
				$starter_layout = $meta['layout']['starter'];
				$starter_limit = $starter_layout != 'none' ? $meta['layout']['starter_limit'] : 0;

			}
		}

		if ( !is_paged() && $starter_limit > $i ) {
			return $starter_layout;
		}

		return $layout;
	}
endif;


/**
 * Get current pagination
 *
 * It checks which pagination type to display based on current template options
 *
 * @return string|bool Pagination layout or false if there is no pagination
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_current_pagination' ) ):
	function vlog_get_current_pagination() {

		global $wp_query;

		if ( $wp_query->max_num_pages <= 1 ) {
			return false;
		}

		$layout = 'numeric'; //layout numeric as default

		$vlog_template = vlog_detect_template();

		if ( in_array( $vlog_template, array( 'search', 'tag', 'author', 'archive' ) ) ) {

			$layout = vlog_get_option( $vlog_template.'_pag' );

		} else if ( $vlog_template == 'category' ) {

				$obj = get_queried_object();

				if ( isset( $obj->term_id ) ) {
					$meta = vlog_get_category_meta( $obj->term_id );
					$layout = $meta['layout']['type'] == 'inherit' ? vlog_get_option( $vlog_template.'_pag' ) : $meta['layout']['pagination'];
				}

		} else if ( $vlog_template == 'serie' ) {

			$obj = get_queried_object();

			if ( isset( $obj->term_id ) ) {
				$meta = vlog_get_series_meta( $obj->term_id );
				$layout = $meta['layout']['type'] == 'inherit' ? vlog_get_option( $vlog_template.'_pag' ) : $meta['layout']['pagination'];
			}

		}

		return $layout;
	}
endif;


/**
 * Get post format
 *
 * Checks format of current post and possibly modify it based on specific options
 *
 * @param unknown $restriction_check bool Wheter to check for post restriction (if restricted we threat it as standard)
 * @return string Format value
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_post_format' ) ):
	function vlog_get_post_format( $restriction_check = false ) {

		if ( $restriction_check && vlog_is_restricted_post() ) {
			return 'standard';
		}

		$format = get_post_format();

		if ( empty( $format ) ) {

			$format = 'standard';

			if ( vlog_get_option( 'autodetect_video' ) && hybrid_media_grabber( array( 'type' => 'video', 'split_media' => false ) ) ) {
				$format = 'video';
			}

		}

		return $format;
	}
endif;


/**
 * Calculate time difference
 *
 * @param string  $timestring String to calculate difference from
 * @return  int Time difference in miliseconds
 * @since  1.0
 */

if ( !function_exists( 'vlog_calculate_time_diff' ) ) :
	function vlog_calculate_time_diff( $timestring ) {

		$now = current_time( 'timestamp' );

		switch ( $timestring ) {
		case '-1 day' : $time = $now - DAY_IN_SECONDS; break;
		case '-3 days' : $time = $now - ( 3 * DAY_IN_SECONDS ); break;
		case '-1 week' : $time = $now - WEEK_IN_SECONDS; break;
		case '-1 month' : $time = $now - ( YEAR_IN_SECONDS / 12 ); break;
		case '-3 months' : $time = $now - ( 3 * YEAR_IN_SECONDS / 12 ); break;
		case '-6 months' : $time = $now - ( 6 * YEAR_IN_SECONDS / 12 ); break;
		case '-1 year' : $time = $now - ( YEAR_IN_SECONDS ); break;
		default : $time = $now;
		}

		return $time;
	}
endif;


/* Generate list of additional image sizes
 *
 * @return array List of image size parameters
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_image_sizes' ) ):
	function vlog_get_image_sizes() {

		global $vlog_image_matches;

		$options = array(
			'a' => array( 'sid' => 768, 'full' => 1104 ),
			'b' => array( 'sid' => 366, 'full' => 534 ),
			'c' => array( 'sid' => 366, 'full' => 534 ),
			'd' => array( 'sid' => 165, 'full' => 249 ),
			'e' => array( 'sid' => 232, 'full' => 344 ),
			'f' => array( 'sid' => 76, 'full' => 123 ),
			'g' => array( 'sid' => 165, 'full' => 249 ),
			'h' => array( 'sid' => 83, 'full' => 125 )
		);

		//allow child themes to modify our sizes options
		$options = apply_filters( 'vlog_modify_image_sizes_opts', $options );

		//Check if user has disabled to generate particular image sizes from theme options

		$disable_img_sizes = (array) vlog_get_option( 'disable_img_sizes' );
		$disable_img_sizes = array_keys( array_filter( $disable_img_sizes ) );

		$sizes = array();
		$widths = array();
		$vlog_image_matches = array();

		foreach ( $options as $layout => $opt ) {

			if ( !in_array( $layout, $disable_img_sizes ) ) {

				$lay_sizes = vlog_calculate_image_size( $layout, $opt );

				if ( !empty( $lay_sizes ) ) {

					foreach ( $lay_sizes as $id => $size ) {

						//Check if size with same args already exists and avoid generating same size twice

						if ( !array_key_exists( $size['args']['w'], $widths ) ) {

							$widths[$size['args']['w']][] = $id;
							$sizes[$id] = $size;

						} else {

							$add_size = true;

							foreach ( $widths[$size['args']['w']] as $k => $name ) {
								if ( $size['args']['w'] == $sizes[$name]['args']['w'] && $size['args']['h'] == $sizes[$name]['args']['h'] && $size['args']['crop'] == $sizes[$name]['args']['crop'] ) {
									$add_size = false;
									$vlog_image_matches[$id] = $name;
									continue;
								}
							}

							if ( $add_size ) {
								$sizes[$id] = $size;
							}
						}

					}
				}
			}
		}


		if ( !in_array( 'cover-123', $disable_img_sizes ) ) {
			// Full cover size
			if ( vlog_get_option( 'cover_type' ) == 'fixed' ) {
				$width = absint( vlog_get_option( 'cover_w' ) );
				$crop = true;
			} else {
				$width = 999999;
				$crop = false;
			}

			$sizes['vlog-cover-full'] = array( 'title' => 'Cover Full', 'args' => array( 'w' => $width, 'h' => absint( vlog_get_option( 'cover_h' ) ), 'crop' => $crop ) );
		}


		if ( !in_array( 'cover-4', $disable_img_sizes ) ) {

			//Large cover size
			$sizes['vlog-cover-large'] = array( 'title' => 'Cover Large', 'args' => array( 'w' => 768, 'h' => ( absint( vlog_get_option( 'cover_h' ) ) - 72 ), 'crop' => $crop ) );

		}

		if ( !in_array( 'cover-5', $disable_img_sizes ) ) {

			//Medium cover size
			$sizes['vlog-cover-medium'] = array( 'title' => 'Cover Medium', 'args' => array( 'w' => 600, 'h' => ( absint( vlog_get_option( 'cover_h' ) ) - 72 ), 'crop' => $crop ) );

			//Small cover size
			$sizes['vlog-cover-small'] = array( 'title' => 'Cover Small', 'args' => array( 'w' => 264, 'h' => ( ( absint( vlog_get_option( 'cover_h' ) ) - 144 ) / 2 ), 'crop' => $crop ) );

		}


		//Allow child themes to modify sizes
		$sizes = apply_filters( 'vlog_modify_image_sizes', $sizes );

		return $sizes;
	}
endif;


/**
 * Calculate image size
 *
 * Helper function to calculate image sizes based on specific layout options
 *
 * @param string  $lay   ID of specific layout
 * @param array   $width An array with 'sid' and 'full' arguments representing width of image with sidebar or full width
 * @return array List of generated sizes
 * @since  1.0
 */

if ( !function_exists( 'vlog_calculate_image_size' ) ):
	function vlog_calculate_image_size( $lay, $width ) {

		$sizes = array();

		if ( $ratio = vlog_get_option( 'img_size_lay_'.$lay.'_ratio' ) ) {
			$crop = true;
			if ( $ratio == 'original' ) {
				$height['sid'] = 9999;
				$height['full'] = 9999;
				$crop = false;
			} else if ( $ratio == 'custom' ) {
					$ratio = vlog_get_option( 'img_size_lay_'.$lay.'_custom' );
					$ratio_opts = explode( ":", $ratio );

					if ( !empty( $ratio ) && !empty( $ratio_opts ) ) {
						$height['sid'] = absint( $width['sid'] * absint( $ratio_opts[1] ) / absint( $ratio_opts[0] ) );
						$height['full'] = absint( $width['full'] * absint( $ratio_opts[1] ) / absint( $ratio_opts[0] ) );

					} else {
						//fallback to 16:9 if user haven't set proper ratio
						$height['sid'] = absint( $width['sid'] * 16 / 9 );
						$height['full'] = absint( $width['full'] * 16 / 9 );
					}
				} else {
				$ratio_opts = explode( "_", $ratio );
				$height['sid'] = absint( $width['sid'] * $ratio_opts[1] / $ratio_opts[0] );
				$height['full'] = absint( $width['full'] * $ratio_opts[1] / $ratio_opts[0] );
			}

			$sizes['vlog-lay-'.$lay] = array( 'title' => strtoupper( $lay ), 'args' => array( 'w' => $width['sid'], 'h' => $height['sid'], 'crop' => $crop ) );
			$sizes['vlog-lay-'.$lay.'-full'] = array( 'title' =>  strtoupper( $lay ) . ' (full)', 'args' => array( 'w' => $width['full'], 'h' => $height['full'], 'crop' => $crop ) );
		}

		return $sizes;

	}
endif;



/**
 * Check if RTL mode is enabled
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_is_rtl' ) ):
	function vlog_is_rtl() {

		if ( vlog_get_option( 'rtl_mode' ) ) {
			$rtl = true;
			//Check if current language is excluded from RTL
			$rtl_lang_skip = explode( ",", vlog_get_option( 'rtl_lang_skip' ) );
			if ( !empty( $rtl_lang_skip )  ) {
				$locale = get_locale();
				if ( in_array( $locale, $rtl_lang_skip ) ) {
					$rtl = false;
				}
			}
		} else {
			$rtl = false;
		}

		return $rtl;
	}
endif;


/**
 * Detect WordPress template
 *
 * It checks which template is currently active
 * so we know what set of options to load later
 *
 * @return string Template name prefix we use in options panel
 * @since  1.0
 */

if ( !function_exists( 'vlog_detect_template' ) ):
	function vlog_detect_template() {

		global $vlog_current_template;

		if ( !empty( $vlog_current_template ) ) {
			return $vlog_current_template;
		}

		if ( is_single() ) {

			$type = get_post_type();

			if ( in_array( $type, array( 'product', 'forum', 'topic' ) ) ) {
				$template = $type;
			} else {
				$template = 'single';
			}

		} else if ( is_page_template( 'template-modules.php' ) && is_page() ) {
				$template = 'modules';
			} else if ( is_page() ) {
				$template = 'page';
			} else if ( is_category() ) {
				$template = 'category';
			} else if ( is_tag() ) {
				$template = 'tag';
			} else if ( is_search() ) {
				$template = 'search';
			} else if ( is_author() ) {
				$template = 'author';
			} else if ( is_tax( 'series' ) ) {
				$template = 'serie';
			} else if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_post_type_archive( 'product' ) ) {
				$template = 'product_archive';
			} else if ( is_archive() ) {
				$template = 'archive';
			} else {
			$template = 'archive'; //default
		}

		$vlog_current_template = $template;

		return $template;
	}
endif;


/**
 * Get image ID from URL
 *
 * It gets image/attachment ID based on URL
 *
 * @param string  $image_url URL of image/attachment
 * @return int|bool Attachment ID or "false" if not found
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_image_id_by_url' ) ):
	function vlog_get_image_id_by_url( $image_url ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

		if ( isset( $attachment[0] ) ) {
			return $attachment[0];
		}

		return false;
	}
endif;


/**
 * Calculate reading time by content length
 *
 * @param string  $text Content to calculate
 * @return int Number of minutes
 * @since  1.0
 */

if ( !function_exists( 'vlog_read_time' ) ):
	function vlog_read_time( $text ) {

		$words = count( preg_split( "/[\n\r\t ]+/", wp_strip_all_tags( $text ) ) );

		if ( !empty( $words ) ) {
			$time_in_minutes = ceil( $words / 200 );
			return $time_in_minutes;
		}

		return false;
	}
endif;


/**
 * Trim chars of a string
 *
 * @param string  $string Content to trim
 * @param int     $limit  Number of characters to limit
 * @param string  $more   Chars to append after trimed string
 * @return string Trimmed part of the string
 * @since  1.0
 */

if ( !function_exists( 'vlog_trim_chars' ) ):
	function vlog_trim_chars( $string, $limit, $more = '...' ) {

		if ( !empty( $limit ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $string ), ' ' );
			preg_match_all( '/./u', $text, $chars );
			$chars = $chars[0];
			$count = count( $chars );
			if ( $count > $limit ) {

				$chars = array_slice( $chars, 0, $limit );

				for ( $i = ( $limit - 1 ); $i >= 0; $i-- ) {
					if ( in_array( $chars[$i], array( '.', ' ', '-', '?', '!' ) ) ) {
						break;
					}
				}

				$chars =  array_slice( $chars, 0, $i );
				$string = implode( '', $chars );
				$string = rtrim( $string, ".,-?!" );
				$string.= $more;
			}
		}

		return $string;
	}
endif;


/**
 * Parse args ( merge arrays )
 *
 * Similar to wp_parse_args() but extended to also merge multidimensional arrays
 *
 * @param array   $a - set of values to merge
 * @param array   $b - set of default values
 * @return array Merged set of elements
 * @since  1.0
 */

if ( !function_exists( 'vlog_parse_args' ) ):
	function vlog_parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$r = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = vlog_parse_args( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}
		return $r;
	}
endif;


/**
 * Compare two values
 *
 * Fucntion compares two values and sanitazes 0
 *
 * @param mixed   $a
 * @param mixed   $b
 * @return bool Returns true if equal
 * @since  1.0
 */

if ( !function_exists( 'vlog_compare' ) ):
	function vlog_compare( $a, $b ) {
		return (string) $a === (string) $b;
	}
endif;



/**
 * Hex 2 rgba
 *
 * Convert hexadecimal color to rgba
 *
 * @param string  $color   Hexadecimal color value
 * @param float   $opacity Opacity value
 * @return string RGBA color value
 * @since  1.0
 */

if ( !function_exists( 'vlog_hex2rgba' ) ):
	function vlog_hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) )
			return $default;

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) { $opacity = 1.0; }
			$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';
		} else {
			$output = 'rgb('.implode( ",", $rgb ).')';
		}

		//Return rgb(a) color string
		return $output;
	}
endif;


/**
 * Get list of social options
 *
 * @return array
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_social' ) ) :
	function vlog_get_social() {
		$social = array(
			'apple' => 'Apple',
			'behance' => 'Behance',
			'delicious' => 'Delicious',
			'deviantart' => 'DeviantArt',
			'digg' => 'Digg',
			'dribbble' => 'Dribbble',
			'facebook' => 'Facebook',
			'flickr' => 'Flickr',
			'github' => 'Github',
			'google' => 'GooglePlus',
			'instagram' => 'Instagram',
			'linkedin' => 'LinkedIN',
			'pinterest' => 'Pinterest',
			'reddit' => 'ReddIT',
			'rss' => 'Rss',
			'skype' => 'Skype',
			'stumbleupon' => 'StumbleUpon',
			'soundcloud' => 'SoundCloud',
			'spotify' => 'Spotify',
			'tumblr' => 'Tumblr',
			'twitter' => 'Twitter',
			'vimeo-square' => 'Vimeo',
			'vine' => 'Vine',
			'wordpress' => 'WordPress',
			'xing' => 'Xing' ,
			'yahoo' => 'Yahoo',
			'youtube' => 'Youtube'
		);

		return $social;
	}
endif;

/**
 * Generate dynamic css
 *
 * Function parses theme options and generates css code dynamically
 *
 * @return string Generated css code
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_dynamic_css' ) ):
	function vlog_generate_dynamic_css() {
		ob_start();
		get_template_part( 'assets/css/dynamic-css' );
		$output = ob_get_contents();
		ob_end_clean();
		return vlog_compress_css_code( $output );
	}
endif;


/**
 * Compress CSS Code
 *
 * @param string  $code Uncompressed css code
 * @return string Compressed css code
 * @since  1.0
 */

if ( !function_exists( 'vlog_compress_css_code' ) ) :
	function vlog_compress_css_code( $code ) {

		// Remove Comments
		$code = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code );

		// Remove tabs, spaces, newlines, etc.
		$code = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $code );

		return $code;
	}
endif;

/**
 * Get JS settings
 *
 * Function creates list of settings from thme options to pass
 * them to global JS variable so we can use it in JS files
 *
 * @return array List of JS settings
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_js_settings' ) ):
	function vlog_get_js_settings() {
		$js_settings = array();

		$protocol = is_ssl() ? 'https://' : 'http://';
		$js_settings['ajax_url'] = admin_url( 'admin-ajax.php', $protocol );
		$js_settings['rtl_mode'] = vlog_is_rtl() ? 'true' : 'false';
		$js_settings['header_sticky'] = vlog_get_option( 'header_sticky' ) ? true : false;
		$js_settings['header_sticky_offset'] = absint( vlog_get_option( 'header_sticky_offset' ) );
		$js_settings['header_sticky_up'] = vlog_get_option( 'header_sticky_up' ) ? true : false;
		$js_settings['single_sticky_bar'] = is_single() && vlog_get_option( 'single_sticky_bar' ) ? true : false;
		$js_settings['logo'] = vlog_get_option( 'logo' );
		$js_settings['logo_retina'] = vlog_get_option( 'logo_retina' );
		$js_settings['logo_mini'] = vlog_get_option( 'logo_mini' );
		$js_settings['logo_mini_retina'] = vlog_get_option( 'logo_mini_retina' );
		$js_settings['cover_inplay'] = is_single() && vlog_get_option( 'open_videos_inplay' ) ? true : false;
		$js_settings['watch_later_ajax'] = vlog_get_option( 'watch_later_ajax' ) ? true : false;
		$js_settings['cover_autoplay'] = vlog_get_option( 'cover_autoplay' ) ? true : false;
		$js_settings['cover_autoplay_time'] = absint( vlog_get_option( 'cover_autoplay_time' ) );


		return $js_settings;
	}
endif;


/**
 * Get all translation options
 *
 * @return array Returns list of all options translation available via theme options panel
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_translate_options' ) ):
	function vlog_get_translate_options() {
		global $vlog_translate;
		get_template_part( 'core/translate' );
		$translate = apply_filters( 'vlog_modify_translate_options', $vlog_translate );
		return $translate;
	}
endif;

/**
 * Sort option items
 *
 * Use this function to properly order sortable options like in categories and series module
 *
 * @param unknown $items    Array of items
 * @param unknown $selected Array of IDs of currently selected items
 * @return array ordered items
 * @since  1.0
 */

if ( !function_exists( 'vlog_sort_option_items' ) ):
	function vlog_sort_option_items( $items, $selected, $field = 'term_id' ) {

		if ( empty( $selected ) ) {
			return $items;
		}

		$new_items = array();
		$temp_items = array();
		$temp_items_ids = array();

		foreach ( $selected as $selected_item_id ) {

			foreach ( $items as $item ) {
				if ( $selected_item_id == $item->$field ) {
					$new_items[] = $item;
				} else {
					if ( !in_array( $item->$field, $selected ) && !in_array( $item->$field, $temp_items_ids ) ) {
						$temp_items[] = $item;
						$temp_items_ids[] = $item->$field;
					}
				}
			}

		}

		$new_items = array_merge( $new_items, $temp_items );

		return $new_items;
	}
endif;

/**
 * Generate fonts link
 *
 * Function creates font link from fonts selected in theme options
 *
 * @return string
 * @since  1.0
 */

if ( !function_exists( 'vlog_generate_fonts_link' ) ):
	function vlog_generate_fonts_link() {

		$fonts = array();
		$fonts[] = vlog_get_option( 'main_font' );
		$fonts[] = vlog_get_option( 'h_font' );
		$fonts[] = vlog_get_option( 'nav_font' );
		$unique = array(); //do not add same font links
		$native = vlog_get_native_fonts();
		$protocol = is_ssl() ? 'https://' : 'http://';
		$link = array();

		foreach ( $fonts as $font ) {
			if ( !in_array( $font['font-family'], $native ) ) {
				$temp = array();
				if ( isset( $font['font-style'] ) ) {
					$temp['font-style'] = $font['font-style'];
				}
				if ( isset( $font['subsets'] ) ) {
					$temp['subsets'] = $font['subsets'];
				}
				if ( isset( $font['font-weight'] ) ) {
					$temp['font-weight'] = $font['font-weight'];
				}
				$unique[$font['font-family']][] = $temp;
			}
		}

		$subsets = array( 'latin' ); //latin as default

		foreach ( $unique as $family => $items ) {

			$link[$family] = $family;

			$weight = array( '400' );

			foreach ( $items as $item ) {

				//Check weight and style
				if ( isset( $item['font-weight'] ) && !empty( $item['font-weight'] ) ) {
					$temp = $item['font-weight'];
					if ( isset( $item['font-style'] ) && empty( $item['font-style'] ) ) {
						$temp .= $item['font-style'];
					}

					if ( !in_array( $temp, $weight ) ) {
						$weight[] = $temp;
					}
				}

				//Check subsets
				if ( isset( $item['subsets'] ) && !empty( $item['subsets'] ) ) {
					if ( !in_array( $item['subsets'], $subsets ) ) {
						$subsets[] = $item['subsets'];
					}
				}
			}

			$link[$family] .= ':'.implode( ",", $weight );
			//$link[$family] .= '&subset='.implode( ",", $subsets );
		}

		if ( !empty( $link ) ) {

			$query_args = array(
				'family' => urlencode( implode( '|', $link ) ),
				'subset' => urlencode( implode( ',', $subsets ) )
			);


			$fonts_url = add_query_arg( $query_args, $protocol.'fonts.googleapis.com/css' );

			return esc_url_raw( $fonts_url );
		}

		return '';

	}
endif;


/**
 * Get native fonts
 *
 *
 * @return array List of native fonts
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_native_fonts' ) ):
	function vlog_get_native_fonts() {

		$fonts = array(
			"Arial, Helvetica, sans-serif",
			"'Arial Black', Gadget, sans-serif",
			"'Bookman Old Style', serif",
			"'Comic Sans MS', cursive",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"'Lucida Console', Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
			"Tahoma,Geneva, sans-serif",
			"'Times New Roman', Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif"
		);

		return $fonts;
	}
endif;


/**
 * Get font option
 *
 * @return string Font-family
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_font_option' ) ):
	function vlog_get_font_option( $option = false ) {

		$font = vlog_get_option( $option );
		$native_fonts = vlog_get_native_fonts();
		if ( !in_array( $font['font-family'], $native_fonts ) ) {
			$font['font-family'] = "'".$font['font-family']."'";
		}

		return $font;
	}
endif;


/**
 * Get background
 *
 * @return string background CSS
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_bg_option' ) ):
	function vlog_get_bg_option( $option = false ) {

		$style = vlog_get_option( $option );
		$css = '';

		if ( ! empty( $style ) && is_array( $style ) ) {
			foreach ( $style as $key => $value ) {
				if ( ! empty( $value ) && $key != "media" ) {
					if ( $key == "background-image" ) {
						$css .= $key . ":url('" . $value . "');";
					} else {
						$css .= $key . ":" . $value . ";";
					}
				}
			}
		}

		return $css;
	}
endif;


/**
 * Check if post/page is paginated
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_is_paginated_post' ) ):
	function vlog_is_paginated_post() {

		global $multipage;
		return 0 !== $multipage;

	}
endif;

/**
 * Check if is first page of paginated post
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_is_paginated_post_first_page' ) ):
	function vlog_is_paginated_post_first_page() {

		if ( !vlog_is_paginated_post() ) {
			return false;
		}

		global $page;

		return $page === 1;

	}
endif;

/**
 * Get term slugs by term names for specific taxonomy
 *
 * @param string  $names List of tag names separated by comma
 * @param string  $tax   Taxonomy name
 * @return array List of slugs
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_tax_term_slug_by_name' ) ):
	function vlog_get_tax_term_slug_by_name( $names, $tax = 'post_tag' ) {

		if ( empty( $names ) ) {
			return '';
		}

		$slugs = array();
		$names = explode( ",", $names );

		foreach ( $names as $name ) {
			$tag = get_term_by( 'name', trim( $name ), $tax );

			if ( !empty( $tag ) && isset( $tag->slug ) ) {
				$slugs[] = $tag->slug;
			}
		}

		return $slugs;

	}
endif;


/**
 * Get term names by term slugs for specific taxonomy
 *
 * @param array   $slugs List of tag slugs
 * @param string  $tax   Taxonomy name
 * @return string List of names separrated by comma
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_tax_term_name_by_slug' ) ):
	function vlog_get_tax_term_name_by_slug( $slugs, $tax = 'post_tag' ) {

		if ( empty( $slugs ) ) {
			return '';
		}

		$names = array();

		foreach ( $slugs as $slug ) {
			$tag = get_term_by( 'slug', trim( $slug ), $tax );
			if ( !empty( $tag ) && isset( $tag->name ) ) {
				$names[] = $tag->name;
			}
		}

		if ( !empty( $names ) ) {
			$names = implode( ",", $names );
		} else {
			$names = '';
		}

		return $names;

	}
endif;


/**
 * Check if  a post is in watch later posts
 *
 * @param int     $id Post ID
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_in_watch_later' ) ):
	function vlog_in_watch_later( $id ) {

		if ( !in_array( $id, vlog_get_watch_later_posts() ) ) {
			return false;
		}

		return true;
	}
endif;


/**
 * Get watch later posts
 *
 * @return array List of post IDs
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_watch_later_posts' ) ):
	function vlog_get_watch_later_posts( ) {

		if ( !isset( $_COOKIE['vlog_watch_later'] ) ) {
			return array();
		}

		return explode( "_", $_COOKIE['vlog_watch_later'] );

	}
endif;


/**
 * Get related posts for particular post
 *
 * @param int     $post_id
 * @return object WP_Query
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_related_posts' ) ):
	function vlog_get_related_posts( $post_id = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$args['post_type'] = 'post';

		//Exclude current post from query
		$args['post__not_in'] = array( $post_id );

		//If previuos next posts active exclude them too
		if ( vlog_get_option( 'single_prevnext' ) || vlog_get_option( 'single_cover_prevnext' ) ) {

			$prev_next = vlog_get_prev_next_posts();

			if ( !empty( $prev_next['prev'] ) ) {
				$args['post__not_in'][] = $prev_next['prev']->ID;
			}

			if ( !empty( $prev_next['next'] ) ) {
				$args['post__not_in'][] = $prev_next['next']->ID;
			}
		}

		$num_posts = absint( vlog_get_option( 'related_limit' ) );

		if ( $num_posts > 100 ) {
			$num_posts = 100;
		}

		$args['posts_per_page'] = $num_posts;


		if ( vlog_is_series_active() && has_term( '', 'series' ) ) {

			$series = get_the_terms( $post_id, 'series' );

			if ( !empty( $series ) ) {

				$serie_ids =array();
				foreach ( $series as $serie ) {
					$serie_ids[] = $serie->term_id;
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'series',
						'field'    => 'term_id',
						'terms'    => $serie_ids,
					)
				);

				if ( vlog_get_option( 'serie_order_asc' ) ) {
					$args['order'] = 'ASC';
				}

				$args['orderby'] = 'date';

			}

		} else {

			$args['orderby'] = vlog_get_option( 'related_order' );

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();
			}

			if ( $args['orderby'] == 'title' ) {
				$args['order'] = 'ASC';
			}

			if ( $time_diff = vlog_get_option( 'related_time' ) ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', vlog_calculate_time_diff( $time_diff ) ) );
			}

			if ( $type = vlog_get_option( 'related_type' ) ) {
				switch ( $type ) {

				case 'cat':
					$cats = get_the_category( $post_id );
					$cat_args = array();
					if ( !empty( $cats ) ) {
						foreach ( $cats as $k => $cat ) {
							$cat_args[] = $cat->term_id;
						}
					}
					$args['category__in'] = $cat_args;
					break;

				case 'tag':
					$tags = get_the_tags( $post_id );
					$tag_args = array();
					if ( !empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							$tag_args[] = $tag->term_id;
						}
					}
					$args['tag__in'] = $tag_args;
					break;

				case 'cat_and_tag':
					$cats = get_the_category( $post_id );
					$cat_args = array();
					if ( !empty( $cats ) ) {
						foreach ( $cats as $k => $cat ) {
							$cat_args[] = $cat->term_id;
						}
					}
					$tags = get_the_tags( $post_id );
					$tag_args = array();
					if ( !empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							$tag_args[] = $tag->term_id;
						}
					}
					$args['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field'    => 'id',
							'terms'    => $cat_args,
						),
						array(
							'taxonomy' => 'post_tag',
							'field'    => 'id',
							'terms'    => $tag_args,
						)
					);
					break;

				case 'cat_or_tag':
					$cats = get_the_category( $post_id );
					$cat_args = array();
					if ( !empty( $cats ) ) {
						foreach ( $cats as $k => $cat ) {
							$cat_args[] = $cat->term_id;
						}
					}
					$tags = get_the_tags( $post_id );
					$tag_args = array();
					if ( !empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							$tag_args[] = $tag->term_id;
						}
					}
					$args['tax_query'] = array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'category',
							'field'    => 'id',
							'terms'    => $cat_args,
						),
						array(
							'taxonomy' => 'post_tag',
							'field'    => 'id',
							'terms'    => $tag_args,
						)
					);
					break;

				case 'author':
					global $post;
					$author_id = isset( $post->post_author ) ? $post->post_author : 0;
					$args['author'] = $author_id;
					break;

				case 'default':
					break;
				}
			}
		}

		$related_query = new WP_Query( $args );

		return $related_query;
	}
endif;

/**
 * Get featured area params
 *
 * @return array WP_Query and Layout ID
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_featured_area' ) ):
	function vlog_get_featured_area() {

		$template = vlog_detect_template();

		if ( !in_array( $template, array( 'category', 'serie' ) ) ) {
			return false;
		}

		$obj = get_queried_object();
		$args['post_type'] = 'post';

		if ( $template == 'category' ) {

			$meta = vlog_get_category_meta( $obj->term_id );
			$layout = ( $meta['layout']['type'] == 'inherit' ) ? vlog_get_option( $template . '_fa_layout' ) : $meta['layout']['cover'];

			if ( $layout == 'none' ) {
				return false;
			}

			$args['posts_per_page'] = ( $meta['layout']['type'] == 'custom' ) ? $meta['layout']['cover_ppp'] : vlog_get_option( $template . '_fa_limit' );
			$args['cat'] = $obj->term_id;
			$args['orderby'] = ( $meta['layout']['type'] == 'custom' ) ? $meta['layout']['cover_order'] : vlog_get_option( $template . '_fa_order' );

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();
			}


		} else if ( $template == 'serie' ) {

				$meta = vlog_get_series_meta( $obj->term_id );
				$layout = ( $meta['layout']['type'] == 'inherit' ) ? vlog_get_option( $template . '_fa_layout' ) : $meta['layout']['cover'];

				if ( $layout == 'none' ) {
					return false;
				}

				$args['posts_per_page'] = ( $meta['layout']['type'] == 'custom' ) ? $meta['layout']['cover_ppp'] : vlog_get_option( $template . '_fa_limit' );
				
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'series',
						'field'    => 'term_id',
						'terms'    => $obj->term_id,
					)
				);

				$args['orderby'] = 'date';

				$ascending = $meta['layout']['type'] == 'custom' ? $meta['layout']['serie_order_asc'] : vlog_get_option( $template.'_order_asc' );
				
				$args['order'] = $ascending ? 'ASC' : 'DESC';

			}

		$query = new WP_Query( $args );

		return array( 'query' => $query, 'layout' => $layout );
	}
endif;

/**
 * Get previous/next posts
 *
 * @return array Previous and next post ids
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_prev_next_posts' ) ):
	function vlog_get_prev_next_posts() {

		if ( vlog_is_series_active() && has_term( '', 'series' ) ) {

			$invert = vlog_get_option( 'serie_order_asc' ) ? true : false;
			$prev = get_adjacent_post( true, '', $invert, 'series' );
			$next = get_adjacent_post( true, '', !$invert, 'series' );

		} else {

			$prev = get_adjacent_post( true, '', false, 'category' );
			$next = get_adjacent_post( true, '', true, 'category' );

		}

		return array( 'prev' => $prev, 'next' => $next );

	}
endif;




/**
 * Check if Series plugin is active
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'vlog_is_series_active' ) ):
	function vlog_is_series_active() {

		if ( in_array( 'series/series.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}
endif;


/**
 * Check if post is currently restricted
 *
 * @return bool
 * @since  1.4
 */

if ( !function_exists( 'vlog_is_restricted_post' ) ):
	function vlog_is_restricted_post() {

		//Check if password protected
		if ( post_password_required() ) {
			return true;
		}

		//Check if restricted with Restric Content Pro
		if ( function_exists( 'rcp_user_can_access' ) && !rcp_user_can_access() ) {
			return true;
		}

		return false;
	}
endif;


/**
 * Check if WooCommerce is active
 *
 * @return bool
 * @since  1.5
 */

if ( !function_exists( 'vlog_is_woocommerce_active' ) ):
	function vlog_is_woocommerce_active() {

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check if we are on WooCommerce page
 *
 * @return bool
 * @since  1.5
 */

if ( !function_exists( 'vlog_is_woocommerce_page' ) ):
	function vlog_is_woocommerce_page() {

		return is_singular( 'product' ) || is_tax( 'product_cat' ) || is_post_type_archive( 'product' );
	}
endif;

/**
 * Check if bbPress is active
 *
 * @return bool
 * @since  1.5
 */

if ( !function_exists( 'vlog_is_bbpress_active' ) ):
	function vlog_is_bbpress_active() {

		if ( in_array( 'bbpress/bbpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}
endif;

?>