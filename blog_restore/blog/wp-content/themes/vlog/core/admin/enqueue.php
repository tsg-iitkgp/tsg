<?php

/* Load admin scripts and styles */
add_action( 'admin_enqueue_scripts', 'vlog_load_admin_scripts' );


/**
 * Load scripts and styles in admin
 *
 * It just wrapps two other separate functions for loading css and js files in admin
 *
 * @return void
 * @since  1.0
 */

function vlog_load_admin_scripts() {
	vlog_load_admin_css();
	vlog_load_admin_js();
}


/**
 * Load admin css files
 *
 * @return void
 * @since  1.0
 */

function vlog_load_admin_css() {
	
	global $pagenow, $typenow;

	
	if ( $typenow == 'page' && ($pagenow == 'post.php' || $pagenow == 'post-new.php') ) {
		wp_enqueue_style ( 'wp-jquery-ui-dialog' );
	}

	//Load small admin style tweaks
	wp_enqueue_style( 'vlog-admin', get_template_directory_uri() . '/assets/css/admin/global.css', false, VLOG_THEME_VERSION, 'screen, print' );
}


/**
 * Load admin js files
 *
 * @return void
 * @since  1.0
 */

function vlog_load_admin_js() {

	global $pagenow, $typenow;

	//Load post & page js
	if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
		if ( $typenow == 'post' ) {
			wp_enqueue_script( 'vlog-post', get_template_directory_uri().'/assets/js/admin/metaboxes-post.js', array( 'jquery' ), VLOG_THEME_VERSION );
		} elseif ( $typenow == 'page' ) {
			wp_enqueue_script( 'vlog-page', get_template_directory_uri().'/assets/js/admin/metaboxes-page.js', array( 'jquery', 'jquery-ui-dialog' ), VLOG_THEME_VERSION );
		}
	}


	//Load category & series JS
	if ( in_array( $pagenow, array('edit-tags.php', 'term.php') ) && isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], array( 'category', 'series' ) ) ) {
		wp_enqueue_script( 'vlog-category', get_template_directory_uri().'/assets/js/admin/metaboxes-category.js', array( 'jquery' ), VLOG_THEME_VERSION );
	}

	//Load widgets JS
	if( $pagenow == 'widgets.php' ){
		wp_enqueue_script( 'vlog-widgets', get_template_directory_uri().'/assets/js/admin/widgets.js', array( 'jquery', 'jquery-ui-sortable'), VLOG_THEME_VERSION );
	}
}

?>