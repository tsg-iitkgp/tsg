<?php

add_action( 'init', 'vlog_add_mega_menu_support' );

/* Add support for our built in mega menu system */
if ( !function_exists( 'vlog_add_mega_menu_support' ) ):
	function vlog_add_mega_menu_support() {

		if ( vlog_get_option( 'mega_menu' ) ) {
			add_filter( 'wp_edit_nav_menu_walker', 'vlog_edit_menu_walker', 10, 2 );
			add_filter( 'wp_setup_nav_menu_item', 'vlog_add_custom_nav_fields' );
			add_action( 'wp_update_nav_menu_item', 'vlog_update_custom_nav_fields', 10, 3 );
			add_filter( 'nav_menu_css_class', 'vlog_add_class_to_menu', 10, 2 );
		}
	}
endif;

/* Add custom fields to menu */
if ( !function_exists( 'vlog_add_custom_nav_fields' ) ):
	function vlog_add_custom_nav_fields( $menu_item ) {
		$menu_item->mega_menu_cat = get_post_meta( $menu_item->ID, '_vlog_mega_menu_cat', true ) ? 1 : 0;
		return $menu_item;
	}
endif;


/* Save custom fiedls to menu */
if ( !function_exists( 'vlog_update_custom_nav_fields' ) ):
	function vlog_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		if ( $args['menu-item-object'] == 'category' ) {
			$value = isset( $_REQUEST['menu-item-mega-menu-cat'][$menu_item_db_id] ) ? 1 : 0;
			update_post_meta( $menu_item_db_id, '_vlog_mega_menu_cat', $value );
		}
	}
endif;


/* Display our fields in admin */
if ( !function_exists( 'vlog_edit_menu_walker' ) ):
	function vlog_edit_menu_walker( $walker, $menu_id ) {

		class vlog_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				$temp_output = '';
				$mega_menu_html = '';

				if($depth == 0 ){
					if ( $item->object == 'category' ) {
						$mega_menu_html .= '<p class="field-custom description description-wide">
			                <label for="edit-menu-item-mega-'.$item->db_id.'">
			        		<input type="checkbox" id="edit-menu-item-mega-'.$item->db_id.'" class="widefat code edit-menu-item-custom" name="menu-item-mega-menu-cat['.$item->db_id.']" value="1" '.checked( $item->mega_menu_cat, 1, false ). ' />
			                '.esc_html__( 'Mega Menu (display posts from category)', 'vlog' ).'</label>
			            </p>';
					}
				}

				parent::start_el( $temp_output, $item, $depth, $args, $id );

				$temp_output = preg_replace( '/(?=<div.*submitbox)/', $mega_menu_html, $temp_output );

				$output .= $temp_output;
			}

		}

		return 'vlog_Walker_Nav_Menu_Edit';
	}
endif;



/* Output category mega menu */
if ( !function_exists( 'vlog_load_mega_menu' ) ) :

	function vlog_load_mega_menu( $cat_id ) {
		
		
		$ppp = vlog_get_option('mega_menu_limit');
		
		$args = array(
			'post_type'    => 'post',
			'cat'      => $cat_id,
			'posts_per_page' => $ppp
		);

		
		$output = '<li class="vlog-menu-posts">';
		ob_start();

		vlog_print_menu_posts( $args );

		$output .= ob_get_clean();

		$output .= '</li>';

		return $output;
	
	}

endif;

/* Add class to menu item when mega menu is detected */
if ( !function_exists( 'vlog_add_class_to_menu' ) ):
	function vlog_add_class_to_menu( $classes, $item ) {

		if ( $item->object == 'category' && isset( $item->mega_menu_cat ) && $item->mega_menu_cat ) {
			$classes[] = 'vlog-mega-menu menu-item-has-children';
		}

		return $classes;

	}
endif;

/* Mega menu walker */
class vlog_Menu_Walker extends Walker_Nav_menu
{
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		if ( vlog_get_option( 'mega_menu' ) ) {
				
				//print_r( $item);

			if ( $item->mega_menu_cat ) {
				$output .= '<ul class="sub-menu">';
				$output .= vlog_load_mega_menu( $item->object_id );
				$output .= '</ul>';

			}
		}

	}
}

?>