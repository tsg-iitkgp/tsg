<?php

/**
 * Wrapper function for __()
 *
 * It checks if specific text is translated via options panel
 * If option is set, it returns translated text from theme options
 * If option is not set, it returns default translation string (from language file)
 *
 * @param string  $string_key Key name (id) of translation option
 * @return string Returns translated string
 * @since  1.0
 */

if ( !function_exists( '__vlog' ) ):
	function __vlog( $string_key ) {
		if ( ( $translated_string = vlog_get_option( 'tr_'.$string_key ) ) && vlog_get_option( 'enable_translate' ) ) {

			if ( $translated_string == '-1' ) {
				return "";
			}

			return $translated_string;

		} else {

			$translate = vlog_get_translate_options();
			return $translate[$string_key]['text'];
		}
	}
endif;



/**
 * Get featured image
 *
 * Function gets featured image depending on the size and post id.
 * If image is not set, it gets the default featured image placehloder from theme options.
 *
 * @param string  $size               Image size ID
 * @param int     $post_id            Post ID - if not passed it gets current post id by default
 * @param bool    $ignore_default_img Wheter to apply default featured image if post doesn't have featured image
 * @param bool    $ignore_size_suffix Wheter to pass exact size or automatically apply sid or full sufixes depending if sidebar is included or not
 * @return string Image HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_featured_image' ) ):
	function vlog_get_featured_image( $size = 'large', $post_id = false, $ignore_default_img = false, $ignore_size_suffix = false  ) {

		global $vlog_sidebar_opts, $vlog_image_matches;

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( !$ignore_size_suffix && $vlog_sidebar_opts['use_sidebar'] == 'none' ) {
			//Add "full" size
			$size .= '-full';
		}

		//Check if has a matched image size
		if ( !empty( $vlog_image_matches ) && array_key_exists( $size, $vlog_image_matches ) ) {
			$size = $vlog_image_matches[$size];
		}

		if ( has_post_thumbnail( $post_id ) ) {

			return get_the_post_thumbnail( $post_id, $size );

		} else if ( !$ignore_default_img && ( $placeholder = vlog_get_option( 'default_fimg' ) ) ) {

				//If there is no featured image, try to get default from theme options

				global $placeholder_img, $placeholder_imgs;

				if ( empty( $placeholder_img ) ) {
					$img_id = vlog_get_image_id_by_url( $placeholder );
				} else {
					$img_id = $placeholder_img;
				}

				if ( !empty( $img_id ) ) {
					if ( !isset( $placeholder_imgs[$size] ) ) {
						$def_img = wp_get_attachment_image( $img_id, $size );
					} else {
						$def_img = $placeholder_imgs[$size];
					}

					if ( !empty( $def_img ) ) {
						$placeholder_imgs[$size] = $def_img;
						return $def_img;
					}
				}

				return '<img src="'.esc_attr( $placeholder ).'" alt="'.esc_attr( get_the_title( $post_id ) ).'" />';
			}

		return false;
	}
endif;


/**
 * Get post categories
 *
 * Function gets categories for current post and displays and slightly modifies
 * HTML output of category list so we can have category id in class parameter
 *
 * @return string HTML output of category links
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_category' ) ):
	function vlog_get_category() {

		$output = '';
		$cats = get_the_category();
		if ( !empty( $cats ) ) {
			foreach ( $cats as $k => $cat ) {
				$output.= '<a href="'.esc_url( get_category_link( $cat->term_id ) ).'" class="vlog-cat-'.$cat->term_id.'">'.$cat->name.'</a>';
				if ( ( $k + 1 ) != count( $cats ) ) {
					$output.= ', ';
				}
			}
		}
		return $output;
	}
endif;


/**
 * Get post meta data
 *
 * Function outputs meta data HTML based on theme options for specifi layout
 *
 * @param string  $layout     Layout ID
 * @param array   $force_meta Force specific meta instead of using options
 * @return string HTML output of meta data
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_meta_data' ) ):
	function vlog_get_meta_data( $layout = 'a', $force_meta = false ) {

		$meta_data = $force_meta !== false ? $force_meta : array_keys( array_filter( vlog_get_option( 'lay_'.$layout .'_meta' ) ) );

		$output = '';

		if ( !empty( $meta_data ) ) {

			foreach ( $meta_data as $mkey ) {


				$meta = '';

				switch ( $mkey ) {

				case 'date':
					$meta = '<span class="updated meta-icon">'.get_the_date().'</span>';
					break;

				case 'author':
					$author_id = get_post_field( 'post_author', get_the_ID() );
					$meta = '<span class="vcard author"><span class="fn"><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'" class="meta-icon">'.get_the_author_meta( 'display_name', $author_id ).'</a></span></span>';
					break;

				case 'views':
					global $wp_locale;
					$thousands_sep = isset( $wp_locale->number_format['thousands_sep'] ) ? $wp_locale->number_format['thousands_sep'] : ',';
					if ( strlen( $thousands_sep ) > 1 ) {
						$thousands_sep = trim( $thousands_sep );
					}
					$meta = function_exists( 'ev_get_post_view_count' ) ?  number_format_i18n( absint( str_replace( $thousands_sep, '', ev_get_post_view_count( get_the_ID() ) ) + vlog_get_option( 'views_forgery' ) ) )  . ' '.__vlog( 'views' ) : '';
					break;

				case 'rtime':
					$meta = vlog_read_time( get_post_field( 'post_content', get_the_ID() ) );
					if ( !empty( $meta ) ) {
						$meta .= ' '.__vlog( 'min_read' );
					}
					break;

				case 'comments':
					if ( !vlog_is_restricted_post() && ( comments_open() || get_comments_number() ) ) {
						ob_start();
						comments_popup_link( __vlog( 'no_comments' ), __vlog( 'one_comment' ), __vlog( 'multiple_comments' ), '', '' );
						$meta = ob_get_contents();
						ob_end_clean();
					} else {
						$meta = '';
					}
					break;

				default:
					break;
				}

				if ( !empty( $meta ) ) {
					$output .= '<div class="meta-item meta-'.$mkey.'">'.$meta.'</div>';
				}
			}
		}


		return $output;

	}
endif;

/**
 * Get post meta actions
 *
 * Function outputs meta data HTML based on theme options for specific layout
 *
 * @param string  $layout Layout ID
 * @return string HTML output of meta data
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_meta_actions' ) ):
	function vlog_get_meta_actions( $layout = 'a' ) {

		$meta_data = array_keys( array_filter( vlog_get_option( 'lay_'.$layout .'_actions' ) ) );

		$format = vlog_get_post_format();

		if ( $format != 'video' ) {
			$meta_data = array_diff( $meta_data, array( 'watch-later', 'cinema-mode' ) );
		}

		$output = '';

		if ( !empty( $meta_data ) ) {

			foreach ( $meta_data as $mkey ) {


				$meta = '';

				switch ( $mkey ) {

				case 'comments':
					if ( !vlog_is_restricted_post() && (comments_open() || get_comments_number() ) ) {
						ob_start();
						comments_popup_link( __vlog( 'no_comments' ), __vlog( 'one_comment' ), __vlog( 'multiple_comments' ), 'action-item comments' );
						$meta = ob_get_contents();
						ob_end_clean();
					} else {
						$meta = '';
					}
					break;

				case 'watch-later':

					if ( vlog_in_watch_later( get_the_ID() ) ) {
						$action = 'remove';
						$icon = 'fv-added';
						$add_class = 'add hidden';
						$remove_class = 'remove';
					} else {
						$action = 'add';
						$icon = 'fv-watch-later';
						$add_class = 'add';
						$remove_class = 'remove hidden';
					}

					$meta = '<a class="action-item watch-later" href="javascript:void(0);" data-id="'.esc_attr( get_the_ID() ).'" data-action="'.esc_attr( $action ).'"><i class="fv '.esc_attr( $icon ).'"></i> <span class="'.esc_attr( $add_class ).'">'.__vlog( 'watch_later' ).'</span><span class="'.esc_attr( $remove_class ).'">'.__vlog( 'watch_later_remove' ).'</span></a>';

					break;

				case 'cinema-mode':
					if( !vlog_is_restricted_post() ){
						$meta = '<a class="action-item cinema-mode" href="javascript:void(0);" data-id="'.esc_attr( get_the_ID() ).'"><i class="fv fv-fullscreen"></i> '.__vlog( 'cinema_mode' ).'</a>';
					}
					break;

				default:
					break;
				}

				if ( !empty( $meta ) ) {
					$output .= $meta;
				}
			}
		}

		return $output;

	}
endif;




/**
 * Get post excerpt
 *
 * Function outputs post excerpt for specific layout
 *
 * @param string  $layout     Layout ID
 * @param string  $force_meta Force specific meta instead of using options for layout
 * @return string HTML output of category links
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_excerpt' ) ):
	function vlog_get_excerpt( $layout = 'a' ) {

		$manual_excerpt = false;

		if ( has_excerpt() ) {
			$content =  get_the_excerpt();
			$manual_excerpt = true;
		} else {
			$text = get_the_content( '' );
			$text = strip_shortcodes( $text );
			$text = apply_filters( 'the_content', $text );
			$content = str_replace( ']]>', ']]&gt;', $text );
		}


		if ( !empty( $content ) ) {
			$limit = vlog_get_option( 'lay_'.$layout.'_excerpt_limit' );
			if ( !empty( $limit ) || !$manual_excerpt ) {
				$more = vlog_get_option( 'more_string' );
				$content = wp_strip_all_tags( $content );
				$content = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $content );
				$content = vlog_trim_chars( $content, $limit, $more );
			}
			return wpautop( $content );
		}

		return '';

	}
endif;

/**
 * Print module/archive heading
 *
 * Function that outputs the heading of a module based on passed arguments
 *
 * @param array   $args title => heading title, desc => heading description,  actions => action links
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_module_heading' ) ):
	function vlog_module_heading( $args ) {

		$defaults = array(
			'title' => '',
			'desc' => '',
			'actions' => ''
		);

		$args = vlog_parse_args( $args, $defaults );

		$output = '';

		if ( !empty( $args['title'] ) ||  !empty( $args['actions'] ) ) {

			if ( !empty( $args['title'] ) ) {
				$output.= '<div class="vlog-mod-title">'.$args['title'].'</div>';
			}

			if ( !empty( $args['actions'] ) ) {
				$output.= '<div class="vlog-mod-actions">'.$args['actions'].'</div>';
			}

		}

		if ( !empty( $args['desc'] ) ) {
			$output.= '<div class="vlog-mod-desc">'.$args['desc'].'</div>';
		}

		if ( !empty( $output ) ) {
			$output = '<div class="vlog-mod-head">'.$output.'</div>';
		}

		return $output;
	}
endif;


/**
 * Get archive heading
 *
 * Function gets title and description for current template
 *
 * @return string Uses vlog_print_module_heading() to generate HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_archive_heading' ) ):
	function vlog_get_archive_heading() {
		if ( is_category() ) {
			$obj = get_queried_object();

			$args['title'] = __vlog( 'category' ).single_cat_title( '', false );
			$args['desc'] = category_description();

		} else if ( is_author() ) {
				$obj = get_queried_object();

				if(empty( $obj)){
					global $author;
					$obj = isset($_GET['author_name']) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
				}
				
				$args['title'] = __vlog( 'author' ).$obj->display_name;

				if ( vlog_get_option( 'author_desc' ) ) {
					$args['desc'] = wpautop( get_avatar( $obj->ID, 80 ) . get_the_author_meta( 'description', $obj->ID ) ) .vlog_get_author_links( $obj->ID );


				}

			} else if ( is_tax( 'series' ) ) {
				$args['title'] = __vlog( 'serie' ).single_term_title( '', false );
				$args['desc'] = term_description();
			} else if ( is_tax() ) {
				$args['title'] = single_term_title( '', false );
			} else if ( is_home() && ( $posts_page = get_option( 'page_for_posts' ) ) && !is_page_template( 'template-modules.php' ) ) {
				$args['title'] = get_the_title( $posts_page );
			} else if ( is_search() ) {
				$args['title'] = __vlog( 'search_results_for' ).get_search_query();
				$args['desc'] = get_search_form( false );
			} else if ( is_tag() ) {
				$args['title'] = __vlog( 'tag' ).single_tag_title( '', false );
				$args['desc'] = tag_description();
			} else if ( is_day() ) {
				$args['title'] = __vlog( 'archive' ).get_the_date();
			} else if ( is_month() ) {
				$args['title'] = __vlog( 'archive' ).get_the_date( 'F Y' );
			} else if ( is_year() ) {
				$args['title'] = __vlog( 'archive' ).get_the_date( 'Y' );
			} else {
			$args['title'] = '';
			$args['desc'] = '';
		}

		if ( !empty( $args['title'] ) ) {
			$args['title'] = '<h1 class="h4">'.$args['title'].'</h1>';
		}

		if ( !empty( $args['desc'] ) ) {
			$args['desc'] = wpautop( $args['desc'] );
		}

		return vlog_module_heading( $args );
	}
endif;



/**
 * Get post format icon
 *
 * Checks format of current post and returns icon class.
 *
 * @return string Icon HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_post_format_icon' ) ):
	function vlog_post_format_icon() {

		$format = vlog_get_post_format();

		$icons = array(
			'video' => __vlog( 'label_video' ),
			'audio' => __vlog( 'label_audio' ),
			'image' => __vlog( 'label_image' ),
			'gallery' => __vlog( 'label_gallery' )
		);

		//Allow plugins or child themes to modify icons
		$icons = apply_filters( 'vlog_modify_post_format_icons', $icons );

		if ( $format && array_key_exists( $format, $icons ) ) {

			return '<span class="vlog-format-label">'.esc_attr( $icons[$format] ).'</span>';

		}

		return '';
	}
endif;

/**
 * Get post format action
 *
 * Checks format of current post and returns action icon.
 *
 * @param string  $size Icon size class
 * @return string Icon HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_post_format_action' ) ):
	function vlog_post_format_action( $size = 'medium') {


		$format = vlog_get_post_format( true );

		$icons = array(
			'video' => 'fa fa-play',
			'audio' => 'fa fa-play',
			'image' => 'fv fv-fullscreen',
			'gallery' => 'fv fv-fullscreen'
		);

		//Allow plugins or child themes to modify icons
		$icons = apply_filters( 'vlog_post_format_actions', $icons );

		if ( $format && array_key_exists( $format, $icons ) ) {

			return '<span class="vlog-format-action '.esc_attr( $size ).'""><i class="'.esc_attr( $icons[$format] ).'"></i></span>';

		}

		return '';
	}
endif;

/**
 * Get special tag label
 *
 * Checks if post is tagged with special tag defined in theme options
 * and return tag name
 * 
 * @return string HTML output (with tag name)
 * @since  1.5
 */

if ( !function_exists( 'vlog_get_special_tag' ) ):
	function vlog_get_special_tag() {
		
		$tag_id = vlog_get_option('special_tag');

		if( $tag_id && has_tag( $tag_id ) ){

			$tag = get_term( $tag_id, 'post_tag' );

			return '<span class="vlog-special-tag-label">'.esc_html( $tag->name ).'</span>';
		}

		return '';
	}
endif;

/**
 * Get format labels
 *
 * Wrapper function for post format and special tag labels
 *
 * @param string  $layout ID of a layout
 * @param string  $size Icon size class
 * @return string HTML output (with tag name)
 * @since  1.5
 */

if ( !function_exists( 'vlog_labels' ) ):
	function vlog_labels( $layout = 'a', $size = 'medium' ) {
			
		$format_icon = vlog_get_option('lay_'.$layout.'_format_label') ?  vlog_post_format_icon() : '';
		$special_tag = vlog_get_option('lay_'.$layout.'_special_tag') ?  vlog_get_special_tag() : '';

		if(!empty( $format_icon ) || !empty( $special_tag ) ){

			return '<div class="vlog-labels '.esc_attr( $size ).'">'. $format_icon . $special_tag .'</div>';
			
		}

		return '';
	}
endif;


/**
 * Get social share
 *
 * Checkssocial share options to generate sharing buttons
 *
 * @return array List of HTML sharing links
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_social_share' ) ):
	function vlog_get_social_share() {

		$share_options = array_keys( array_filter( vlog_get_option( 'social_share' ) ) );

		if ( empty( $share_options ) ) {
			return false;
		}

		$share = array();
		$share['facebook'] = '<a href="javascript:void(0);" class="vlog-facebook vlog-share-item" data-url="http://www.facebook.com/sharer/sharer.php?u='.urlencode( esc_url( get_permalink() ) ).'&amp;t='.urlencode( esc_attr( get_the_title() ) ).'"><i class="fa fa-facebook"></i><span>'.__vlog( 'share_facebook' ).'</span></a>';
		$share['twitter'] = '<a href="javascript:void(0);" class="vlog-twitter vlog-share-item" data-url="http://twitter.com/intent/tweet?url='.urlencode(esc_url( get_permalink() ) ).'&amp;text='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-twitter"></i><span>'.__vlog( 'share_twitter' ).'</span></a>';
		$share['gplus'] = '<a href="javascript:void(0);"  class="vlog-gplus vlog-share-item" data-url="https://plus.google.com/share?url='.urlencode(esc_url( get_permalink() )).'"><i class="fa fa-google-plus"></i><span>'.__vlog( 'share_gplus' ).'</span></a>';
		$pin_img = has_post_thumbnail() ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) : '';
		$pin_img = isset( $pin_img[0] ) ? $pin_img[0] : '';
		$share['pinterest'] = '<a href="javascript:void(0);"  class="vlog-pinterest vlog-share-item" data-url="http://pinterest.com/pin/create/button/?url='.urlencode(esc_url( get_permalink() )).'&amp;media='.urlencode(esc_attr( $pin_img )).'&amp;description='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-pinterest-p"></i><span>'.__vlog( 'share_pinterest' ).'</span></a>';
		$share['linkedin'] = '<a href="javascript:void(0);"  class="vlog-linkedin vlog-share-item" data-url="http://www.linkedin.com/shareArticle?mini=true&amp;url='.esc_url( get_permalink() ).'&amp;title='.esc_attr( get_the_title() ).'"><i class="fa fa-linkedin"></i><span>'.__vlog( 'share_linkedin' ).'</span></a>';
		$share['reddit'] = '<a href="javascript:void(0);"  class="vlog-reddit vlog-share-item" data-url="http://www.reddit.com/submit?url='.urlencode(esc_url( get_permalink() )).'&amp;title='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-reddit-alien"></i><span>'.__vlog( 'share_reddit' ).'</span></a>';
		$share['email'] = '<a href="mailto:?subject='.urlencode(esc_attr( get_the_title() )).'&amp;body='.urlencode(esc_url( get_permalink() )).'" class="vlog-mailto"><i class="fa fa-envelope-o"></i><span>'.__vlog( 'share_email' ).'</span></a>';
		$share['stumbleupon'] = '<a href="javascript:void(0);"  class="vlog-stumbleupon vlog-share-item" data-url="http://www.stumbleupon.com/badge?url='.urlencode(esc_url( get_permalink() )).'&amp;title='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-stumbleupon"></i><span>'.__vlog( 'share_stumbleupon' ).'</span></a>';
		$share['whatsapp'] = '<a href="whatsapp://send?text='.urlencode(esc_url( get_permalink() )).'" class="vlog-whatsapp"><i class="fa fa-whatsapp"></i><span>'.__vlog( 'share_whatsapp' ).'</span></a>';

		$output = array();
		foreach ( $share_options as $social ) {
			$output[] = $share[$social];
		}

		return $output;


	}
endif;


/**
 * Numeric pagination
 *
 * @param string  $prev Previous link text
 * @param string  $next Next link text
 * @return string Pagination HTML output or empty string
 * @since  1.0
 */

if ( !function_exists( 'vlog_numeric_pagination' ) ):
	function vlog_numeric_pagination( $prev = '&lsaquo;', $next = '&rsaquo;' ) {
		global $wp_query, $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		$pagination = array(
			'base' => @add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'prev_text' => $prev,
			'next_text' => $next,
			'type' => 'plain'
		);
		if ( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if ( !empty( $wp_query->query_vars['s'] ) )
			$pagination['add_args'] = array( 's' => str_replace( ' ', '+', get_query_var( 's' ) ) );

		$links = paginate_links( $pagination );

		return empty( $links ) ? '' : $links;
	}
endif;

/**
 * Print menu posts
 *
 * Used to display mega menu posts
 *
 * @param array   $args for new WP_Query
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'vlog_print_menu_posts' ) ):
	function vlog_print_menu_posts( $args ) {

		$args['ignore_sticky_posts'] = 1;

		$menu_posts = new WP_Query( $args );


		if ( $menu_posts->have_posts() ) :

			while ( $menu_posts->have_posts() ) : $menu_posts->the_post(); ?>

				<article <?php post_class( 'vlog-lay-h lay-horizontal vlog-post col-lg-3 col-md-12 col-sm-12 col-xs-12' ); ?>>
				    <div class="row">

				        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
				            <?php if ( $fimg = vlog_get_featured_image( 'vlog-lay-h', false, false, true ) ) : ?>
				                <div class="entry-image">
				                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
				                   	<?php echo $fimg; ?>
				                </a>
				                </div>
				            <?php endif; ?>
				        </div>

				        <div class="col-lg-7  col-md-7 col-sm-7 col-xs-7 no-left-padding">

				            <div class="entry-header">
				                <?php the_title( sprintf( '<h2 class="entry-title h7"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				            </div>

				        </div>
				    </div>
				</article>

			<?php endwhile;

		endif;

		wp_reset_postdata();
	}
endif;

/**
 * Get single post layout
 *
 * @return string Layout ID
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_single_layout' ) ):
	function vlog_get_single_layout() {

		$layout = vlog_get_post_meta( get_the_ID(), 'layout' );

		if( $layout != 'inherit' ){
			return $layout;
		} 

		if( vlog_get_post_format() == 'video' && vlog_get_option( 'single_video_layout_switch' ) ) {
			$layout = vlog_get_option( 'single_video_layout' );
		} else {
			$layout = vlog_get_option( 'single_fa_layout' );
		}

		return $layout;

	}
endif;

/**
 * Get author social links
 *
 * @param int     $author_id ID of an author/user
 * @return string HTML output of social links
 * @since  1.0
 */

if ( !function_exists( 'vlog_get_author_links' ) ):
	function vlog_get_author_links( $author_id ) {

		$output = '';

		if ( is_single() ) {

			$output .= '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'" class="vlog-button vlog-button-small">'.__vlog( 'view_all' ).'</a>';
		}


		if ( $url = get_the_author_meta( 'url', $author_id ) ) {
			$output .= '<a href="'.esc_url( $url ).'" target="_blank" class="vlog-sl-item fa fa-link"></a>';
		}

		$social = vlog_get_social();

		if ( !empty( $social ) ) {
			foreach ( $social as $id => $name ) {
				if ( $social_url = get_the_author_meta( $id,  $author_id ) ) {

					if ( $id == 'twitter' ) {
						$pos = strpos( $social_url, '@' );
						$social_url = 'https://twitter.com/'.substr( $social_url, $pos, strlen( $social_url ) );
					}

					$output .=  '<a href="'.esc_url( $social_url ).'" target="_blank" class="vlog-sl-item fa fa-'.$id.'"></a>';
				}
			}
		}

		return $output;
	}
endif;

/**
 * Print watch later posts
 *
 * @param array   $args for new WP_Query
 * @return string HTML output
 * @since  1.3
 */

if ( !function_exists( 'vlog_print_watch_later_posts' ) ):
	function vlog_print_watch_later_posts( $args ) {

		$args['ignore_sticky_posts'] = 1;

		$menu_posts = new WP_Query( $args );


		if ( $menu_posts->have_posts() ) :

			while ( $menu_posts->have_posts() ) : $menu_posts->the_post(); ?>

				<article <?php post_class( 'vlog-lay-h lay-horizontal vlog-post wl-post col-lg-3 col-md-12 col-sm-12 col-xs-12' ); ?>>
				    <div class="row">

				        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
				            <?php if ( $fimg = vlog_get_featured_image( 'vlog-lay-h', false, false, true ) ) : ?>
				                <div class="entry-image">
				                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
				                   	<?php echo $fimg; ?>
				                </a>
				                </div>
				            <?php endif; ?>
				        </div>

				        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 no-left-padding">

				            <div class="entry-header">
				                <?php the_title( sprintf( '<h2 class="entry-title h7"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				                <a href="javascript:void(0);" class="vlog-remove-wl" data-id="<?php echo esc_attr( get_the_ID() ); ?>"><i class="fv fv-close"></i></a>
				            </div>

				        </div>
				    </div>
				</article>

			<?php endwhile;

		endif;

		wp_reset_postdata();
	}
endif;

/**
 * Load watch later content 
 *
 * If ajax option is checked it loads it after page is loaded to prevent issues with caching plugins
 * 
 * @return string HTML output
 * @since  1.3
 */

if ( !function_exists( 'vlog_load_watch_later' ) ):
	function vlog_load_watch_later( ) { ?>

		<span>
			<i class="fv fv-watch-later"></i>
			<?php
				$count = count( vlog_get_watch_later_posts() );
				$display = $count ? '' : 'display:none;';
				$display_invert = $count ? 'display:none;' : '';
			?>
			<span class="vlog-watch-later-count pulse" style="<?php echo esc_attr( $display ); ?>"><?php echo $count; ?></span>
		</span>

		<ul class="sub-menu">

			<li class="vlog-menu-posts">
				<?php
					if ( $count ) {
						$args = array( 'post__in' => vlog_get_watch_later_posts() );
						vlog_print_watch_later_posts( $args );
					}
				?>
			</li>

			<li class="vlog-wl-empty" style="<?php echo esc_attr( $display_invert ); ?>">
				<p class="text-center"><i class="fv fv-watch-later"></i> <?php echo esc_attr( __vlog( 'no_videos_message' ) ); ?></p>
				<p class="text-center vlog-small-border"><?php echo esc_attr( __vlog( 'watch_later_message' ) ); ?></p>
			</li>

		</ul>
		
		<?php if(vlog_get_option('watch_later_ajax')) { die(); } ?>

	<?php }
endif;

/**
 * Get post content 
 *
 * Functions gets the post content but strips media from post formats
 * 
 * @return string HTML output
 * @since  1.3
 */

if ( !function_exists( 'vlog_get_content' ) ):
	function vlog_get_content( ) {
		
		$format = vlog_get_post_format();

		if(in_array($format, array('video', 'audio', 'gallery') ) ){
			hybrid_media_grabber( array( 'type' => $format, 'split_media' => true ) );
		}
		ob_start();
		the_content();
		$output = ob_get_clean();
		return $output;
	}
endif;


/**
 * Breadcrumbs
 *
 * Function provides support for several breadcrumb plugins 
 * and gets its content to display on frontend
 * 
 * @return string HTML output
 * @since  1.5
 */

if ( !function_exists( 'vlog_breadcrumbs' ) ):
	function vlog_breadcrumbs( ) {
		
		$has_breadcrumbs = vlog_get_option('breadcrumbs');

		if($has_breadcrumbs == 'none'){
			return '';
		}

		$breadcrumbs = '';
		
		if ( $has_breadcrumbs == 'yoast' && function_exists('yoast_breadcrumb') ) {
				$breadcrumbs = yoast_breadcrumb('<div id="vlog-breadcrumbs" class="vlog-breadcrumbs">','</div>', false );
		}

		if( $has_breadcrumbs == 'bcn' && function_exists('bcn_display')){
        	$breadcrumbs = '<div id="vlog-breadcrumbs" class="vlog-breadcrumbs">'.bcn_display( true ).'</div>';
    	}
	
		return $breadcrumbs;
	}
endif;

?>