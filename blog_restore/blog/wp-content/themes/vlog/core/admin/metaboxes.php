<?php

add_action( 'load-post.php', 'vlog_meta_boxes_setup' );
add_action( 'load-post-new.php', 'vlog_meta_boxes_setup' );

/**
 * Metaboxes main setup
 * 
 * @since  1.0
 */

if ( !function_exists( 'vlog_meta_boxes_setup' ) ) :
	function vlog_meta_boxes_setup() {
		global $typenow;
		if ( $typenow == 'page' ) {
			add_action( 'add_meta_boxes', 'vlog_load_page_metaboxes' );
			add_action( 'save_post', 'vlog_save_page_metaboxes', 10, 2 );
		}

		if ( $typenow == 'post' ) {
			add_action( 'add_meta_boxes', 'vlog_load_post_metaboxes' );
			add_action( 'save_post', 'vlog_save_post_metaboxes', 10, 2 );
		}
	}
endif;


/* Include metaboxes */

include_once( get_template_directory().'/core/admin/metaboxes/page.php');
include_once( get_template_directory().'/core/admin/metaboxes/post.php');
include_once( get_template_directory().'/core/admin/metaboxes/category.php');

if( vlog_is_series_active() ){
	include_once( get_template_directory().'/core/admin/metaboxes/series.php');
}

?>