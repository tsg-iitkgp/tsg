<?php
 
/* Load frontend scripts and styles */
add_action( 'wp_enqueue_scripts', 'vlog_load_scripts' );

/**
 * Load scripts and styles on frontend
 *
 * It just wrapps two other separate functions for loading css and js files
 *
 * @return void
 * @since  1.0
 */

function vlog_load_scripts() {
	vlog_load_css();
	vlog_load_js();
}

/**
 * Load frontend css files
 *
 * @return void
 * @since  1.0
 */

function vlog_load_css() {
	
	//Load google fonts
	if( $fonts_link = vlog_generate_fonts_link() ){
		wp_enqueue_style( 'vlog-fonts', $fonts_link, false, VLOG_THEME_VERSION );
	}
	
	//Check if is minified option active and load appropriate files
	if(	vlog_get_option('minify_css') ){
		
		wp_enqueue_style( 'vlog-main', get_template_directory_uri() . '/assets/css/min.css', false, VLOG_THEME_VERSION );
	
	} else {

		$styles = array( 
			'font-awesome' => 'font-awesome.css',
			'vlog-font' => 'vlog-font.css',
			'bootstrap' => 'bootstrap.css',
			'magnific-popup' => 'magnific-popup.css',
			'animate'	=> 'animate.css',
			'owl-carousel' => 'owl-carousel.css',
			'main' => 'main.css'
		);

		foreach ($styles as $id => $style ){
			wp_enqueue_style( 'vlog-'.$id, get_template_directory_uri() . '/assets/css/' . $style, false, VLOG_THEME_VERSION );
		}
	}

	//Append dynamic css
	wp_add_inline_style( 'vlog-main', vlog_generate_dynamic_css() );

	//Load WooCommerce CSS
	if ( vlog_is_woocommerce_active() ) {
		wp_enqueue_style( 'vlog-woocommerce', get_template_directory_uri() . '/assets/css/vlog-woocommerce.css', array( 'vlog-main'), VLOG_THEME_VERSION );
	}

	//Load bbPress CSS
	if ( vlog_is_bbpress_active() ) {
		wp_enqueue_style( 'vlog-bbpress', get_template_directory_uri() . '/assets/css/vlog-bbpress.css', array( 'vlog-main'), VLOG_THEME_VERSION );
	}

	//Load RTL css
	if ( vlog_is_rtl() ) {
		wp_enqueue_style( 'vlog-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array( 'vlog-main'), VLOG_THEME_VERSION );
	}

	//Do not load font awesome from our shortcodes plugin
	wp_dequeue_style( 'mks_shortcodes_fntawsm_css' );
	
}


/**
 * Load frontend js files
 *
 * @return void
 * @since  1.0
 */

function vlog_load_js() {

	//Load comment reply js
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//Check if is minified option active and load appropriate files
	if(	vlog_get_option('minify_js') ){
		
		wp_enqueue_script( 'vlog-main', get_template_directory_uri() . '/assets/js/min.js', array( 'jquery' ), VLOG_THEME_VERSION, true );
	
	} else {

		$scripts = array( 
			'imagesloaded' => 'imagesloaded.js',
			'magnific-popup' => 'magnific-popup.js',
			'fitvids' => 'fitvids.js',
			'sticky-kit' => 'sticky-kit.js',
			'owl-carousel' => 'owl-carousel.js',
			'modernizr' => 'amodernizr.js',
			'dlmenu' => 'dlmenu.js',
			'main' => 'main.js'
		);

		foreach ($scripts as $id => $script ){
			wp_enqueue_script( 'vlog-'.$id, get_template_directory_uri().'/assets/js/'. $script, array( 'jquery' ), VLOG_THEME_VERSION, true );
		}
	}

	wp_localize_script( 'vlog-main', 'vlog_js_settings', vlog_get_js_settings() );
}
?>